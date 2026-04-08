<?php

namespace App\Services;

use Exception;
use Illuminate\Http\UploadedFile;

class FileUploadService
{
    /**
     * @param UploadedFile $file
     * @param string $disk
     * @param string $folder
     * @param array $allowedMimes
     * @return string Path to the stored file
     * @throws Exception
     */
    public function validateAndStore(
        UploadedFile $file,
        string $disk,
        string $folder,
        array $allowedMimes = []
    ): string {
        // Verify MIME type server-side natively using fileinfo
        $mimeType = $file->getMimeType();
        if (!empty($allowedMimes) && !in_array($mimeType, $allowedMimes)) {
            throw new Exception('Invalid file type: ' . $mimeType);
        }
        
        // Block explicitly dangerous extensions, even if MIME type is faked
        $dangerous = ['php', 'exe', 'sh', 'bat', 'cmd', 'js', 'py', 'rb'];
        $ext = strtolower($file->getClientOriginalExtension());
        if (in_array($ext, $dangerous)) {
            throw new Exception('File type not allowed due to security policies.');
        }
        
        // Use Laravel's store(), which generates a secure random hash name
        $path = $file->store($folder, $disk);
        if (!$path) {
            throw new Exception('Failed to store file securely.');
        }
        
        return $path;
    }
}
