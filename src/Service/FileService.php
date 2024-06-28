<?php declare(strict_types=1);

namespace App\Service;

class FileService
{
    public static function getFileNames(): array
    {
        $files = scandir(self::getUploaddedPath());
        $files = array_diff($files, ['.', '..']);
        return $files;
    }

    public static function getUploaddedPath(): string
    {
        return $_ENV["UPLOAD_DIRECTORY"] ?? "upload";
    }
}