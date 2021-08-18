<?php


namespace App\Controllers;


use App\Core\Controller;
use App\Core\Request;
use App\Core\Response;
use App\Models\Category;
use App\Models\Product;

class MainController extends Controller
{
    public function index()
    {
        $products = (new Product())->all();

        $this->render('master', 'index', compact('products'));
    }

    public function categories()
    {
        $categories = (new Category())->all();

        $this->render('master', 'categories', compact('categories'));
    }

    public function category(Request $request, Response $response, $code)
    {
        $category = (new Category())->where('code', $code)->first();

        $this->render('master', 'category', compact('category'));
    }

    public function product(Request $request, Response $response, $categoryCode, $productCode)
    {
        $product = (new Product())->where('code', $productCode)->first();

        $this->render('master', 'product', compact('product'));
    }
}