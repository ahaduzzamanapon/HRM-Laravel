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
            Log::info("CAN: Role has 'all_permissions' for key: {$key}");
            return true;
        }


        //dd($role->permissions->pluck('key')->toArray());

        // Check if the role has the permission directly
        if ($role->permissions->contains('key', $key)) {
            Log::info("CAN: Role has direct permission for key: {$key}");
            return true;
        }else{
            return false;
        }
       

        // Check if the key is a parent permission and the role has any of its children
        $parentPermission = \App\Models\Permission::where('key', $key)->first();

       // dd($parentPermission);




        if ($parentPermission && $parentPermission->children->count() > 0) {
            Log::info("CAN: Key '{$key}' is a parent permission. Checking children.");
            foreach ($parentPermission->children as $child) {
                if ($role->permissions->contains('key', $child->key)) {
                    Log::info("CAN: Role has child permission '{$child->key}' for parent key: {$key}");
                    return true;
                }
            }
        }

        // If the key is a child permission, check if its parent is granted
        $childPermission = \App\Models\Permission::where('key', $key)->first();
        if ($childPermission && $childPermission->parent_id) {
            $grandparentPermission = \App\Models\Permission::find($childPermission->parent_id);
            if ($grandparentPermission && $role->permissions->contains('key', $grandparentPermission->key)) {
                Log::info("CAN: Key '{$key}' is a child permission. Parent '{$grandparentPermission->key}' is granted.");
                return true;
            }
        }

        Log::info("CAN: No permission found for key: {$key}");
        return false;
    }
}

