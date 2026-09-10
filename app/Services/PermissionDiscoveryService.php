<?php

namespace App\Services;

use App\Models\Permission;

class PermissionDiscoveryService
{
    /**
     * Get structured permission tree grouped by top-level Module -> Menu -> Child Permissions.
     *
     * @return \Illuminate\Support\Collection
     */
    public static function getPermissionTree()
    {
        return Permission::with(['children.children'])
            ->whereNull('parent_id')
            ->orderBy('name', 'asc')
            ->get();
    }

    /**
     * Get permission matrix structure for role details and assignment views.
     * Groups permissions into rows representing individual menus/resources
     * and maps CRUD actions (View, Add, Edit, Delete, Export, Manage/Other).
     *
     * @return array
     */
    public static function getPermissionMatrix()
    {
        $rootPermissions = Permission::with(['children.children'])->whereNull('parent_id')->orderBy('name', 'asc')->get();
        $matrix = [];

        foreach ($rootPermissions as $module) {
            $moduleName = $module->name;

            // Direct children of module can be menus or individual permissions
            foreach ($module->children as $menu) {
                $menuName = $menu->name;
                $actions = [
                    'view' => null,
                    'create' => null,
                    'edit' => null,
                    'delete' => null,
                    'export' => null,
                    'other' => []
                ];

                // Check menu itself
                static::categorizePermission($menu, $actions);

                // Check sub-permissions under menu
                if ($menu->children && $menu->children->count() > 0) {
                    foreach ($menu->children as $child) {
                        static::categorizePermission($child, $actions);
                    }
                }

                $matrix[] = [
                    'module' => $moduleName,
                    'menu' => $menuName,
                    'menu_permission' => $menu,
                    'actions' => $actions
                ];
            }
        }

        return $matrix;
    }

    /**
     * Categorize a permission object into standard matrix action buckets.
     *
     * @param Permission $perm
     * @param array &$actions
     */
    protected static function categorizePermission($perm, &$actions)
    {
        $key = strtolower($perm->key);
        $name = strtolower($perm->name);

        if (str_contains($key, 'view') || str_contains($name, 'view') || str_contains($key, 'list') || str_contains($name, 'list')) {
            $actions['view'] = $perm;
        } elseif (str_contains($key, 'create') || str_contains($name, 'add') || str_contains($key, 'add') || str_contains($name, 'create')) {
            $actions['create'] = $perm;
        } elseif (str_contains($key, 'edit') || str_contains($name, 'edit') || str_contains($key, 'update') || str_contains($name, 'update')) {
            $actions['edit'] = $perm;
        } elseif (str_contains($key, 'delete') || str_contains($name, 'delete') || str_contains($key, 'destroy') || str_contains($name, 'destroy')) {
            $actions['delete'] = $perm;
        } elseif (str_contains($key, 'export') || str_contains($name, 'export') || str_contains($key, 'download') || str_contains($name, 'download') || str_contains($key, 'print')) {
            $actions['export'] = $perm;
        } else {
            $actions['other'][] = $perm;
        }
    }
}
