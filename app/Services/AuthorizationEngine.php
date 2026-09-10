<?php

namespace App\Services;

use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Database\Eloquent\Builder;

class AuthorizationEngine
{
    protected static $permCache = [];
    protected static $branchCache = [];

    /**
     * Clear authorization engine memory cache.
     */
    public static function clearCache()
    {
        static::$permCache = [];
        static::$branchCache = [];
    }

    /**
     * Determine if a user is a Super Admin.
     */
    public static function isSuperAdmin($user = null): bool
    {
        $user = $user ?: Auth::user();
        if (!$user) {
            return false;
        }

        $role = $user->role;
        if (!$role) {
            return false;
        }

        $roleKey = strtolower($role->key ?? '');
        $roleName = strtolower($role->name ?? '');

        if ($roleKey === 'super_admin' || $roleKey === 'superadmin' || $roleName === 'super admin' || $roleName === 'superadmin') {
            return true;
        }

        if ($role->permissions && $role->permissions->contains('key', 'all_permissions')) {
            return true;
        }

        return false;
    }

    /**
     * Determine if a user is in an HR role.
     */
    public static function isHRRole($user = null): bool
    {
        $user = $user ?: Auth::user();
        if (!$user) {
            return false;
        }

        $role = $user->role;
        if (!$role) {
            return false;
        }

        $roleKey = strtolower($role->key ?? '');
        $roleName = strtolower($role->name ?? '');

        return in_array($roleKey, ['hr', 'hr_manager', 'hrmanager']) || in_array($roleName, ['hr', 'hr manager', 'hrmanager']);
    }

    /**
     * Determine if a user is in an Employee / Non-Administrative role.
     */
    public static function isEmployeeRole($user = null): bool
    {
        $user = $user ?: Auth::user();
        if (!$user || static::isSuperAdmin($user)) {
            return false;
        }

        if ((isset($user->role_id) && (int)$user->role_id === 3) || (isset($user->group_id) && (int)$user->group_id === 3)) {
            return true;
        }

        $role = $user->role;
        $roleName = strtolower($role->name ?? $role->key ?? '');

        if ($roleName === 'employee') {
            return true;
        }

        // If user has management permissions, treat as administrative role
        if (static::can('manage_staff', $user) || 
            static::can('manage_departmental_cases', $user) || 
            static::can('manage_site_settings', $user) ||
            in_array($roleName, ['admin', 'super admin', 'hr', 'hr manager', 'branch admin', 'branch manager'])) {
            return false;
        }

        return true;
    }

    /**
     * Get assigned branch ID for user.
     */
    public static function getUserBranchId($user = null): ?int
    {
        $user = $user ?: Auth::user();
        if (!$user) {
            return null;
        }

        $userId = $user->id;
        if (!isset(static::$branchCache[$userId])) {
            if ($user->branch_id) {
                static::$branchCache[$userId] = (int) $user->branch_id;
            } elseif ($user->role && $user->role->branch_id) {
                static::$branchCache[$userId] = (int) $user->role->branch_id;
            } else {
                static::$branchCache[$userId] = null;
            }
        }

        return static::$branchCache[$userId];
    }

    /**
     * Dynamic Permission Check with recursive parent-child resolution.
     */
    public static function can($key, $user = null): bool
    {
        $user = $user ?: Auth::user();
        if (!$user) {
            return false;
        }

        if (is_string($key) && str_contains($key, '|')) {
            foreach (explode('|', $key) as $k) {
                if (static::can(trim($k), $user)) {
                    return true;
                }
            }
            return false;
        }

        $userId = $user->id;
        if (!isset(static::$permCache[$userId])) {
            $role = $user->role;
            if (!$role) {
                static::$permCache[$userId] = [];
            } else {
                $keys = [];
                $assignedPermissions = $role->permissions()->with(['parent', 'children.children'])->get();

                foreach ($assignedPermissions as $p) {
                    if ($p->key) {
                        $keys[$p->key] = true;
                    }

                    // 1. Bubble up ancestors so parent menus unlock if child is granted
                    $curr = $p;
                    while ($curr && $curr->parent_id) {
                        $parent = $curr->parent ?: \App\Models\Permission::find($curr->parent_id);
                        if ($parent && $parent->key) {
                            $keys[$parent->key] = true;
                        }
                        $curr = $parent;
                    }
                }

                static::$permCache[$userId] = $keys;
            }
        }

        // Allow all permissions if role explicitly has 'all_permissions' assigned or user is Super Admin
        if (static::isSuperAdmin($user) || isset(static::$permCache[$userId]['all_permissions'])) {
            return true;
        }

        return isset(static::$permCache[$userId][$key]);
    }

