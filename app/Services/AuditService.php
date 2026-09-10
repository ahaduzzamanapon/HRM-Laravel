<?php

namespace App\Services;

use App\Models\EnterpriseAuditLog;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Request;

class AuditService
{
    /**
     * Log an audit record for permission-controlled actions.
     */
    public static function log($module, $action, $permissionKey = null, $record = null, $oldValues = null, $newValues = null)
    {
        try {
            $user = Auth::user();

            $recordId = null;
            $recordType = null;

            if (is_object($record)) {
                $recordType = get_class($record);
                $recordId = isset($record->id) ? (string) $record->id : null;
            } elseif (is_scalar($record)) {
                $recordId = (string) $record;
            }

            $branchId = null;
            if (function_exists('userBranchId')) {
                $branchId = userBranchId($user);
            }

            EnterpriseAuditLog::create([
                'user_id' => $user->id ?? null,
                'user_name' => $user ? trim(($user->name ?? '') . ' ' . ($user->last_name ?? '')) : 'Guest',
                'user_role' => $user && $user->role ? ($user->role->name ?? $user->role->key) : 'N/A',
                'branch_id' => $branchId,
                'module' => $module,
                'permission_key' => $permissionKey,
                'action' => strtolower($action),
                'record_id' => $recordId,
                'record_type' => $recordType,
                'old_values' => $oldValues,
                'new_values' => $newValues,
                'ip_address' => Request::ip(),
                'user_agent' => Request::userAgent(),
            ]);
        } catch (\Throwable $e) {
            // Silently swallow log failure to prevent breaking main business operations
            \Illuminate\Support\Facades\Log::error('Audit log failed: ' . $e->getMessage());
        }
    }
}
