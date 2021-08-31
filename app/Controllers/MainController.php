<?php


namespace App\Controllers;


use App\Core\Controller;
use App\Core\Database\DatabaseModel;
use App\Core\Model;
use App\Core\Pagination;
use App\Core\Request;
use App\Core\Response;
use App\Models\Category;
use App\Models\Product;

class MainController extends Controller
{
    public function index(Request $request)
    {
        $product = new Product();
        $products = $product->paginate();
        $pagination = new Pagination($product->count(),DatabaseModel::SHOW_BY_DEFAULT);

        $this->render('master', 'index', compact('products', 'pagination'));
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