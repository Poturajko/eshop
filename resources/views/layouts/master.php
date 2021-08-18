<!doctype html>
<html lang="en">
<head>
   <meta charset="utf-8">
   <meta http-equiv="X-UA-Compatible" content="IE=edge">
   <meta name="viewport" content="width=device-width, initial-scale=1">

   <title>Интернет Магазин: <?= $this->title ?></title>

   <link href="https://fonts.googleapis.com/css?family=Raleway:100,600" rel="stylesheet" type="text/css">
   <script src="/js/jquery.min.js"></script>
   <script src="/js/bootstrap.min.js"></script>
   <link href="/css/bootstrap.min.css" rel="stylesheet">
   <link href="/css/starter-template.css" rel="stylesheet">
</head>
<body>
<nav class="navbar navbar-inverse navbar-fixed-top">
   <div class="container">
      <div class="navbar-header">
         <a class="navbar-brand" href="/">Интернет Магазин</a>
      </div>
      <div id="navbar" class="collapse navbar-collapse">
         <ul class="nav navbar-nav">
            <li <?php echo (\App\Core\Application::routeActive('index')) ? 'class="active"' : '' ?> >
               <a href="/">Все товары</a></li>
            <li <?php echo (\App\Core\Application::routeActive('categories')) ? 'class="active"' : '' ?> >
               <a href="/categories">Категории</a></li>
            <li <?php echo (\App\Core\Application::routeActive('cart')) ? 'class="active"' : '' ?> >
               <a href="/cart">В корзину</a></li>
            <li><a href="http://internet-shop.tmweb.ru/reset">Сбросить проект в начальное состояние</a></li>
            <li><a href="http://internet-shop.tmweb.ru/locale/en">en</a></li>

            <li class="dropdown">
               <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true"
                  aria-expanded="false">₽<span class="caret"></span></a>
               <ul class="dropdown-menu">
                  <li><a href="http://internet-shop.tmweb.ru/currency/RUB">₽</a></li>
                  <li><a href="http://internet-shop.tmweb.ru/currency/USD">$</a></li>
                  <li><a href="http://internet-shop.tmweb.ru/currency/EUR">€</a></li>
               </ul>
            </li>
         </ul>

         <ul class="nav navbar-nav navbar-right">

             <?php if (\App\Core\Application::isGuest()): ?>
                <li><a href="/login">Войти</a></li>
             <?php endif; ?>

             <?php if (\App\Core\Application::auth()): ?>

                 <?php if (\App\Core\Application::isAdmin()): ?>
                   <li><a href="/admin/orders">Панель администратора</a></li>
                 <?php else: ?>
                   <li><a href="/user/orders">Мои заказы</a></li>
                 <?php endif; ?>
                <li><a href="/logout">Выйти</a></li>
             <?php endif; ?>

         </ul>
      </div>
   </div>
</nav>

<div class="container">
   <div class="starter-template">
       <?php if (\App\Core\Application::$app->session->has('warning')): ?>
          <p class="alert alert-warning"><?= \App\Core\Application::$app->session->flush('warning') ?></p>
       <?php endif; ?>

       <?php if (\App\Core\Application::$app->session->has('success')): ?>
          <p class="alert alert-success"><?= \App\Core\Application::$app->session->flush('success') ?></p>
       <?php endif; ?>
      {{content}}
   </div>
</div>
</body>
</html>
