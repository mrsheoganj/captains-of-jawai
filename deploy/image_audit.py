"""Read-only audit of the images on the live site. Usage: python3 image_audit.py https://site"""
import io, json, re, sys
from collections import defaultdict
import requests, imagehash
from PIL import Image

SITE = sys.argv[1].rstrip('/')
S = requests.Session()
S.headers['User-Agent'] = 'CaptainsOfJawai-ImageAudit/1.0'

def get(u):
    r = S.get(u, timeout=40)
    r.raise_for_status()
    return r

sm = get(SITE + '/sitemap.xml').text
pages = sorted(set(re.findall(r'<loc>([^<]+)</loc>', sm)))
pages = [p for p in pages if not p.endswith('.xml')]
for extra in ['/gallery/', '/book-a-safari/', '/contact/', '/faq/', '/photo-credits/', '/this-page-does-not-exist/']:
    if SITE + extra not in pages:
        pages.append(SITE + extra)

def key(src):
    src = src.split('?')[0]
    return re.sub(r'-(1600|800|400)\.(jpg|png|webp)$', '', src)

per_page = {}
usage = defaultdict(list)
alts = {}
for p in pages:
    try:
        r = S.get(p, timeout=40)
    except Exception as e:
        print('ERR', p, e); continue
    html = r.text
    imgs = []
    for tag in re.findall(r'<img\b[^>]*>', html):
        m = re.search(r'\ssrc="([^"]+)"', tag)
        if not m: continue
        src = m.group(1)
        if '/assets/img/' in src or src.startswith('data:'): continue
        k = key(src)
        a = re.search(r'\salt="([^"]*)"', tag)
        alts.setdefault(k, a.group(1) if a else '')
        imgs.append(k)
    path = p.replace(SITE, '') or '/'
    per_page[path] = imgs
    for i, k in enumerate(imgs):
        usage[k].append(f'{path}#{i+1}')

print(f'== {len(per_page)} pages, {len(usage)} different image files in use\n')
print('== Repeats on the same page')
for path, imgs in per_page.items():
    d = {k: imgs.count(k) for k in set(imgs) if imgs.count(k) > 1}
    if d:
        print(path); [print(f'   x{n}  {k}') for k, n in sorted(d.items(), key=lambda x: -x[1])]
print('\n== Every page (image order)')
for path, imgs in per_page.items():
    print(path); [print(f'   {i+1:>2}. {k}') for i, k in enumerate(imgs)]

print('\n== Most used images across the site')
for k, u in sorted(usage.items(), key=lambda x: -len(x[1]))[:40]:
    print(f'{len(u):>3}  {k}   [{alts.get(k, "")[:70]}]')

# Perceptual hashes: same photo saved as different files
hashes = {}
for k in usage:
    for suffix in ['-400.jpg', '-800.jpg', '-400.png', '-1600.jpg']:
        try:
            r = S.get(SITE + k + suffix, timeout=40)
            if r.status_code == 200 and r.headers.get('content-type', '').startswith('image'):
                im = Image.open(io.BytesIO(r.content)).convert('RGB')
                hashes[k] = (imagehash.phash(im), imagehash.dhash(im))
                break
        except Exception:
            pass
keys = list(hashes)
groups, seen = [], set()
for i, a in enumerate(keys):
    if a in seen: continue
    g = [a]
    for b in keys[i+1:]:
        if b in seen: continue
        if hashes[a][0] - hashes[b][0] <= 10 and hashes[a][1] - hashes[b][1] <= 12:
            g.append(b); seen.add(b)
    if len(g) > 1:
        seen.add(a); groups.append(g)
print('\n== Different files that are the same (or nearly the same) photo')
for g in groups:
    print('--'); [print(f'   {k}  used {len(usage[k])}x  [{alts.get(k, "")[:60]}]') for k in g]
if not groups: print('   none')

json.dump({'pages': per_page, 'usage': usage, 'alts': alts, 'same_photo_groups': groups,
           'hashes': {k: [str(h[0]), str(h[1])] for k, h in hashes.items()}}, open('image-audit.json', 'w'), indent=1)
