<?php

use Illuminate\Support\Facades\File;
use App\Services\AuthorizationEngine;
use App\Services\AuditService;

if (!function_exists('uploadFile')) {
    /**
     * Upload a file to a specified folder and return the file path.
     *
     * @param \Illuminate\Http\UploadedFile $file The file to upload.
     * @param string $folder The folder where the file will be stored.
     * @param string|null $name Optional custom file name (without extension).
     * @return string The relative path of the uploaded file.
     */
    function uploadFile($file, $folder, $name = null)
    {
        $path = public_path($folder);

        if (!File::exists($path)) {
            File::makeDirectory($path, 0775, true, true);
        }

        $filename = $name
            ? $name . '.' . $file->getClientOriginalExtension()
            : time() . '_' . $file->getClientOriginalName();

        $file->move($path, $filename);

        return $folder . '/' . $filename;
    }
}

if (!function_exists('can')) {
    /**
     * Dynamic Permission Check powered by AuthorizationEngine.
     */
    function can($key, $user = null)
    {
        return AuthorizationEngine::can($key, $user);
    }
}

if (!function_exists('isSuperAdmin')) {
    /**
     * Check if a user is a Super Admin.
     */
    function isSuperAdmin($user = null)
    {
        return AuthorizationEngine::isSuperAdmin($user);
    }
}

if (!function_exists('userBranchId')) {
    /**
     * Get assigned branch ID for user.
     */
    function userBranchId($user = null)
    {
        return AuthorizationEngine::getUserBranchId($user);
    }
}

if (!function_exists('hasBranchAccess')) {
    /**
     * Check whether a user has access to a specific branch ID.
     */
    function hasBranchAccess($targetBranchId, $user = null)
    {
        if (isSuperAdmin($user) || AuthorizationEngine::isHRRole($user)) {
            return true;
        }
        $bId = userBranchId($user);
        if (!$bId) {
            return true;
        }
        return (int)$bId === (int)$targetBranchId;
    }
}

if (!function_exists('checkBranchAccess')) {
    /**
     * Abort request with 403 if user doesn't have access to the target branch ID.
     */
    function checkBranchAccess($targetBranchId, $user = null)
    {
        if (!hasBranchAccess($targetBranchId, $user)) {
            abort(403, 'You do not have permission to manage or access resources outside your assigned branch.');
        }
    }
}

if (!function_exists('applyBranchScope')) {
    /**
     * Apply branch scope to a query builder instance for non-superadmins.
     */
    function applyBranchScope($query, $column = 'branch_id', $user = null)
    {
        return AuthorizationEngine::applyScope($query, $user);
    }
}

if (!function_exists('applyUserBranchScope')) {
    /**
     * Apply branch scope through user relation to a query builder instance for non-superadmins.
     */
    function applyUserBranchScope($query, $userRelation = 'user', $user = null)
    {
        return AuthorizationEngine::applyScope($query, $user);
    }
}

if (!function_exists('canManageBranch')) {
    /**
     * Returns true if the current user is a Super Admin, HR, OR belongs to the given branch.
     */
    function canManageBranch($branchId, $user = null)
    {
        if (isSuperAdmin($user) || AuthorizationEngine::isHRRole($user)) {
            return true;
        }
        $myBranch = userBranchId($user);
        if (!$myBranch) {
            return true;
        }
        return (int) $myBranch === (int) $branchId;
    }
}

if (!function_exists('enforceBranchOwnership')) {
    /**
     * Abort 403 if the current user does not have access to the given branch-owned record.
     */
    function enforceBranchOwnership($branchIdOrModel, $branchColumn = 'branch_id', $user = null)
    {
        if (isSuperAdmin($user) || AuthorizationEngine::isHRRole($user)) {
            return;
        }

        $targetBranchId = null;
        if (is_object($branchIdOrModel)) {
            if (isset($branchIdOrModel->$branchColumn)) {
                $targetBranchId = $branchIdOrModel->$branchColumn;
            } elseif (isset($branchIdOrModel->user) && isset($branchIdOrModel->user->branch_id)) {
                $targetBranchId = $branchIdOrModel->user->branch_id;
            } elseif (isset($branchIdOrModel->employee) && isset($branchIdOrModel->employee->branch_id)) {
                $targetBranchId = $branchIdOrModel->employee->branch_id;
            } else {
                return;
            }
        } else {
            $targetBranchId = $branchIdOrModel;
        }

        if ($targetBranchId === null) {
            return;
        }

        $myBranch = userBranchId($user);
        if ($myBranch && (int) $myBranch !== (int) $targetBranchId) {
            abort(403, 'You do not have permission to access resources outside your assigned branch.');
        }
    }
}

if (!function_exists('applyBranchScopeWithGlobal')) {
    /**
     * Apply branch scope but ALSO include records where branch_id IS NULL (global records).
     */
    function applyBranchScopeWithGlobal($query, $column = 'branch_id', $user = null)
    {
        if (!isSuperAdmin($user)) {
            $bId = userBranchId($user);
            if ($bId) {
                $query->where(function ($q) use ($column, $bId) {
                    $q->where($column, $bId)->orWhereNull($column);
                });
            }
        }
        return $query;
    }
}

if (!function_exists('getEmployeeDropdownOptions')) {
    /**
     * Get employees formatted as [id => "First Last (EMP-123)"] for dropdowns.
     * Respects branch scope for non-superadmins.
     */
    function getEmployeeDropdownOptions($query = null)
    {
        $q = $query ?: \App\Models\User::query();
        applyBranchScope($q, 'branch_id');
        return $q->get()->mapWithKeys(function ($u) {
            $name = trim(($u->name ?? '') . ' ' . ($u->last_name ?? ''));
            if ($u->emp_id) {
                $name .= ' (ID: ' . $u->emp_id . ')';
            }
            return [$u->id => $name];
        });
    }
}

if (!function_exists('canPerformAction')) {
    /**
     * Evaluate action-level permission and ownership for a record.
     */
    function canPerformAction($action, $permissionKey = null, $record = null, $user = null)
    {
        return AuthorizationEngine::canPerformAction($action, $permissionKey, $record, $user);
    }
}

if (!function_exists('canViewMenu')) {
    /**
     * Check menu authorization for module.
     */
    function canViewMenu($moduleKey, $user = null)
    {
        return AuthorizationEngine::canViewMenu($moduleKey, $user);
    }
}

if (!function_exists('canViewWidget')) {
    /**
     * Check widget authorization.
     */
    function canViewWidget($widgetKey, $user = null)
    {
        return AuthorizationEngine::canViewWidget($widgetKey, $user);
    }
}

if (!function_exists('canViewReport')) {
    /**
     * Check report authorization.
     */
    function canViewReport($reportKey, $user = null)
    {
        return AuthorizationEngine::canViewReport($reportKey, $user);
    }
}

if (!function_exists('auditLog')) {
    /**
     * Log permission-controlled audit action.
     */
    function auditLog($module, $action, $permissionKey = null, $record = null, $oldValues = null, $newValues = null)
    {
        AuditService::log($module, $action, $permissionKey, $record, $oldValues, $newValues);
    }
}
