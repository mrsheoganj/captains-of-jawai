"""Lists freely-licensed (CC0 / CC BY / CC BY-SA / PD) Jawai-related photos on Wikimedia Commons."""
import json, re, requests
API = 'https://commons.wikimedia.org/w/api.php'
S = requests.Session()
S.headers['User-Agent'] = 'CaptainsOfJawaiWebsite/2.1 (+https://captainsofjawai.com; photo research)'
CATS = ['Category:Jawai Dam', 'Category:Jawai Bandh', 'Category:Jawai', 'Category:Jawai Leopard Conservation Reserve',
        'Category:Bera, Rajasthan', 'Category:Leopards in Rajasthan', 'Category:Jawai River', 'Category:Sumerpur',
        'Category:Rabari people', 'Category:Pali district', 'Category:Falna']
SEARCH = ['Jawai', 'Jawai Bandh', 'Jawai dam', 'Jawai leopard', 'Bera leopard', 'Bera Rajasthan', 'Jawai crocodile',
          'Jawai flamingo', 'Jawai sunset', 'Jawai hills', 'Jawai Rabari', 'Sena Jawai', 'Jawai temple', 'Jawai monsoon',
          'Jawai winter', 'leopard granite Rajasthan', 'Pali Rajasthan leopard', 'Rabari shepherd Rajasthan', 'Aravalli granite hills',
          'Demoiselle crane Rajasthan', 'Indian leopard rock', 'Ranakpur', 'Kumbhalgarh', 'Gypsy safari jeep India']
OK = re.compile(r'^(CC0|CC[ -]BY(-SA)?\b|Public domain|PD\b|Attribution)', re.I)
BAD = re.compile(r'\b(NC|ND)\b|non-?commercial|no ?deriv', re.I)
found = {}
def clean(h): return re.sub(r'\s+', ' ', re.sub(r'<[^>]+>', ' ', h or '')).strip()
def add(pages, why):
    for p in pages:
        ii = (p.get('imageinfo') or [None])[0]
        if not ii or ii.get('mime') not in ('image/jpeg', 'image/png', 'image/webp'): continue
        if ii.get('width', 0) < 1200 or ii.get('height', 0) < 700: continue
        md = ii.get('extmetadata', {})
        lic = clean(md.get('LicenseShortName', {}).get('value', ''))
        if not lic or BAD.search(lic) or not OK.search(lic): continue
        t = p['title'][5:].replace(' ', '_')
        e = found.setdefault(t, {'title': t, 'w': ii['width'], 'h': ii['height'], 'license': lic,
            'artist': clean(md.get('Artist', {}).get('value', ''))[:80],
            'desc': clean(md.get('ImageDescription', {}).get('value', ''))[:200],
            'cats': clean(md.get('Categories', {}).get('value', '')).replace('|', ', ')[:200],
            'thumb': ii.get('thumburl', ''), 'page': ii.get('descriptionurl', ''), 'why': []})
        e['why'].append(why)
props = {'prop': 'imageinfo', 'iiprop': 'url|size|mime|extmetadata', 'iiurlwidth': '640',
         'iiextmetadatafilter': 'LicenseShortName|Artist|ImageDescription|Categories', 'format': 'json', 'formatversion': '2', 'action': 'query'}
for c in CATS:
    r = S.get(API, params={**props, 'generator': 'categorymembers', 'gcmtitle': c, 'gcmtype': 'file', 'gcmlimit': '100'}, timeout=40).json()
    add(r.get('query', {}).get('pages', []), c)
for q in SEARCH:
    r = S.get(API, params={**props, 'generator': 'search', 'gsrsearch': q + ' filetype:bitmap', 'gsrnamespace': '6', 'gsrlimit': '40'}, timeout=40).json()
    add(r.get('query', {}).get('pages', []), 'search:' + q)
rows = sorted(found.values(), key=lambda e: (-len(e['why']), e['title']))
print(f'{len(rows)} usable photos found')
for e in rows:
    print(f"\n{e['title']}  {e['w']}x{e['h']}  {e['license']}  by {e['artist']}\n   why: {', '.join(e['why'][:4])}\n   desc: {e['desc']}\n   cats: {e['cats']}\n   {e['thumb']}")
json.dump(rows, open('commons-candidates.json', 'w'), indent=1)
