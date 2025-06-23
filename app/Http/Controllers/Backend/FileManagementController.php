<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class FileManagementController extends Controller
{
    
    public function contentImageUpload(Request $request): JsonResponse
    {
        $request->validate([
            'upload' => 'required|image|mimes:jpeg,png,jpg,gif',
        ]);
        if ($request->hasFile('upload')) {
            $file = $request->file('upload');
            $filename = $file->getClientOriginalName();
            $folder = uniqid();
            $file->storeAs('content_image/' . $folder, $filename, 'public');
            $path = "content_image/" . $folder;
            return response()->json([
                'success' => 'File upload successfully',
                'url' => asset('storage/' . $path . '/' . $filename)
            ]);
        }
        return response()->json(['error' => 'File upload failed'], 400);
    }
}
