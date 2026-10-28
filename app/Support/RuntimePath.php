<?php

namespace App\Support;

class RuntimePath
{
    public static function writable(string $preferred, string $name): string
    {
        if (self::directoryUsable($preferred)) {
            return $preferred;
        }

        $fallback = rtrim(sys_get_temp_dir(), '\\/').DIRECTORY_SEPARATOR.'web-tagihan-sukarame'.DIRECTORY_SEPARATOR.$name;
        if (!is_dir($fallback)) {
            @mkdir($fallback, 0777, true);
        }

        return $fallback;
    }

    public static function logChannel(): string
    {
        $dir = storage_path('logs');
        $file = $dir.DIRECTORY_SEPARATOR.'laravel.log';
        $fileOk = !file_exists($file) || is_writable($file);

        return self::directoryUsable($dir) && $fileOk ? 'single' : 'errorlog';
    }

    public static function emergencyLog(): string
    {
        $file = storage_path('logs/laravel.log');
        if (self::logChannel() === 'single') {
            return $file;
        }

        return rtrim(sys_get_temp_dir(), '\\/').DIRECTORY_SEPARATOR.'web-tagihan-sukarame-laravel.log';
    }

    private static function directoryUsable(string $path): bool
    {
        return is_dir($path) && is_writable($path);
    }
}
