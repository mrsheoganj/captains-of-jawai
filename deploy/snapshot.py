"""Saves the visible text of key live pages (read-only) into snapshot/ for content checks."""
import html, os, re, requests
SITE = 'https://captainsofjawai.com'
S = requests.Session(); S.headers['User-Agent'] = 'CaptainsOfJawai-snapshot/1.0'
os.makedirs('snapshot', exist_ok=True)
sm = S.get(SITE + '/sitemap.xml', timeout=40).text
urls = sorted(set(re.findall(r'<loc>([^<]+)</loc>', sm))) + [SITE + '/book-a-safari/', SITE + '/faq/']
for u in sorted(set(urls)):
    r = S.get(u, timeout=40); h = r.text
    head = '\n'.join(re.findall(r'<title>[^<]*</title>|<meta name="description"[^>]*>|<link rel="canonical"[^>]*>|<h1[^>]*>.*?</h1>', h, re.S))
    body = re.sub(r'<(script|style|svg)[^>]*>.*?</\1>', ' ', h, flags=re.S)
    body = re.sub(r'<(br|/p|/li|/h\d|/div|/tr|/section|/figcaption|/dt|/dd|/summary)[^>]*>', '\n', body)
    txt = html.unescape(re.sub(r'<[^>]+>', ' ', body))
    txt = '\n'.join(l.strip() for l in re.sub(r'[ \t]+', ' ', txt).split('\n') if l.strip())
    name = (u.replace(SITE, '').strip('/') or 'home').replace('/', '__')
    open(f'snapshot/{name}.txt', 'w').write(f'URL {u}\nSTATUS {r.status_code}\n{head}\n\n{txt}\n')
    ld = re.findall(r'<script type="application/ld\+json">(.*?)</script>', h, re.S)
    if ld: open(f'snapshot/{name}.jsonld.txt', 'w').write('\n\n'.join(ld))
open('snapshot/headers.txt', 'w').write('\n'.join(f'{k}: {v}' for k, v in S.get(SITE + '/assets/css/site.css').headers.items()) + '\n\n' + '\n'.join(f'{k}: {v}' for k, v in S.get(SITE + '/').headers.items()) + '\n\nrobots.txt:\n' + S.get(SITE + '/robots.txt').text)
print(len(os.listdir('snapshot')), 'files')
