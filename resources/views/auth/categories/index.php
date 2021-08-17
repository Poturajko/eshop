<?php
$this->title = 'Категории'
?>
    <div class="col-md-12">
        <h1>Категории</h1>
        <table class="table">
            <tbody>
            <tr>
                <th>
                    #
                </th>
                <th>
                    Код
                </th>
                <th>
                    Название
                </th>
                <th>
                    Действия
                </th>
            </tr>
            <?php foreach($categories as $category): ?>
                <tr>
                    <td><?= $category->id?></td>
                    <td><?= $category->code?></td>
                    <td><?= $category->name?></td>
                    <td>
                        <div class="btn-group" role="group">
                            <form action="/admin/categories/delete/<?= $category->id ?>" method="POST">
                                <a class="btn btn-success" type="button" href="/admin/categories/<?= $category->id ?>">Открыть</a>
                                <a class="btn btn-warning" type="button" href="/admin/<?= $category->id ?>/categories">Редактировать</a>
                                <input class="btn btn-danger" type="submit" value="Удалить"></form>
                        </div>
                    </td>
                </tr>
            <?php endforeach; ?>
            </tbody>
        </table>
        <a class="btn btn-success" type="button"
           href="/admin/categories/create">Добавить категорию</a>
    </div>

