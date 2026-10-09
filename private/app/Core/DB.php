<?php
declare(strict_types=1);

namespace App\Core;

use PDO;
use PDOStatement;

/** Thin PDO wrapper. Works with MySQL/MariaDB (production) and SQLite (fallback / local). */
final class DB
{
    private static ?PDO $pdo = null;

    public static function connect(?array $cfg = null): PDO
    {
        $cfg ??= Config::get('db', []);
        $driver = $cfg['driver'] ?? 'mysql';
        $options = [
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
            PDO::ATTR_EMULATE_PREPARES => false,
        ];
        if ($driver === 'sqlite') {
            $path = $cfg['path'] ?? (STORAGE_PATH . '/database.sqlite');
            $pdo = new PDO('sqlite:' . $path, null, null, $options);
            $pdo->exec('PRAGMA foreign_keys = ON');
            $pdo->exec('PRAGMA journal_mode = WAL');
        } else {
            $dsn = sprintf(
                'mysql:host=%s;port=%d;dbname=%s;charset=utf8mb4',
                $cfg['host'] ?? 'localhost',
                (int) ($cfg['port'] ?? 3306),
                $cfg['name'] ?? ''
            );
            $pdo = new PDO($dsn, $cfg['user'] ?? '', $cfg['pass'] ?? '', $options);
            $pdo->exec("SET time_zone = '" . date('P') . "'");
        }
        return $pdo;
    }

    public static function pdo(): PDO
    {
        return self::$pdo ??= self::connect();
    }

    public static function use(PDO $pdo): void
    {
        self::$pdo = $pdo;
    }

    public static function driver(): string
    {
        return self::pdo()->getAttribute(PDO::ATTR_DRIVER_NAME);
    }

    public static function q(string $sql, array $params = []): PDOStatement
    {
        $st = self::pdo()->prepare($sql);
        $st->execute($params);
        return $st;
    }

    public static function all(string $sql, array $params = []): array
    {
        return self::q($sql, $params)->fetchAll();
    }

    public static function one(string $sql, array $params = []): ?array
    {
        $row = self::q($sql, $params)->fetch();
        return $row === false ? null : $row;
    }

    public static function val(string $sql, array $params = []): mixed
    {
        $v = self::q($sql, $params)->fetchColumn();
        return $v === false ? null : $v;
    }

    public static function insert(string $table, array $data): int
    {
        $cols = array_keys($data);
        $sql = sprintf(
            'INSERT INTO %s (%s) VALUES (%s)',
            self::ident($table),
            implode(', ', array_map([self::class, 'ident'], $cols)),
            implode(', ', array_fill(0, count($cols), '?'))
        );
        self::q($sql, array_values($data));
        return (int) self::pdo()->lastInsertId();
    }

    public static function update(string $table, array $data, string $where, array $params = []): int
    {
        $sets = implode(', ', array_map(fn ($c) => self::ident($c) . ' = ?', array_keys($data)));
        $st = self::q('UPDATE ' . self::ident($table) . " SET $sets WHERE $where", [...array_values($data), ...$params]);
        return $st->rowCount();
    }

    public static function delete(string $table, string $where, array $params = []): int
    {
        return self::q('DELETE FROM ' . self::ident($table) . " WHERE $where", $params)->rowCount();
    }

    public static function ident(string $name): string
    {
        if (!preg_match('/^[a-z_][a-z0-9_]*$/i', $name)) {
            throw new \InvalidArgumentException('Invalid identifier');
        }
        return self::driver() === 'sqlite' ? '"' . $name . '"' : '`' . $name . '`';
    }
}
