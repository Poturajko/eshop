<?php
$this->title = 'Товары';
?>
    <div class="col-md-12">
        <h1>Товары</h1>
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
                    Категория
                </th>
                <th>
                    Цена
                </th>
                <th>
                    Действия
                </th>
            </tr>
            <?php foreach($products as $product): ?>
                <tr>
                    <td><?= $product->id ?></td>
                    <td><?= $product->code ?></td>
                    <td><?= $product->name ?></td>
                    <td><?= $product->category()->name ?></td>
                    <td><?= $product->price ?></td>
                    <td>
                        <div class="btn-group" role="group">
                            <form action="/admin/products/delete/<?=$product->id?>" method="POST">
                                <a class="btn btn-success" type="button"
                                   href="/admin/products/<?=$product->id?>">Открыть</a>
                                <a class="btn btn-warning" type="button"
                                   href="/admin/<?=$product->id?>/products">Редактировать</a>
                                <input class="btn btn-danger" type="submit" value="Удалить"></form>
                        </div>
                    </td>
                </tr>
            <?php endforeach; ?>
            </tbody>
        </table>
        <a class="btn btn-success" type="button" href="/admin/products/create">Добавить товар</a>
    </div>