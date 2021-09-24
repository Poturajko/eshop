<?php

namespace App\Controllers\Admin;

use App\Core\Base\BaseController;
use App\Core\Request\Request;
use App\Core\Storage\File;
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

    public function store(Request $request)
    {
        $category = new Category();
        if ($request->isPost()) {
            $params = $request->getBody();
            unset($params['image']);
            if ($request->has('image')) {
                $file = new File($request->file('image'));
                $path = $file->save('categories');
                $params['image'] = $path;
            }
            $category->getRepo()->save($params);
            redirect('/admin/categories');
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

    public function update(Request $request, int $id)
    {
        $category = (new Category())->getRepo()->find($id);

        if ($request->isPost()) {
            $params = $request->getBody();
            unset($params['image']);
            if ($request->has('image')) {
                $file = new File($request->file('image'));
                if (!is_null($category->image)) {
                    $file->delete($category->image);
                }
                $path = $file->save('categories');
                $params['image'] = $path;
            }
            $category->getRepo()->update($id, $params);
            redirect('/admin/categories');
        }
    }

    public function destroy(int $id)
    {
        $category = (new Category())->getRepo()->find($id);
        if (!is_null($category->image)) {
            (new File())->delete($category->image);
        }
        $category->getRepo()->delete($category->id);

        redirect('/admin/categories');
    }
}