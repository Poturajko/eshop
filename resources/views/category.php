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
                <?php if($product->isNew()): ?>

                   <span class="badge badge-success">Новинка</span>
                <?php endif; ?>

                <?php if($product->isRecommend()): ?>
                   <span class="badge badge-warning">Рекомендуем</span>
                <?php endif; ?>

                <?php if($product->isHit()): ?>
                   <span class="badge badge-danger">Хит продаж</span>
                <?php endif; ?>
            </div>

            <img src="<?=url($product->image)?>" alt="<?=$product->name?>">
            <div class="caption">
               <h3><?=$product->name?></h3>
               <p><?=$product->price?></p>
               <p>
               <form action="/cart/add/<?=$product->id?>" method="GET">
                  <button type="submit" class="btn btn-primary" role="button">В корзину</button>
                  <a href="/<?=$category->code?>/<?=$product->code?>"
                     class="btn btn-default"
                     role="button">Подробнее</a>
               </form>
               </p>
            </div>
         </div>
      </div>

    <?php endforeach; ?>
</div>