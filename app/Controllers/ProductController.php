<?php

namespace App\Controllers;

use App\Models\Product;
use App\Models\Category;
use App\Models\Review;

class ProductController extends BaseController
{
    private $productModel;
    private $categoryModel;
    private $reviewModel;

    public function __construct()
    {
        $this->productModel = new Product();
        $this->categoryModel = new Category();
        $this->reviewModel = new Review();
    }

    public function detail($id)
    {
        // Get product details
        $product = $this->productModel->findById($id);
        
        if (!$product) {
            header('HTTP/1.0 404 Not Found');
            return $this->render('errors/404');
        }

        // Get rating summary
        $ratingSummary = $this->reviewModel->getAverageRating($id);
        $product['avg_rating'] = $ratingSummary['avg_rating'] ?? 0;
        $product['total_reviews'] = $ratingSummary['total_reviews'] ?? 0;

        // Get reviews list
        $reviews = $this->reviewModel->findByProductId($id, 10);

        // Get related products from same category
        $relatedProducts = $this->productModel->getAll([
            'category_id' => $product['category_id'],
            'limit' => 4,
            'exclude_id' => $id
        ]);

        // Get all categories for breadcrumb
        $categories = $this->categoryModel->getAll();

        return $this->render('product/detail', [
            'product' => $product,
            'relatedProducts' => $relatedProducts,
            'categories' => $categories,
            'reviews' => $reviews,
        ]);
    }
}
