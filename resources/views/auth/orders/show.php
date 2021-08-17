<?php
$title = 'Заказ ' . $order->id;
?>
    <div class="py-4">
        <div class="container">
            <div class="justify-content-center">
                <div class="panel">
                    <h1>Заказ №<?= $order->id ?></h1>
                    <p>Заказчик: <b><?= $order->name ?></b></p>
                    <p>Номер теелфона: <b><?= $order->phone ?></b></p>
                    <table class="table table-striped">
                        <thead>
                        <tr>
                            <th>Название</th>
                            <th>Кол-во</th>
                            <th>Цена</th>
                            <th>Стоимость</th>
                        </tr>
                        </thead>
                        <tbody>
                        <?php foreach ($order->products() as $product): ?>
                            <tr>
                                <td>
                                    <a href="/<?=$product->category()->code?>/<?=$product->id?>">
                                        <img height="56px"
                                             src="#">
                                        <?= $product->name ?>
                                    </a>
                                </td>
                                <td><span class="badge">1</span></td>
                                <td><?= $product->price ?> $</td>
                                <td> $</td>
                            </tr>
                        <?php endforeach; ?>
                        <tr>
                            <td colspan="3">Общая стоимость:</td>
                            <td><?= $order->getFullPrice() ?> $ </td>
                        </tr>
                        </tbody>
                    </table>
                    <br>
                </div>
            </div>
        </div>
    </div>
