<?php
declare(strict_types=1);

namespace App\Core;

use PHPMailer\PHPMailer\PHPMailer;

/** Sends email using the SMTP / transport configured in Admin → Settings → Email & SMTP. */
final class Mailer
{
    /** SMTP conversation captured when send() is called with ['debug' => true]. */
    public static array $lastDebug = [];

    /**
     * @param array{cc?:array,bcc?:array,reply_to?:string,reply_name?:string,context?:string,overrides?:array} $opts
     * @return true|string true on success, error message otherwise
     */
    public static function send(array $to, string $subject, string $textBody, array $opts = []): bool|string
    {
        $to = array_values(array_filter($to, fn ($a) => filter_var($a, FILTER_VALIDATE_EMAIL)));
        if (!$to) {
            return 'No valid recipient address.';
        }
        $cfg = fn (string $k) => array_key_exists($k, $opts['overrides'] ?? []) ? $opts['overrides'][$k] : Settings::get($k);
        $transport = (string) $cfg('mail_transport');
        $context = $opts['context'] ?? 'general';

        if ($transport === 'log') {
            self::log($to, $subject, 'logged', 'Transport set to log-only; not sent.', $context);
            return true;
        }

        $mail = new PHPMailer(true);
        try {
            $mail->CharSet = PHPMailer::CHARSET_UTF8;
            $mail->Encoding = PHPMailer::ENCODING_BASE64;
            if ($transport === 'smtp') {
                $mail->isSMTP();
                $mail->Host = (string) $cfg('smtp_host');
                $mail->Port = (int) $cfg('smtp_port');
                $mail->Timeout = max(5, (int) $cfg('smtp_timeout'));
                $enc = (string) $cfg('smtp_encryption');
                if ($enc === 'ssl') {
                    $mail->SMTPSecure = PHPMailer::ENCRYPTION_SMTPS;
                } elseif ($enc === 'tls') {
                    $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
                } else {
                    $mail->SMTPSecure = '';
                    $mail->SMTPAutoTLS = false;
                }
                $mail->SMTPAuth = in_array((string) $cfg('smtp_auth'), ['1', 'on', 'true'], true);
                if ($mail->SMTPAuth) {
                    $mail->Username = (string) $cfg('smtp_username');
                    $mail->Password = (string) $cfg('smtp_password');
                }
                if (!in_array((string) $cfg('smtp_verify_peer'), ['1', 'on', 'true'], true)) {
                    $mail->SMTPOptions = ['ssl' => ['verify_peer' => false, 'verify_peer_name' => false, 'allow_self_signed' => true]];
                }
                if (!empty($opts['debug'])) {
                    $mail->SMTPDebug = 2;
                    $mail->Debugoutput = function (string $str): void {
                        self::$lastDebug[] = trim($str);
                    };
                }
            } else {
                $mail->isMail();
            }

            $fromEmail = (string) $cfg('mail_from_email');
            if (!filter_var($fromEmail, FILTER_VALIDATE_EMAIL)) {
                $fromEmail = (string) $cfg('smtp_username');
            }
            $mail->setFrom($fromEmail, (string) $cfg('mail_from_name'));
            foreach ($to as $addr) {
                $mail->addAddress($addr);
            }
            foreach ($opts['cc'] ?? [] as $addr) {
                $mail->addCC($addr);
            }
            foreach ($opts['bcc'] ?? [] as $addr) {
                $mail->addBCC($addr);
            }
            if (!empty($opts['reply_to']) && filter_var($opts['reply_to'], FILTER_VALIDATE_EMAIL)) {
                $mail->addReplyTo($opts['reply_to'], $opts['reply_name'] ?? '');
            }
            $mail->Subject = $subject;
            $mail->isHTML(true);
            $mail->Body = self::html($subject, $textBody);
            $mail->AltBody = $textBody . "\n\n--\n" . Settings::get('email_footer');
            $mail->send();
            self::log($to, $subject, 'sent', '', $context);
            return true;
        } catch (\Throwable $e) {
            $err = $mail->ErrorInfo ?: $e->getMessage();
            self::log($to, $subject, 'failed', $err, $context);
            error_log('Mail failed: ' . $err);
            return $err;
        }
    }

    /** Branded HTML wrapper around a plain-text message (links auto-linked). */
    public static function html(string $title, string $text): string
    {
        $accent = (string) Settings::get('color_accent');
        $body = nl2br(e($text));
        $body = preg_replace('~(https?://[^\s<]+)~', '<a href="$1" style="color:' . e($accent) . '">$1</a>', $body);
        return View::partial('emails/layout', [
            'title' => $title,
            'body' => $body,
            'accent' => $accent,
            'logo' => abs_url('/assets/img/emblem-96.png'),
            'siteName' => (string) Settings::get('site_name'),
            'footer' => (string) Settings::get('email_footer'),
        ]);
    }

    /** Replace {placeholders} in a template string. */
    public static function fill(string $template, array $vars): string
    {
        $vars += [
            'site_name' => (string) Settings::get('site_name'),
            'site_url' => base_url(),
        ];
        return preg_replace_callback('/\{([a-z_]+)\}/', fn ($m) => array_key_exists($m[1], $vars) ? (string) $vars[$m[1]] : $m[0], $template);
    }

    private static function log(array $to, string $subject, string $status, string $error, string $context): void
    {
        try {
            DB::insert('email_log', [
                'to_email' => implode(', ', $to),
                'subject' => mb_substr($subject, 0, 250),
                'status' => $status,
                'error' => mb_substr($error, 0, 2000),
                'context' => $context,
                'created_at' => now(),
            ]);
        } catch (\Throwable) {
        }
    }
}
