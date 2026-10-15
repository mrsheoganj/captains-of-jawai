"""Builds numbered contact sheets of Jawai-related Commons candidates (reads commons-candidates.json)."""
import io, json, os, re, requests
from PIL import Image, ImageDraw
rows = json.load(open('commons-candidates.json'))
pat = re.compile(r'jawai|bera\b|bera,|sena\b|pali\b|sumerpur|falna|rabari|ranakpur|kumbhal', re.I)
rows = [r for r in rows if pat.search(r['title'] + ' ' + r['desc'] + ' ' + r['cats'])]
S = requests.Session(); S.headers['User-Agent'] = 'CaptainsOfJawaiWebsite/2.1 (+https://captainsofjawai.com; photo research)'
os.makedirs('sheets', exist_ok=True)
W, H, C, R = 320, 214, 5, 5
idx = []
for s in range(0, len(rows), C * R):
    sheet = Image.new('RGB', (W * C, (H + 18) * R), 'white'); d = ImageDraw.Draw(sheet)
    for i, r in enumerate(rows[s:s + C * R]):
        n = s + i + 1
        idx.append(f"{n:03d} {r['title']} | {r['w']}x{r['h']} | {r['license']} | {r['artist'][:40]}")
        try:
            im = Image.open(io.BytesIO(S.get(r['thumb'], timeout=40).content)).convert('RGB')
            im.thumbnail((W - 4, H - 4)); x, y = (i % C) * W, (i // C) * (H + 18)
            sheet.paste(im, (x + 2, y + 2))
            d.text((x + 4, y + H), f"{n:03d} {r['title'][:44]}", fill='black')
        except Exception as e:
            print('ERR', r['title'], e)
    sheet.save(f'sheets/sheet-{s // (C * R) + 1:02d}.jpg', quality=82)
open('sheets/index.txt', 'w').write('\n'.join(idx) + '\n')
json.dump(rows, open('sheets/candidates.json', 'w'), indent=1)
print(len(rows), 'candidates on', (len(rows) + 24) // 25, 'sheets')
