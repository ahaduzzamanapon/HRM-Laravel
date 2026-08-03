<?php
use Illuminate\Support\Facades\File;

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

        // Ensure the directory exists
        if (!File::exists($path)) {
            File::makeDirectory($path, 0775, true, true);
        }

        // Generate a unique file name if not provided
        $filename = $name
            ? $name . '.' . $file->getClientOriginalExtension()
            : time() . '_' . $file->getClientOriginalName();

        // Move the file to the desired folder
        $file->move($path, $filename);

        // Return the relative path
        return $folder . '/' . $filename;
    }
}

if (!function_exists('can')) {

    function can($key)
    {
        if (!auth()->check()) {
            return false;
        }

        static $permCache = [];
        $user = auth()->user();
        $userId = $user->id;

        if (!isset($permCache[$userId])) {
            $role = $user->role;
            if (!$role) {
                $permCache[$userId] = [];
            } elseif ($role->key === 'super_admin' || $role->name === 'Super Admin' || $role->permissions->contains('key', 'all_permissions')) {
                $permCache[$userId] = 'ALL';
            } else {
                $keys = [];
                $permissions = $role->permissions()->with('parent')->get();
                foreach ($permissions as $p) {
                    if ($p->key) {
                        $keys[$p->key] = true;
                    }
                    if ($p->parent && $p->parent->key) {
                        $keys[$p->parent->key] = true;
                    }
                }
                $permCache[$userId] = $keys;
            }
        }

        if ($permCache[$userId] === 'ALL') {
            return true;
        }

        return isset($permCache[$userId][$key]);
    }
}

if (!function_exists('isSuperAdmin')) {
    /**
     * Check if a user is a Super Admin.
     */
    function isSuperAdmin($user = null)
    {
        $user = $user ?: auth()->user();
        if (!$user) {
            return false;
        }
        $role = $user->role;
        if (!$role) {
            return false;
        }
        return $role->key === 'super_admin' || $role->name === 'Super Admin' || $role->permissions->contains('key', 'all_permissions');
    }
}

if (!function_exists('userBranchId')) {
    /**
     * Get assigned branch ID for the authenticated (or given) user.
     * Prioritizes explicit user branch_id, then role assigned branch_id.
     */
    function userBranchId($user = null)
    {
        static $userBranchCache = [];
        $user = $user ?: auth()->user();
        if (!$user) {
            return null;
        }
        $userId = $user->id;
        if (!isset($userBranchCache[$userId])) {
            if ($user->branch_id) {
                $userBranchCache[$userId] = (int) $user->branch_id;
            } elseif ($user->role && $user->role->branch_id) {
                $userBranchCache[$userId] = (int) $user->role->branch_id;
            } else {
                $userBranchCache[$userId] = null;
            }
        }
        return $userBranchCache[$userId];
    }
}

if (!function_exists('hasBranchAccess')) {
    /**
     * Check whether a user has access to a specific branch ID.
     */
    function hasBranchAccess($targetBranchId, $user = null)
    {
        if (isSuperAdmin($user)) {
            return true;
        }
        $bId = userBranchId($user);
        if (!$bId) {
            return true; // If user has no branch set, default allow or handled elsewhere
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
        if (!isSuperAdmin($user)) {
            $bId = userBranchId($user);
            if ($bId) {
                $query->where($column, $bId);
            }
        }
        return $query;
    }
}

if (!function_exists('applyUserBranchScope')) {
    /**
     * Apply branch scope through user relation to a query builder instance for non-superadmins.
     */
    function applyUserBranchScope($query, $userRelation = 'user', $user = null)
    {
        if (!isSuperAdmin($user)) {
            $bId = userBranchId($user);
            if ($bId) {
                $query->whereHas($userRelation, function ($q) use ($bId) {
                    $q->where('branch_id', $bId);
                });
            }
        }
        return $query;
    }
}

if (!function_exists('canManageBranch')) {
    /**
     * Returns true if the current user is a Super Admin OR belongs to the given branch.
     */
    function canManageBranch($branchId, $user = null)
    {
        if (isSuperAdmin($user)) {
            return true;
        }
        $myBranch = userBranchId($user);
        if (!$myBranch) {
            return true; // No branch restriction set on this user
        }
        return (int) $myBranch === (int) $branchId;
    }
}

if (!function_exists('enforceBranchOwnership')) {
    /**
     * Abort 403 if the current user does not have access to the given branch-owned record.
     * Accepts either a branch_id integer or a model instance with a branch_id or user.branch_id.
     */
    function enforceBranchOwnership($branchIdOrModel, $branchColumn = 'branch_id', $user = null)
    {
        if (isSuperAdmin($user)) {
            return;
        }
        if (is_object($branchIdOrModel)) {
            // Support both direct branch_id and nested user->branch_id
            if (isset($branchIdOrModel->$branchColumn)) {
                $targetBranchId = $branchIdOrModel->$branchColumn;
            } elseif (isset($branchIdOrModel->user) && isset($branchIdOrModel->user->branch_id)) {
                $targetBranchId = $branchIdOrModel->user->branch_id;
            } elseif (isset($branchIdOrModel->employee) && isset($branchIdOrModel->employee->branch_id)) {
                $targetBranchId = $branchIdOrModel->employee->branch_id;
            } else {
                return; // Cannot determine branch — allow (fail open, not fail closed for unknown structures)
            }
        } else {
            $targetBranchId = $branchIdOrModel;
        }

        if ($targetBranchId === null) {
            return; // Global record accessible by all
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
     * Useful for leave types, salary grades, etc. that can be global or branch-specific.
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
        if (function_exists('applyBranchScope')) {
            applyBranchScope($q, 'branch_id');
        }
        return $q->get()->mapWithKeys(function ($u) {
            $name = trim(($u->name ?? '') . ' ' . ($u->last_name ?? ''));
            if ($u->emp_id) {
                $name .= ' (ID: ' . $u->emp_id . ')';
            }
            return [$u->id => $name];
        });
    }
}
