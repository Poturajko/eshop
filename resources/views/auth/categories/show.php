<?php
$this->title = 'Категория ' . $category->id
?>

    <div class="col-md-12">
        <h1>Категория Бытовая техника</h1>
        <table class="table">
            <tbody>
            <tr>
                <th>
                    Поле
                </th>
                <th>
                    Значение
                </th>
            </tr>
            <tr>
                <td>ID</td>
                <td><?=$category->id?></td>
            </tr>
            <tr>
                <td>Код</td>
                <td><?=$category->code?></td>
            </tr>
            <tr>
                <td>Название</td>
                <td><?=$category->name?></td>
            </tr>
            <tr>
                <td>Описание</td>
                <td><?=$category->description?></td>
            </tr>
            <tr>
                <td>Картинка</td>
                <td><img src="#" height="240px"></td>
            </tr>
            <tr>
                <td>Кол-во товаров</td>
                <td><?= $category->products() !== null ? count($category->products()) : 0 ?></td>
            </tr>
            </tbody>
        </table>
    </div>
