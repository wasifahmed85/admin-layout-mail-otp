<?php

namespace App\Http\Traits;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

trait FileManagementTrait
{
    /**
     * Handle image upload for any model.
     *
     * @param \Illuminate\Http\Request $request
     * @param mixed $model The model to attach the image to
     * @param string $imageField The name of the image field in the form
     * @param string $folderName The folder to store the image
     * @return void
     */
    public function handleFileUpload($file, $folderName = 'uploads', $fileName = false): string
    {
        $file_name = Str::slug($fileName) ?? Str::slug($file->getClientOriginalName() . rand(1000, 9999));
        $fileName = $file_name . '_' . time() . rand(1000, 9999) . '.' . $file->getClientOriginalExtension();
        $path = $file->storeAs($folderName, $fileName, 'public');
        return $path;
    }
    public function fileDelete($path)
    {
        if ($path) {
            Storage::disk('public')->delete($path);
        }
    }
}
