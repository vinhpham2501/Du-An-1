<?php

namespace App\Core;

class Controller
{
    protected $db;

    public function __construct()
    {
        $this->db = Database::getInstance();
    }

    protected function render($view, $data = [])
    {
        // Extract data to variables
        extract($data);
        
        // Determine layout
        $layout = 'app';
        if (strpos($view, 'admin/') === 0) {
            $layout = 'admin';
        }
        
        // Start output buffering
        ob_start();
        
        // Include the view
        $viewFile = APP_PATH . '/Views/' . $view . '.php';
        if (file_exists($viewFile)) {
            include $viewFile;
        } else {
            throw new \Exception("View {$view} not found");
        }
        
        // Get view content
        $content = ob_get_clean();
        
        // Include layout
        $layoutFile = APP_PATH . '/Views/layouts/' . $layout . '.php';
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
        header('Location: ' . $url);
        exit;
    }

    protected function requireAuth()
    {
        if (!isset($_SESSION['user_id'])) {
            $this->redirect('/login');
        }
    }

    protected function requireAdmin()
    {
        $this->requireAuth();
        if ($_SESSION['role'] !== 'admin') {
            http_response_code(403);
            $this->render('errors/403');
            exit;
        }
    }
}
