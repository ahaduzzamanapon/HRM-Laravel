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

        $user = auth()->user();
        $role = $user->role;

        if (!$role) {
            return false;
        }

        // If the role has all permissions (e.g., Admin)
        if ($role->permissions->contains('key', 'all_permissions')) { // Assuming 'all_permissions' is a key for a super admin
            return true;
        }

        // Check if the role has the permission directly
        if ($role->permissions->contains('key', $key)) {
            return true;
        }

        // Check if the key is a parent permission and the role has any of its children
        $parentPermission = \App\Models\Permission::where('key', $key)->first();
        if ($parentPermission && $parentPermission->children->count() > 0) {
            foreach ($parentPermission->children as $child) {
                if ($role->permissions->contains('key', $child->key)) {
                    return true;
                }
            }
        }

        // If the key is a child permission, check if its parent is granted
        $childPermission = \App\Models\Permission::where('key', $key)->first();
        if ($childPermission && $childPermission->parent_id) {
            $grandparentPermission = \App\Models\Permission::find($childPermission->parent_id);
            if ($grandparentPermission && $role->permissions->contains('key', $grandparentPermission->key)) {
                return true;
            }
        }

        return false;
    }
}

