<!doctype html>
<html><head><meta charset="utf-8"><meta name="viewport" content="width=device-width"><title><?= e($title) ?></title></head>
<body style="margin:0;padding:0;background:#F4EFE6;font-family:-apple-system,'Segoe UI',Helvetica,Arial,sans-serif;color:#141618">
<table role="presentation" width="100%" cellpadding="0" cellspacing="0" style="background:#F4EFE6;padding:32px 12px">
<tr><td align="center">
  <table role="presentation" width="600" cellpadding="0" cellspacing="0" style="max-width:600px;width:100%;background:#FFFFFF;border-radius:10px;overflow:hidden;border:1px solid #E8E2D6">
    <tr><td style="background:#121416;padding:22px 28px">
      <table role="presentation" cellpadding="0" cellspacing="0"><tr>
        <td><img src="<?= e($logo) ?>" width="44" height="44" alt="" style="display:block;border-radius:50%"></td>
        <td style="padding-left:12px;font-family:Georgia,'Times New Roman',serif;font-size:21px;color:#FDFBF7;letter-spacing:.3px"><?= e($siteName) ?></td>
      </tr></table>
    </td></tr>
    <tr><td style="height:4px;background:<?= e($accent) ?>"></td></tr>
    <tr><td style="padding:32px 28px 8px;font-size:15px;line-height:1.65;color:#2A2E33"><?= $body ?></td></tr>
    <tr><td style="padding:24px 28px 30px;font-size:12px;line-height:1.6;color:#7A828A;border-top:1px solid #EFEAE0"><?= nl2br(e($footer)) ?></td></tr>
  </table>
</td></tr>
</table>
</body></html>
