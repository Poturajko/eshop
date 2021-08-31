<?php
$this->title = 'Продукт ' . $product->name;
?>
<div class="col-md-12">
   <h1><?= $product->name ?></h1>
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
         <td><?= $product->id ?></td>
      </tr>
      <tr>
         <td>Код</td>
         <td><?= $product->code ?></td>
      </tr>
      <tr>
         <td>Название</td>
         <td><?= $product->name ?></td>
      </tr>
      <tr>
         <td>Описание</td>
         <td><?= $product->description ?></td>
      </tr>
      <tr>
         <td>Картинка</td>
         <td><img src="#" height="240px"></td>
      </tr>
      <tr>
         <td>Категория</td>
         <td><?= $product->category()->name ?></td>
      </tr>
      <tr>
         <td>Лейблы</td>
         <td>
             <?php if ($product->isNew()): ?>
                <span class="badge badge-success">Новинка</span>
             <?php endif; ?>
             <?php if ($product->isRecommend()): ?>
                <span class="badge badge-warning">Рекомендуем</span>
             <?php endif; ?>
             <?php if ($product->isHit()): ?>
                <span class="badge badge-danger">Хит продаж</span>
             <?php endif; ?>
         </td>
      </tr>
      </tbody>
   </table>
</div>
