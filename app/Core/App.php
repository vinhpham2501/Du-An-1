<?php

namespace App\Core;

class App
{
    private $config;
    private $router;
    private $database;

    public function __construct($config)
    {
        $this->config = $config;
        $this->router = new Router();
        $this->database = Database::getInstance($config['database']);
        
        // Set up routes
        $this->setupRoutes();
    }

    public function run()
    {
        $this->router->dispatch();
    }

    private function setupRoutes()
    {
        // Home routes
        $this->router->get('/', 'HomeController@index');
        $this->router->get('/product', 'HomeController@product');
        $this->router->get('/category', 'HomeController@category');
        $this->router->get('/search', 'HomeController@search');
        $this->router->get('/about', 'HomeController@about');
        $this->router->get('/contact', 'HomeController@contact');
        $this->router->post('/contact', 'HomeController@contact');
        $this->router->post('/add-review', 'HomeController@addReview');

        // Auth routes
        $this->router->get('/login', 'AuthController@login');
        $this->router->post('/login', 'AuthController@login');
        $this->router->get('/register', 'AuthController@register');
        $this->router->post('/register', 'AuthController@register');
        $this->router->get('/logout', 'AuthController@logout');
        $this->router->get('/profile', 'AuthController@profile');
        $this->router->post('/profile', 'AuthController@profile');
        $this->router->post('/change-password', 'AuthController@changePassword');

        // Cart routes
        $this->router->get('/cart', 'CartController@index');
        $this->router->post('/cart/add', 'CartController@add');
        $this->router->post('/cart/update', 'CartController@update');
        $this->router->post('/cart/remove', 'CartController@remove');
        $this->router->post('/cart/clear', 'CartController@clear');
        $this->router->get('/cart/count', 'CartController@getCount');

        // Order routes
        $this->router->get('/checkout', 'OrderController@checkout');
        $this->router->post('/checkout', 'OrderController@placeOrder');
        $this->router->get('/my-orders', 'OrderController@myOrders');
        $this->router->get('/orders/{id}', 'OrderController@orderDetail');
        $this->router->post('/orders/cancel', 'OrderController@cancelOrder');

        // Admin routes
        $this->router->get('/admin/dashboard', 'Admin\DashboardController@index');
        $this->router->get('/admin/statistics', 'Admin\DashboardController@statistics');
        
        $this->router->get('/admin/products', 'Admin\ProductController@index');
        $this->router->get('/admin/products/create', 'Admin\ProductController@create');
        $this->router->post('/admin/products/create', 'Admin\ProductController@create');
        $this->router->get('/admin/products/{id}/edit', 'Admin\ProductController@edit');
        $this->router->post('/admin/products/{id}/edit', 'Admin\ProductController@edit');
        $this->router->post('/admin/products/delete', 'Admin\ProductController@delete');
    }
}
