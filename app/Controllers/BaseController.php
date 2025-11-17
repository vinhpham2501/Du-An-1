<?php

namespace App\Controllers;

class BaseController
{
    protected function render($view, $data = [])
    {
        // Extract data to variables
        extract($data);
        
        // Start output buffering
        ob_start();
        
        // Include the view file
        $viewFile = APP_PATH . '/Views/' . $view . '.php';
        
        if (!file_exists($viewFile)) {
            throw new \Exception("View file not found: {$viewFile}");
        }
        
        include $viewFile;
        
        // Get the content
        $content = ob_get_clean();
        
        // Include layout if exists
        $layoutFile = APP_PATH . '/Views/layouts/app.php';
        if (file_exists($layoutFile)) {
            include $layoutFile;
        } else {
            echo $content;
        }
    }
    
    protected function json($data)
    {
        header('Content-Type: application/json');
        echo json_encode($data);
        exit;
    }
    
    protected function redirect($url)
    {
        header("Location: {$url}");
        exit;
    }
}
