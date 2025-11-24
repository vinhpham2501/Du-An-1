<?php

namespace App\Helpers;

class ImageHelper
{
    /**
     * Lấy đường dẫn hình ảnh đúng (URL hoặc local file)
     */
    public static function getImageSrc($imageUrl)
    {
        // Trim and basic validation
        if ($imageUrl === null) {
            return null;
        }
        $src = trim((string)$imageUrl);
        if ($src === '') {
            return null;
        }

        // Accept full/external URLs or special schemes
        // - http://, https://
        // - protocol-relative: //example.com/img.jpg
        // - data:, blob:
        if (preg_match('/^(https?:)?\/\//i', $src) || preg_match('/^(data:|blob:)/i', $src)) {
            return $src;
        }

        // If absolute path provided (starts with /), normalize any legacy /public/uploads or /public/images to new /images
        if (strpos($src, '/') === 0) {
            // Normalize legacy public path
            $src = preg_replace('#^/public/uploads/#', '/images/', $src);
            $src = preg_replace('#^/public/images/#', '/images/', $src);
            return $src;
        }

        // Normalize relative local paths
        $src = ltrim($src, './');
        // Convert legacy 'public/uploads/' or 'public/images/' to 'images/'
        if (stripos($src, 'public/uploads/') === 0) {
            $src = 'images/' . substr($src, strlen('public/uploads/'));
        } elseif (stripos($src, 'public/images/') === 0) {
            $src = substr($src, strlen('public/')); // becomes images/...
        }
        // Ensure it is served under /images/
        if (stripos($src, 'images/') === 0) {
            return '/' . $src;
        }

        // Assume it's a plain filename stored after uploading
        return '/images/' . $src;
    }
    
    /**
     * Tạo thẻ img với fallback
     */
    public static function renderImage($imageUrl, $alt = '', $class = '', $style = '')
    {
        $src = self::getImageSrc($imageUrl);
        
        if (!$src) {
            return '<div class="bg-light d-flex align-items-center justify-content-center ' . $class . '" style="' . $style . '">
                        <i class="fas fa-image text-muted"></i>
                    </div>';
        }
        
        return '<img src="' . htmlspecialchars($src) . '" 
                     alt="' . htmlspecialchars($alt) . '" 
                     class="' . $class . '" 
                     style="' . $style . '"
                     onerror="this.style.display=\'none\'">';
    }
}
