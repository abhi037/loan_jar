<?php

namespace App\Traits;

use Illuminate\Support\Facades\Storage;

trait FileUploadTrait
{
    public function uploadFile($file, $folder)
    {
        return $file->store($folder, 'public');
    }

    public function uploadOrReplaceFile($file, $folder, $existingFile = null)
    {
        if ($existingFile && Storage::disk('public')->exists($existingFile)) {
            Storage::disk('public')->delete($existingFile);
        }

        return $file->store($folder, 'public');
    }
}
