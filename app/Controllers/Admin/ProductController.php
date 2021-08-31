<?php

namespace App\Controllers\Admin;

use App\Core\Controller;
use App\Core\Request;
use App\Core\Response;
use App\Core\Storage;
use App\Models\Category;
use App\Models\Product;

class ProductController extends Controller
{
    public function index()
    {
        $products = (new Product())->all();

        $this->render('auth', 'auth/products/index', compact('products'));
    }

    public function create()
    {
        $categories = (new Category())->all();

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
            if ($request->has(['hit','recommend', 'new'])){
                foreach (['hit','recommend', 'new'] as $item) {
                    $params[$item] =  $params[$item] ? 1 : 0;
                }
            }
            $product->create($params);
            $response->redirect('/admin/products');
        }
    }

    public function show(Request $request, Response $response, int $id)
    {
        $product = (new Product())->where('id', $id)->first();

        $this->render('auth', 'auth/products/show', compact('product'));
    }

    public function edit(Request $request, Response $response, int $id)
    {
        $categories = (new Category())->all();
        $product = (new Product())->where('id', $id)->first();

        $this->render('auth', 'auth/products/form', compact('categories','product'));

    }

    public function update(Request $request, Response $response, int $id)
    {
        if ($request->isPost()) {
            $product = (new Product())->where('id', $id)->first();
            $params = $request->getBody();
            unset($params['image']);
            if ($product->validate()) {
                if ($request->has('image')) {
                    Storage::delete($product->image);
                    $path = $request->file('image')->store('products');
                    $params['image'] = $path;
                }
                if ($request->has(['hit','recommend', 'new'])){
                    foreach (['hit','recommend', 'new'] as $item) {
                        $params[$item] =  $params[$item] ? 1 : 0;
                    }
                }
                $product->update($params);
                $response->redirect('/admin/products');
            }
        }
    }

    public function destroy(Request $request, Response $response, int $id)
    {
        if ($request->isPost()){
            $product = (new Product())->where('id', $id)->first();
            $product->delete();
        }

        $response->redirect('/admin/products');
    }
}