    /**
     * Helper to recursively collect child permission keys.
     */
    protected static function addDescendantKeys($permission, array &$keys)
    {
        if (!$permission || !$permission->children) {
            return;
        }

        foreach ($permission->children as $child) {
            if ($child->key) {
                $keys[$child->key] = true;
            }
            if ($child->children && $child->children->count() > 0) {
                static::addDescendantKeys($child, $keys);
            }
        }
    }

    /**
     * Evaluate action-level permission and ownership for a record.
     */
    public static function canPerformAction($action, $permissionKey = null, $record = null, $user = null): bool
    {
        $user = $user ?: Auth::user();
        if (!$user) {
            return false;
        }

        if ($permissionKey && !static::can($permissionKey, $user)) {
            return false;
        }

        $action = strtolower($action);

        if ($record && is_object($record) && !static::isSuperAdmin($user) && !static::isHRRole($user)) {
            // Branch access check for Admin / Branch Admin
            $targetBranchId = null;
            if (isset($record->branch_id)) {
                $targetBranchId = $record->branch_id;
            } elseif (isset($record->user->branch_id)) {
                $targetBranchId = $record->user->branch_id;
            } elseif (isset($record->employee->branch_id)) {
                $targetBranchId = $record->employee->branch_id;
            }

            $userBranchId = static::getUserBranchId($user);
            if ($targetBranchId !== null && $userBranchId !== null && (int)$targetBranchId !== (int)$userBranchId) {
                return false;
            }

            // Employee ownership check
            if (static::isEmployeeRole($user)) {
                $ownerId = null;
                if (isset($record->user_id)) {
                    $ownerId = $record->user_id;
                } elseif (isset($record->employee_id)) {
                    $ownerId = $record->employee_id;
                } elseif ($record instanceof User) {
                    $ownerId = $record->id;
                }

                if (in_array($action, ['edit', 'update', 'delete', 'destroy', 'approve', 'reject', 'modify', 'process', 'disburse'])) {
                    return false; // Employees cannot modify or approve records
                }

                if ($ownerId !== null && (int)$ownerId !== (int)$user->id) {
                    return false; // Cannot view or access other employee records
                }
            }
        }

        return true;
    }

    /**
     * Centralized Dynamic Query Scoping Engine.
     */
    public static function applyScope($query, $user = null)
    {
        $user = $user ?: Auth::user();

        // Super Admin and HR roles access data across ALL branches
        if (!$user || static::isSuperAdmin($user) || static::isHRRole($user)) {
            return $query;
        }

        $model = $query->getModel();
        $table = $model->getTable();

        // 1. Employee ownership scoping for regular employee roles
        if (static::isEmployeeRole($user)) {
            if ($table === 'users') {
                return $query->where('id', $user->id);
            }

            if (\Illuminate\Support\Facades\Schema::hasColumn($table, 'employee_id')) {
                return $query->where('employee_id', $user->id);
            }

            if (\Illuminate\Support\Facades\Schema::hasColumn($table, 'user_id')) {
                return $query->where('user_id', $user->id);
            }

            if (method_exists($model, 'employee')) {
                return $query->whereHas('employee', fn($q) => $q->where('id', $user->id));
            }

            if (method_exists($model, 'user')) {
                return $query->whereHas('user', fn($q) => $q->where('id', $user->id));
            }
        }

        // 2. Branch scoping for non-superadmins
        $bId = static::getUserBranchId($user);
        if ($bId) {
            if (\Illuminate\Support\Facades\Schema::hasColumn($table, 'branch_id')) {
                return $query->where(function($q) use ($bId) {
                    $q->where('branch_id', $bId)->orWhereNull('branch_id');
                });
            }

            if (method_exists($model, 'employee')) {
                return $query->whereHas('employee', fn($q) => $q->where('branch_id', $bId));
            }

            if (method_exists($model, 'user')) {
                return $query->whereHas('user', fn($q) => $q->where('branch_id', $bId));
            }
        }

        return $query;
    }

    /**
     * Menu Authorization Check.
     */
    public static function canViewMenu($moduleKey, $user = null): bool
    {
        $user = $user ?: Auth::user();
        if (!$user) {
            return false;
        }

        if (static::isSuperAdmin($user)) {
            return true;
        }

        // Check if user has permission for the module key itself or any sub-permission
        return static::can($moduleKey, $user);
    }

    /**
     * Dashboard Widget Authorization Check.
     */
    public static function canViewWidget($widgetKey, $user = null): bool
    {
        return static::can($widgetKey, $user);
    }

    /**
     * Report Authorization Check.
     */
    public static function canViewReport($reportKey, $user = null): bool
    {
        return static::can($reportKey, $user);
    }
}
