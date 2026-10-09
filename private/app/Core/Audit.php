<?php
declare(strict_types=1);

namespace App\Core;

final class Audit
{
    public static function log(string $action, string $entity = '', ?int $entityId = null, array|string $details = ''): void
    {
        try {
            DB::insert('audit_log', [
                'user_id' => Auth::id(),
                'action' => $action,
                'entity' => $entity,
                'entity_id' => $entityId,
                'details' => is_array($details) ? json_encode($details, JSON_UNESCAPED_UNICODE) : $details,
                'ip' => client_ip(),
                'created_at' => now(),
            ]);
        } catch (\Throwable $e) {
            error_log('Audit failed: ' . $e->getMessage());
        }
    }
}
