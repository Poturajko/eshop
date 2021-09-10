<?php

namespace App\Controllers\Admin;

use App\Core\Base\BaseController;
use App\Core\Request;
use App\Core\Response;
use App\Core\Storage;
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

    public function store(Request $request, Response $response)
    {
        $product = new Product();

        if ($request->isPost()) {
            $params = $request->getBody();
            unset($params['image']);
            if ($request->has('image')) {
                $path = $request->file('image')->store('products');
                $params['image'] = $path;
            }
            if ($request->has(['hit', 'recommend', 'new'])) {
                foreach (['hit', 'recommend', 'new'] as $item) {
                    $params[$item] = $params[$item] ? 1 : 0;
                }
            }
            $product->getRepo()->save($params);
            $response->redirect('/admin/products');
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

    public function update(Request $request, Response $response, int $id)
    {
        $product = (new Product())->getRepo()->find($id);
        if ($request->isPost()) {
            $params = $request->getBody();
            unset($params['image']);
            if ($request->has('image')) {
                Storage::delete($product->image);
                $path = $request->file('image')->store('products');
                $params['image'] = $path;
            }
            if ($request->has(['hit', 'recommend', 'new'])) {
                foreach (['hit', 'recommend', 'new'] as $item) {
                    $params[$item] = $params[$item] ? 1 : 0;
                }
            }
            $product->getRepo()->update($id, $params);
            $response->redirect('/admin/products');
        }
    }

    public function destroy(Response $response, int $id)
    {
        (new Product())->getRepo()->findByIdAndDelete(['id' => $id]);

        $response->redirect('/admin/products');
    }
}