"""Compares the distances written on the live map with real road routes (OSRM, OpenStreetMap data)
and looks up the coordinates of each place (Nominatim). Read-only."""
import html, json, re, sys, time, urllib.parse, requests
S = requests.Session(); S.headers['User-Agent'] = 'CaptainsOfJawai-route-check/1.0 (+https://captainsofjawai.com)'
page = S.get('https://captainsofjawai.com/', timeout=40).text
pins = re.findall(r'data-name="([^"]+)" data-km="([^"]*)" data-time="([^"]*)"[^>]*data-gmaps="([^"]+)"', page)
print(len(pins), 'places on the live map')
for name, km, t, g in pins:
    g = html.unescape(g); q = urllib.parse.parse_qs(urllib.parse.urlparse(g).query)
    o = q['origin'][0]; d = q['destination'][0]
    (olat, olng), (dlat, dlng) = map(float, o.split(',')), map(float, d.split(','))
    r = S.get(f'https://router.project-osrm.org/route/v1/driving/{olng},{olat};{dlng},{dlat}?overview=false', timeout=40).json()
    rt = r['routes'][0]
    print(f'{html.unescape(name):40s} site: {km:>7s} {t:>12s} | road (OSRM): {rt["distance"]/1000:6.1f} km {rt["duration"]/3600:4.1f} h | origin {o} dest {d}')
    time.sleep(1)
print('\nGeocoding (Nominatim):')
for qtxt in ['Bera, Pali, Rajasthan', 'Jawai Bandh railway station', 'Falna railway station', 'Maharana Pratap Airport Udaipur', 'Jodhpur Airport', 'Udaipur, Rajasthan', 'Jawai Dam', 'Sumerpur, Pali']:
    r = S.get('https://nominatim.openstreetmap.org/search', params={'q': qtxt, 'format': 'json', 'limit': 2}, timeout=40).json()
    for x in r: print(f'{qtxt:35s} -> {x["lat"][:8]}, {x["lon"][:8]}  {x["display_name"][:90]}')
    time.sleep(1.2)
# Road routes from Bera (Nominatim point) to the geocoded places
