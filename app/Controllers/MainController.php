<?php


namespace App\Controllers;

use App\Core\Base\BaseController;
use App\Core\Request\Request;
use App\Core\Utility\Pagination;
use App\Models\Category;
use App\Models\Product;

class MainController extends BaseController
{
    public function index(Request $request)
    {
        $where = [];
        if ($request->has('price_from')) {
            $where['price']['>='] = $request->getBody()['price_from'];
        }
        if ($request->has('price_to')) {
            $where['price']['<='] = $request->getBody()['price_to'];
        }

        foreach (['hit', 'new', 'recommend'] as $field) {
            if ($request->has($field)) {
                $where[$field]['='] = 1;
            }
        }

        $product = new Product();
        $products = $product->getRepo()->findWithPaging($request, $product::SHOW_BY_DEFAULT, $where);
        $paginate = new Pagination($request, $product->getRepo()->count(), $product::SHOW_BY_DEFAULT);

        $this->render('master', 'index', compact('products', 'paginate'));
    }

    public function categories()
    {
        $categories = (new Category())->getRepo()->findAll();

        $this->render('master', 'categories', compact('categories'));
    }

    public function category(string $code)
    {
        $category = (new Category())->getRepo()->findOneBy(['code' => $code]);

        $this->render('master', 'category', compact('category'));
    }

    public function product($categoryCode, string $productCode)
    {
        $product = (new Product())->getRepo()->findOneBy(['code' => $productCode]);

        $this->render('master', 'product', compact('product'));
    }

}