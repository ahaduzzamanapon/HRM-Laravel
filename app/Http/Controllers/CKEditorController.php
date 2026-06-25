<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class CKEditorController extends Controller
{
    public function upload(Request $request)
    {
        if ($request->hasFile('upload')) {
            $originName = $request->file('upload')->getClientOriginalName();
            $fileName = pathinfo($originName, PATHINFO_FILENAME);
            $extension = $request->file('upload')->getClientOriginalExtension();
            // Sanitize filename
            $fileName = preg_replace('/[^A-Za-z0-9\-]/', '', $fileName) . '_' . time() . '.' . $extension;

            $request->file('upload')->move(public_path('uploads/ckeditor'), $fileName);

            $url = asset('uploads/ckeditor/' . $fileName);

            return response()->json([
                'fileName' => $fileName,
                'uploaded' => 1,
                'url' => $url
            ]);
        }

        return response()->json([
            'uploaded' => 0,
            'error' => ['message' => 'No file uploaded.']
        ]);
    }
}
