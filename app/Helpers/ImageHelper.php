<?php

namespace App\Helpers;

class ImageHelper
{
    /**
     * Lấy đường dẫn hình ảnh đúng (URL hoặc local file)
     */
    public static function getImageSrc($imageUrl)
    {
        if (empty($imageUrl)) {
            return null;
        }
        
        // Nếu là URL (bắt đầu bằng http/https)
        if (strpos($imageUrl, 'http') === 0) {
            return $imageUrl;
        }
        
        // Nếu là file local
        return '/uploads/' . $imageUrl;
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
