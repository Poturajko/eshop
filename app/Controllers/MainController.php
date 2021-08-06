<?php


namespace App\Controllers;


use App\Controller;
use App\Models\Category;
use App\Models\Product;

class MainController extends Controller
{
    public function index()
    {
        $products = (new Product())->getProducts();

        $this->view->render('Главная', compact('products'));
    }

    public function categories()
    {
        $categories = (new Category())->getCategories();

        $this->view->render('Категории', compact('categories'));
    }

    public function category(string $code)
    {
        $category = (new Category())->getCategoryByCode($code);
        $products = (new Product())->getProductsByCategoryId($category['id']);

        $this->view->render('Категория', compact('category', 'products'));
    }

    public function product($category, $product = null)
    {
        $this->view->render('Товар');
    }
}