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
        if ($role->permissions->contains('key', 'all_permissions')) {
            Log::info("CAN: Role has 'all_permissions' for key: {$key}");
            return true;
        }
        if ($role->permissions->contains('key', $key)) {
            Log::info("CAN: Role has direct permission for key: {$key}");
            return true;
        }else{
            return false;
        }
    }
}

