<?php

namespace App\Controllers\Admin;

use App\Core\Base\BaseController;
use App\Core\Request\Request;
use App\Core\Response\Response;
use App\Core\Storage\File;
use App\Models\Category;
use App\Models\Product;

class ProductController extends BaseController
{
    public function index()
    {
        $products = (new Product())->getRepo()->findAll();

        $this->render('auth', 'auth/products/index', compact('products'));
    }

    public function create()
    {
        $categories = (new Category())->getRepo()->findAll();

        $this->render('auth', 'auth/products/form', compact('categories'));
    }

    public function store(Request $request)
    {
        $product = new Product();

        if ($request->isPost()) {
            $params = $request->getBody();
            unset($params['image']);
            if ($request->has('image')) {
                $file = new File($request->file('image'));
                $path = $file->save('products');
                $params['image'] = $path;
            }
            if ($request->has(['recommend', 'hit', 'new'])) {
                foreach (['recommend', 'hit', 'new'] as $item) {
                    $params[$item] = $params[$item] ? 1 : 0;
                }
            }
            $product->getRepo()->save($params);
            redirect('/admin/products');
        }
    }

    public function show(int $id)
    {
        $product = (new Product())->getRepo()->find($id);

        $this->render('auth', 'auth/products/show', compact('product'));
    }

    public function edit(int $id)
    {
        $categories = (new Category())->getRepo()->findAll();
        $product = (new Product())->getRepo()->find($id);

        $this->render('auth', 'auth/products/form', compact('categories', 'product'));

    }

    public function update(Request $request, int $id)
    {
        $product = (new Product())->getRepo()->find($id);
        if ($request->isPost()) {
            $params = $request->getBody();
            unset($params['image']);
            if ($request->has('image')) {
                $file = new File($request->file('image'));
                if (!is_null($product->image)){
                    $file->delete($product->image);
                }
                $path = $file->save('products');
                $params['image'] = $path;
            }
            if ($request->has(['recommend', 'hit', 'new'])) {
                foreach (['recommend', 'hit', 'new'] as $item) {
                    $params[$item] = $params[$item] ? 1 : 0;
                }
            }
            $product->getRepo()->update($id, $params);
            redirect('/admin/products');
        }
    }

    public function destroy(int $id)
    {
        $product = (new Product())->getRepo()->find($id);
        if (!is_null($product->image)) {
            (new File())->delete($product->image);
        }
        $product->getRepo()->delete($id);

        redirect('/admin/products');
    }
}