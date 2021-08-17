<?php

namespace App\Controllers\Admin;

use App\Core\Controller;
use App\Core\Middleware\CheckIsAdminMiddleware;
use App\Core\Request;
use App\Core\Response;
use App\Core\Storage;
use App\Models\Category;

class CategoryController extends Controller
{

    public function __construct()
    {
        $this->registerMiddleware(new CheckIsAdminMiddleware(['index','create','store','show','edit','update','destroy']));
    }

    public function index()
    {
        $categories = (new Category())->all();

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
            $category->create($params);
            $response->redirect('/admin/categories');
        }
    }

    public function show(Request $request, Response $response, int $id)
    {
        $category = (new Category())->where('id', $id)->first();

        $this->render('auth', 'auth/categories/show', compact('category'));
    }

    public function edit(Request $request, Response $response, int $id)
    {
        $category = (new Category())->where('id', $id)->first();

        $this->render('auth', 'auth/categories/form', compact('category'));

    }

    public function update(Request $request, Response $response, int $id)
    {
        $category = (new Category())->where('id', $id)->first();

        if ($request->isPost()) {
            $params = $request->getBody();
            unset($params['image']);
            if ($category->validate()) {
                if ($request->has('image')) {
                    Storage::delete($category->image);
                    $path = $request->file('image')->store('categories');
                    $params['image'] = $path;
                }
                $category->update($params);
                $response->redirect('/admin/categories');
            }
        }
    }

    public function destroy(Request $request, Response $response, int $id)
    {
        $category = (new Category())->where('id', $id)->first();
        $category->delete();

        $response->redirect('/admin/categories');
    }
}