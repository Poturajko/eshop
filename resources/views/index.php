<?php
$this->title = 'Главная'
?>
<h1>Все товары</h1>
<form method="GET" action="/">
   <div class="filters row">
      <div class="col-sm-6 col-md-3">
         <label for="price_from">Цена от <input type="text" name="price_from" id="price_from" size="6" value="">
         </label>
         <label for="price_to">до <input type="text" name="price_to" id="price_to" size="6" value="">
         </label>
      </div>
      <div class="col-sm-2 col-md-2">
         <label for="hit">
            <input type="checkbox" name="hit" id="hit"> Хит </label>
      </div>
      <div class="col-sm-2 col-md-2">
         <label for="new">
            <input type="checkbox" name="new" id="new"> Новинка </label>
      </div>
      <div class="col-sm-2 col-md-2">
         <label for="recommend">
            <input type="checkbox" name="recommend" id="recommend"> Рекомендуем </label>
      </div>
      <div class="col-sm-6 col-md-3">
         <button type="submit" class="btn btn-primary">Фильтр</button>
         <a href="/" class="btn btn-warning">Сброс</a>
      </div>

   </div>
</form>
<div class="row">

    <?php foreach ($products as $product): ?>

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
             <img src="<?= url($product->image) ?>" alt="<?= $product->name ?>"/>
             <div class="caption">
                <h3><?= $product->name ?></h3>
                <p><?= $product->price ?></p>
                <p>
                <form action="/cart/add/<?= $product->id ?>" method="GET">
                   <button type="submit" class="btn btn-primary" role="button">В корзину</button>
                   <a href="/<?= $product->category()->code ?>/<?= $product->code ?>" class="btn btn-default"
                      role="button">Подробнее</a>
                </form>
                </p>
             </div>
          </div>
       </div>

    <?php endforeach; ?>
</div>

<?= $paginate->get() ?>
