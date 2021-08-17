<?php
$this->title = $category->name
?>
<h1>
   <?= $category->name ?>
</h1>
<p>
    <?= $category->description ?>
</p>
<div class="row">
   <?php foreach ($category->products() as $product): ?>

      <div class="col-sm-6 col-md-4">
         <div class="thumbnail">
            <div class="labels">

            </div>
            <img src="http://internet-shop.tmweb.ru/storage/products/iphone_x_silver.jpg" alt="iPhone X 256GB">
            <div class="caption">
               <h3><?=$product->name?></h3>
               <p><?=$product->price?></p>
               <p>
               <form action="/cart/add/<?=$product->id?>" method="GET">
                  <button type="submit" class="btn btn-primary" role="button">В корзину</button>
                  <a href="http://internet-shop.tmweb.ru/mobiles/iphone_x_256"
                     class="btn btn-default"
                     role="button">Подробнее</a>
               </form>
               </p>
            </div>
         </div>
      </div>

    <?php endforeach; ?>
</div>