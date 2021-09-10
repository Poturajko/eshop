<?php

namespace App\Controllers\Admin;

use App\Core\Base\BaseController;
use App\Core\Request;
use App\Core\Response;
use App\Core\Storage;
use App\Models\Category;

class CategoryController extends BaseController
{
    public function index()
    {
        $categories = (new Category())->getRepo()->findAll();

        $this->render('auth', 'auth/categories/index', compact('categories'));
    }

    public function create()
    {
        $this->render('auth', 'auth/categories/form');
    }

    public function store(Request $request, Response $response)
    {
        $category = new Category();
        if ($request->isPost()) {
            $params = $request->getBody();
            unset($params['image']);
            if ($request->has('image')) {
                $path = $request->file('image')->store('categories');
                $params['image'] = $path;
            }
            $category->getRepo()->save($params);
            $response->redirect('/admin/categories');
        }
    }

    public function show(int $id)
    {
        $category = (new Category())->getRepo()->find($id);

        $this->render('auth', 'auth/categories/show', compact('category'));
    }

    public function edit(int $id)
    {
        $category = (new Category())->getRepo()->find($id);

        $this->render('auth', 'auth/categories/form', compact('category'));

    }

    public function update(Request $request, Response $response, int $id)
    {
        $category = (new Category())->getRepo()->find($id);

        if ($request->isPost()) {
            $params = $request->getBody();
            if ($request->has('image')) {
                Storage::delete($category->image);
                $path = $request->file('image')->store('products', true);
                $params['image'] = $path;
            }
            $category->getRepo()->update($id, $params);
            $response->redirect('/admin/categories');
        }
    }

    public function destroy(Response $response, int $id)
    {
        (new Category())->getRepo()->findByIdAndDelete(['id' => $id]);

        $response->redirect('/admin/categories');
    }
}