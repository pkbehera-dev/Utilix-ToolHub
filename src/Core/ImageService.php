<?php
namespace App\Core;

use Exception;

class ImageService {

    private const MAX_FILE_SIZE = 10 * 1024 * 1024; // 10 MB

    /**
     * Process an uploaded image securely.
     * Validates size, MIME type, resizes if needed, and strips metadata by re-saving.
     * 
     * @param array $file The $_FILES['input_name'] array
     * @param string $destination The absolute path to save the processed image
     * @param int $maxWidth Max width to resize to
     * @param int $maxHeight Max height to resize to
     * @throws Exception If validation or processing fails
     */
    public static function processUpload(array $file, string $destination, int $maxWidth = 1920, int $maxHeight = 1080): bool {
        
        if (!isset($file['error']) || is_array($file['error'])) {
            throw new Exception("Invalid parameters.");
        }

        if ($file['error'] !== UPLOAD_ERR_OK) {
            throw new Exception("Upload failed with error code: " . $file['error']);
        }

        if ($file['size'] > self::MAX_FILE_SIZE) {
            throw new Exception("File is too large. Maximum size is 10MB.");
        }

        // Validate MIME type strictly using finfo
        $finfo = new \finfo(FILEINFO_MIME_TYPE);
        $mime = $finfo->file($file['tmp_name']);
        
        $allowedMimeTypes = [
            'image/jpeg' => 'jpg',
            'image/png' => 'png',
            'image/webp' => 'webp',
            'image/gif' => 'gif'
        ];

        if (!array_key_exists($mime, $allowedMimeTypes)) {
            throw new Exception("Invalid file format. Only JPG, PNG, WEBP, and GIF are allowed.");
        }

        // Processing the image via GD strips EXIF metadata automatically when saved.
        return self::resizeAndStripMetadata($file['tmp_name'], $destination, $mime, $maxWidth, $maxHeight);
    }

    private static function resizeAndStripMetadata(string $sourcePath, string $destPath, string $mime, int $maxWidth, int $maxHeight): bool {
        
        // Load image based on MIME
        switch ($mime) {
            case 'image/jpeg':
                $sourceImage = @imagecreatefromjpeg($sourcePath);
                break;
            case 'image/png':
                $sourceImage = @imagecreatefrompng($sourcePath);
                break;
            case 'image/webp':
                $sourceImage = @imagecreatefromwebp($sourcePath);
                break;
            case 'image/gif':
                $sourceImage = @imagecreatefromgif($sourcePath);
                break;
            default:
                return false;
        }

        if (!$sourceImage) {
            throw new Exception("Failed to read image data.");
        }

        $width = imagesx($sourceImage);
        $height = imagesy($sourceImage);

        // Calculate new dimensions keeping aspect ratio
        if ($width > $maxWidth || $height > $maxHeight) {
            $ratio = min($maxWidth / $width, $maxHeight / $height);
            $newWidth = (int)($width * $ratio);
            $newHeight = (int)($height * $ratio);
        } else {
            $newWidth = $width;
            $newHeight = $height;
        }

        // Create a new true color image (strips metadata)
        $newImage = imagecreatetruecolor($newWidth, $newHeight);

        // Preserve transparency for PNG, WEBP and GIF
        if ($mime === 'image/png' || $mime === 'image/webp' || $mime === 'image/gif') {
            imagecolortransparent($newImage, imagecolorallocatealpha($newImage, 0, 0, 0, 127));
            imagealphablending($newImage, false);
            imagesavealpha($newImage, true);
        }

        // Resize and resample
        imagecopyresampled($newImage, $sourceImage, 0, 0, 0, 0, $newWidth, $newHeight, $width, $height);

        // Save image to destination
        $success = false;
        switch ($mime) {
            case 'image/jpeg':
                $success = imagejpeg($newImage, $destPath, 90);
                break;
            case 'image/png':
                $success = imagepng($newImage, $destPath, 9);
                break;
            case 'image/webp':
                $success = imagewebp($newImage, $destPath, 90);
                break;
            case 'image/gif':
                $success = imagegif($newImage, $destPath);
                break;
        }

        imagedestroy($sourceImage);
        imagedestroy($newImage);

        return $success;
    }
}
