"""Finds freely-licensed safari jeep / Gypsy photos on Commons and builds a numbered contact sheet."""
import io, json, os, re, requests
from PIL import Image, ImageDraw
API = 'https://commons.wikimedia.org/w/api.php'
S = requests.Session(); S.headers['User-Agent'] = 'CaptainsOfJawaiWebsite/2.1 (+https://captainsofjawai.com; photo research)'
OK = re.compile(r'^(CC0|CC[ -]BY(-SA)?\b|Public domain|PD\b|Attribution)', re.I); BAD = re.compile(r'\b(NC|ND)\b|non-?commercial|no ?deriv', re.I)
Q = ['Jawai safari', 'Jawai jeep', 'Jawai gypsy', 'Bera safari jeep', 'Maruti Gypsy safari', 'Gypsy safari India', 'safari jeep Rajasthan',
     'open jeep safari India', 'Ranthambore gypsy', 'Ranthambore safari jeep', 'jungle safari jeep India', 'Gypsy jeep national park', 'leopard safari jeep India',
     'safari vehicle tiger reserve India', 'Jawai tourists leopard']
CATS = ['Category:Maruti Gypsy', 'Category:Safari vehicles in India', 'Category:Jawai Bandh']
props = {'prop': 'imageinfo', 'iiprop': 'url|size|mime|extmetadata', 'iiurlwidth': '640', 'iiextmetadatafilter': 'LicenseShortName|Artist|ImageDescription|Categories', 'format': 'json', 'formatversion': '2', 'action': 'query'}
found = {}
def clean(h): return re.sub(r'\s+', ' ', re.sub(r'<[^>]+>', ' ', h or '')).strip()
def add(pages, why):
    for p in pages:
        ii = (p.get('imageinfo') or [None])[0]
        if not ii or ii.get('mime') not in ('image/jpeg', 'image/png') or ii.get('width', 0) < 1000: continue
        md = ii.get('extmetadata', {}); lic = clean(md.get('LicenseShortName', {}).get('value', ''))
        if not lic or BAD.search(lic) or not OK.search(lic): continue
        t = p['title'][5:].replace(' ', '_')
        found.setdefault(t, {'title': t, 'w': ii['width'], 'h': ii['height'], 'license': lic, 'artist': clean(md.get('Artist', {}).get('value', ''))[:50],
                             'desc': clean(md.get('ImageDescription', {}).get('value', ''))[:160], 'cats': clean(md.get('Categories', {}).get('value', ''))[:160], 'thumb': ii.get('thumburl', ''), 'why': why})
for c in CATS:
    add(S.get(API, params={**props, 'generator': 'categorymembers', 'gcmtitle': c, 'gcmtype': 'file', 'gcmlimit': '60'}, timeout=40).json().get('query', {}).get('pages', []), c)
for q in Q:
    add(S.get(API, params={**props, 'generator': 'search', 'gsrsearch': q + ' filetype:bitmap', 'gsrnamespace': '6', 'gsrlimit': '30'}, timeout=40).json().get('query', {}).get('pages', []), q)
rows = [r for r in found.values() if re.search(r'gypsy|jeep|safari|vehicle|4x4|tourist', r['title'] + r['desc'] + r['cats'], re.I)]
os.makedirs('sheets', exist_ok=True)
W, H, C, R = 320, 214, 5, 5; idx = []
for s in range(0, len(rows), C * R):
    sheet = Image.new('RGB', (W * C, (H + 18) * R), 'white'); d = ImageDraw.Draw(sheet)
    for i, r in enumerate(rows[s:s + C * R]):
        n = s + i + 1; idx.append(f"{n:03d} {r['title']} | {r['w']}x{r['h']} | {r['license']} | {r['artist']} | {r['desc'][:100]} | {r['cats'][:100]}")
        try:
            im = Image.open(io.BytesIO(S.get(r['thumb'], timeout=40).content)).convert('RGB'); im.thumbnail((W - 4, H - 4))
            sheet.paste(im, ((i % C) * W + 2, (i // C) * (H + 18) + 2)); d.text(((i % C) * W + 4, (i // C) * (H + 18) + H), f"{n:03d} {r['title'][:44]}", fill='black')
        except Exception as e: print('ERR', r['title'], e)
    sheet.save(f'sheets/vehicle-{s // (C * R) + 1:02d}.jpg', quality=82)
open('sheets/vehicle-index.txt', 'w').write('\n'.join(idx) + '\n'); print(len(rows), 'vehicle candidates')
