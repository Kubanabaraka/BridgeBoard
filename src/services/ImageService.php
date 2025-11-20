<?php

declare(strict_types=1);

namespace BridgeBoard\Services;

use RuntimeException;

final class ImageService
{
    private const DEFAULT_DIR = '/public/assets/uploads';

    public static function handleUpload(array $file, ?string $directory = null): ?string
    {
        if (($file['error'] ?? UPLOAD_ERR_NO_FILE) === UPLOAD_ERR_NO_FILE) {
            return null;
        }

        self::assertNoUploadError($file['error']);

        $allowed = explode(',', env('ALLOWED_IMAGE_TYPES', 'jpg,jpeg,png,webp'));
        $extension = strtolower(pathinfo($file['name'], PATHINFO_EXTENSION));
        if (!in_array($extension, $allowed, true)) {
            throw new RuntimeException('Unsupported image type.');
        }

        $maxSize = (int) env('UPLOAD_MAX_SIZE', 5 * 1024 * 1024);
        if (($file['size'] ?? 0) > $maxSize) {
            throw new RuntimeException('Image exceeds the maximum allowed size.');
        }

        $targetDir = BASE_PATH . ($directory ?? self::DEFAULT_DIR);
        if (!is_dir($targetDir)) {
            mkdir($targetDir, 0775, true);
        }

        $filename = uniqid('img_', true) . '.' . $extension;
        $destination = $targetDir . '/' . $filename;

        if (!move_uploaded_file($file['tmp_name'], $destination)) {
            throw new RuntimeException('Unable to move uploaded file.');
        }

        $relative = str_replace('\\', '/', $destination);
        $base = str_replace('\\', '/', BASE_PATH . '/');

        return ltrim(str_replace($base, '', $relative), '/');
    }

    public static function handleMultiple(array $files): array
    {
        $paths = [];
        $count = is_array($files['name'] ?? null) ? count($files['name']) : 0;

        for ($i = 0; $i < $count; $i++) {
            $file = [
                'name' => $files['name'][$i],
                'type' => $files['type'][$i],
                'tmp_name' => $files['tmp_name'][$i],
                'error' => $files['error'][$i],
                'size' => $files['size'][$i],
            ];

            $stored = self::handleUpload($file);
            if ($stored) {
                $paths[] = $stored;
            }
        }

        return $paths;
    }

    private static function assertNoUploadError(int $error): void
    {
        $messages = [
            UPLOAD_ERR_OK => 'Upload successful.',
            UPLOAD_ERR_INI_SIZE => 'The uploaded file exceeds the server limit.',
            UPLOAD_ERR_FORM_SIZE => 'The uploaded file exceeds the form limit.',
            UPLOAD_ERR_PARTIAL => 'The uploaded file was only partially uploaded.',
            UPLOAD_ERR_NO_FILE => 'No file was uploaded.',
            UPLOAD_ERR_NO_TMP_DIR => 'Missing a temporary folder.',
            UPLOAD_ERR_CANT_WRITE => 'Failed to write file to disk.',
            UPLOAD_ERR_EXTENSION => 'A PHP extension stopped the file upload.',
        ];

        if ($error !== UPLOAD_ERR_OK && $error !== UPLOAD_ERR_NO_FILE) {
            throw new RuntimeException($messages[$error] ?? 'Unknown upload error.');
        }
    }
}
