<!DOCTYPE html>
<html lang="en">
<head>
   <meta charset="utf-8">
   <meta http-equiv="X-UA-Compatible" content="IE=edge">
   <meta name="viewport" content="width=device-width, initial-scale=1">

   <title>Админка: <?= $this->title ?></title>

   <!-- Scripts -->
   <script src="/js/app.js" defer></script>

   <!-- Fonts -->
   <link rel="dns-prefetch" href="https://fonts.gstatic.com">
   <link href="https://fonts.googleapis.com/css?family=Nunito" rel="stylesheet" type="text/css">

   <!-- Styles -->
   <link href="/css/app.css" rel="stylesheet">
   <link href="/css/bootstrap.min.css" rel="stylesheet">
   <link href="/css/admin.css" rel="stylesheet">
</head>
<body>
<div id="app">
   <nav class="navbar navbar-default navbar-expand-md navbar-light navbar-laravel">
      <div class="container">
         <a class="navbar-brand" href="/">
            Вернуться на сайт
         </a>

         <div id="navbar" class="collapse navbar-collapse">
            <ul class="nav navbar-nav">
                <?php if (admin()): ?>
                   <li>
                      <a href="/admin/categories">Категории</a>
                   </li>
                   <li><a href="/admin/products">Товары</a>
                   </li>
                   <li><a href="/admin/orders">Заказы</a></li>
                <?php endif; ?>
            </ul>

             <?php if (guest()): ?>
                <ul class="nav navbar-nav navbar-right">
                   <li class="nav-item">
                      <a class="nav-link" href="/login">Войти</a>
                   </li>
                   <li class="nav-item">
                      <a class="nav-link" href="/register">Зарегистрироваться</a>
                   </li>
                </ul>
             <?php endif; ?>

             <?php if (auth()): ?>
                <ul class="nav navbar-nav navbar-right">
                   <li class="nav-item dropdown">
                      <a id="navbarDropdown" class="nav-link dropdown-toggle" href="#" role="button"
                         data-toggle="dropdown"
                         aria-haspopup="true" aria-expanded="false" v-pre>
                         <?php if (admin()): ?>
                              Администратор
                          <?php else: ?>
                              Кабинет
                          <?php endif; ?>
                      </a>

                      <div class="dropdown-menu dropdown-menu-right" aria-labelledby="navbarDropdown">
                         <a class="dropdown-item" href="/logout"
                            onclick="event.preventDefault();
                                                     document.getElementById('logout-form').submit();">
                            Выйти
                         </a>

                         <form id="logout-form" action="/logout" method="GET"
                               style="display: none;">
                         </form>
                      </div>
                   </li>
                </ul>
             <?php endif; ?>
         </div>
      </div>
   </nav>

   <div class="py-4">
      <div class="container">
         <div class="row justify-content-center">
            {{content}}
         </div>
      </div>
   </div>
</div>
</body>
</html>
