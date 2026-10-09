<?php
declare(strict_types=1);

namespace App\Core;

/** Symmetric encryption for secrets stored in the database (e.g. SMTP password). */
final class Crypto
{
    private static function key(): string
    {
        $key = (string) Config::get('app_key', '');
        if ($key === '') {
            throw new \RuntimeException('app_key missing from config.php');
        }
        return hash('sha256', $key, true);
    }

    public static function encrypt(string $plain): string
    {
        if (function_exists('sodium_crypto_secretbox')) {
            $nonce = random_bytes(SODIUM_CRYPTO_SECRETBOX_NONCEBYTES);
            return 'v1:' . base64_encode($nonce . sodium_crypto_secretbox($plain, $nonce, self::key()));
        }
        $iv = random_bytes(16);
        $cipher = openssl_encrypt($plain, 'aes-256-cbc', self::key(), OPENSSL_RAW_DATA, $iv);
        $mac = hash_hmac('sha256', $iv . $cipher, self::key(), true);
        return 'v2:' . base64_encode($iv . $mac . $cipher);
    }

    public static function decrypt(string $value): string
    {
        try {
            if (str_starts_with($value, 'v1:') && function_exists('sodium_crypto_secretbox_open')) {
                $raw = base64_decode(substr($value, 3), true);
                $nonce = substr($raw, 0, SODIUM_CRYPTO_SECRETBOX_NONCEBYTES);
                $plain = sodium_crypto_secretbox_open(substr($raw, SODIUM_CRYPTO_SECRETBOX_NONCEBYTES), $nonce, self::key());
                return $plain === false ? '' : $plain;
            }
            if (str_starts_with($value, 'v2:')) {
                $raw = base64_decode(substr($value, 3), true);
                $iv = substr($raw, 0, 16);
                $mac = substr($raw, 16, 32);
                $cipher = substr($raw, 48);
                if (!hash_equals(hash_hmac('sha256', $iv . $cipher, self::key(), true), $mac)) {
                    return '';
                }
                return (string) openssl_decrypt($cipher, 'aes-256-cbc', self::key(), OPENSSL_RAW_DATA, $iv);
            }
        } catch (\Throwable) {
            return '';
        }
        return $value; // stored before encryption was enabled
    }
}
