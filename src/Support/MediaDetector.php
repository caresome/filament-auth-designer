<?php

namespace Caresome\FilamentAuthDesigner\Support;

class MediaDetector
{
    protected const VIDEO_EXTENSIONS = ['mp4', 'webm', 'mov', 'ogg', 'avi', 'mkv'];

    protected const IMAGE_EXTENSIONS = ['jpg', 'jpeg', 'png', 'gif', 'webp', 'svg', 'avif'];

    public function isVideo(string $path): bool
    {
        $extension = $this->getExtension($path);

        return in_array($extension, self::VIDEO_EXTENSIONS, true);
    }

    public function isImage(string $path): bool
    {
        $extension = $this->getExtension($path);

        return in_array($extension, self::IMAGE_EXTENSIONS, true);
    }

    public function getExtension(string $path): string
    {
        $path = strtok($path, '?');
        $path = strtok($path, '#');

        return strtolower(pathinfo($path, PATHINFO_EXTENSION));
    }

    public function getMimeType(string $path): string
    {
        $extension = $this->getExtension($path);

        return match ($extension) {
            'mp4' => 'video/mp4',
            'webm' => 'video/webm',
            'mov' => 'video/quicktime',
            'ogg' => 'video/ogg',
            'avi' => 'video/x-msvideo',
            'mkv' => 'video/x-matroska',
            'jpg', 'jpeg' => 'image/jpeg',
            'png' => 'image/png',
            'gif' => 'image/gif',
            'webp' => 'image/webp',
            'svg' => 'image/svg+xml',
            'avif' => 'image/avif',
            default => 'application/octet-stream',
        };
    }
}
