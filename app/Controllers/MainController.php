<?php


namespace App\Controllers;


use App\Core\Application;
use App\Core\Controller;
use App\Core\Exception\NotFoundException;
use App\Core\Request;
use App\Core\Response;
use App\Models\Category;
use App\Models\Product;

class MainController extends Controller
{
    public function index()
    {
        $products = (new Product())->all();

        $this->render($this->layout, 'index', compact('products'));
    }

    public function categories()
    {
        $categories = (new Category())->all();

        $this->render($this->layout, 'categories', compact('categories'));
    }

    public function category(Request $request,Response $response, $code)
    {
        $category = (new Category())->where('code',$code)->first();

        $this->render($this->layout, 'category', compact('category'));
    }

    public function product($category, $product = null)
    {
        //
    }
}