<?php
$this->title = 'Корзина'
?>

<h1>Корзина</h1>
<p>Оформление заказа</p>
<div class="panel">
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
      <?php foreach ($products as $product):?>
      <tr>
         <td>
            <a href="<?= $product->category()->code . '/' . $product->code?>">
               <img height="56px" src="<?= url($product->image)?>">
            <?=$product->name?>
            </a>
         </td>
         <td><span class="badge"><?=(new \App\Models\Cart())->countItems($product->id)?></span>
            <div class="btn-group form-inline">
               <form action="/cart/remove/<?=$product->id?>" method="GET">
                  <button type="submit" class="btn btn-danger" href=""><span
                             class="glyphicon glyphicon-minus" aria-hidden="true"></span></button>
                  </form>
               <form action="/cart/add/<?=$product->id?>" method="GET">
                  <button type="submit" class="btn btn-success"
                          href=""><span class="glyphicon glyphicon-plus" aria-hidden="true"></span></button>

               </form>
            </div>
         </td>
         <td><?=$product->price?></td>
         <td><?= (new \App\Models\Cart())->getTotalPrice($product->id,$product->price) ?></td>
      </tr>
      <?php endforeach;?>
      <tr>
         <td colspan="3">Общая стоимость:</td>
         <td><?= (new \App\Models\Cart())->getFullSum() ?></td>
      </tr>
      </tbody>
   </table>
   <br>
   <div class="btn-group pull-right" role="group">
      <a type="button" class="btn btn-success" href="/checkout">Оформить заказ</a>
   </div>
</div>

