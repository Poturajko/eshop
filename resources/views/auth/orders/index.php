<?php
$this->title = 'Заказы';
?>
    <div class="col-md-12">
        <h1>Заказы</h1>
        <table class="table">
            <tbody>
            <tr>
                <th>
                    #
                </th>
                <th>
                    Имя
                </th>
                <th>
                    Телефон
                </th>
                <th>
                    Почта
                </th>
                <th>
                    Когда отправлен
                </th>
                <th>
                    Сумма
                </th>
                <th>
                    Действия
                </th>
            </tr>
            <?php foreach($orders as $order): ?>
                <tr>
                    <td><?= $order->id ?></td>
                    <td><?= $order->name ?></td>
                    <td><?= $order->phone ?></td>
                    <td><?= $order->email ?></td>
                    <td><?= $order->created_at ?></td>
                    <td><?= $order->getFullPrice() ?></td>
                    <td>
                        <div class="btn-group" role="group">
                            <a class="btn btn-success" type="button"
                               <?php if (\App\Core\Application::isAdmin()): ?>
                               href="/admin/orders/<?=$order->id?>"
                               <?php else: ?>
                               href="/user/orders/<?=$order->id?>"
                                <?php endif; ?>
                            >Открыть</a>
                        </div>
                    </td>
                </tr>
            <?php endforeach; ?>

            </tbody>
        </table>
    </div>
