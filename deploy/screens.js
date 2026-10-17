// Full-page screenshots of the live site (used by the Image audit workflow).
const { chromium } = require('playwright');
(async () => {
  const b = await chromium.launch();
  const pages = ['/', '/jawai/', '/jawai/culture/', '/safaris/', '/gallery/'];
  for (const [w, tag] of [[1366, 'desktop'], [390, 'mobile']]) {
    const p = await b.newPage({ viewport: { width: w, height: 900 } });
    for (const path of pages) {
      try { await p.goto(process.argv[2] + path, { waitUntil: 'load', timeout: 60000 }); } catch (e) { console.log('slow', path); }
      await p.addStyleTag({ content: '.reveal,.reveal-img,[data-stagger]>*{opacity:1!important;transform:none!important} .site-header{position:absolute!important;left:0;right:0}' });
      for (let y = 0; y < 30000; y += 700) { await p.evaluate(v => scrollTo(0, v), y); await p.waitForTimeout(120); }
      await p.evaluate(() => scrollTo(0, 0)); await p.waitForTimeout(800);
      const name = (path === '/' ? 'home' : path.replace(/\//g, '-').replace(/^-|-$/g, '')) + '-' + tag + '.jpg';
      await p.screenshot({ path: 'sheets/screen-' + name, fullPage: true, type: 'jpeg', quality: 60 });
    }
  }
  await b.close();
})();
