<h1>Все товары</h1>
<form method="GET" action="http://internet-shop.tmweb.ru">
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
         <a href="http://internet-shop.tmweb.ru" class="btn btn-warning">Сброс</a>
      </div>
   </div>
</form>
<div class="row">

    <?php foreach ($products as $product): ?>

       <div class="col-sm-6 col-md-4">
          <div class="thumbnail">
             <div class="labels">


             </div>
             <img src="http://internet-shop.tmweb.ru/storage/products/iphone_x_silver.jpg" alt="iPhone X 256GB">
             <div class="caption">
                <h3><?=$product['name']?></h3>
                <p><?=$product['price']?></p>
                <p>
                <form action="http://internet-shop.tmweb.ru/basket/add/2" method="POST">
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

   <nav>
      <ul class="pagination">

         <li class="page-item disabled" aria-disabled="true" aria-label="pagination.previous">
            <span class="page-link" aria-hidden="true">&lsaquo;</span>
         </li>


         <li class="page-item active" aria-current="page"><span class="page-link">1</span></li>
         <li class="page-item"><a class="page-link" href="?&amp;page=2">2</a></li>


         <li class="page-item">
            <a class="page-link" href="?&amp;page=2" rel="next" aria-label="pagination.next">&rsaquo;</a>
         </li>
      </ul>
   </nav>