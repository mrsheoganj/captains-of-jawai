<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<meta name="robots" content="noindex, nofollow">
<link rel="icon" href="<?= e(favicon_url()) ?>">
<style>
  :root { --bg: #F6F4F0; --card: #FFFFFF; --ink: #1E1A16; --ink-2: #4A433C; --muted: #7A726A; --line: #E7E1D8; --grid: #EFEAE2;
    --accent: #B4532A; --accent-soft: #F6E7DF; --bar: #B4532A; --good: #2F7D4F; --bad: #B23A2E; }
  @media (prefers-color-scheme: dark) {
    :root { --bg: #15120F; --card: #1F1B17; --ink: #F2ECE4; --ink-2: #CFC6BB; --muted: #9A9086; --line: #332D27; --grid: #2A2520;
      --accent: #E07A4D; --accent-soft: #3A2419; --bar: #E07A4D; --good: #6CC08E; --bad: #F08070; }
  }
  * { box-sizing: border-box; }
  body { margin: 0; background: var(--bg); color: var(--ink); font: 15px/1.5 system-ui, -apple-system, "Segoe UI", Roboto, sans-serif; }
  a { color: var(--accent); }
  .wrap { max-width: 1180px; margin: 0 auto; padding: 24px 16px 60px; }
  .card { background: var(--card); border: 1px solid var(--line); border-radius: 14px; padding: 18px 18px 14px; }
  h1 { font-size: 22px; margin: 0; letter-spacing: -.01em; }
  h2 { font-size: 15px; margin: 0 0 12px; }
  .muted { color: var(--muted); }
  .btn { appearance: none; border: 1px solid var(--line); background: var(--card); color: var(--ink); border-radius: 999px; padding: 8px 16px; font: inherit; font-weight: 600; cursor: pointer; text-decoration: none; display: inline-block; }
  .btn-primary { background: var(--accent); border-color: var(--accent); color: #fff; }
</style>
