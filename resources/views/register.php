<?php
$this->title = 'Авторизация'
?>
<div class="col-md-8">
   <div class="card">
      <div class="card-header">Регистрация</div>

      <div class="card-body">
         <form method="POST" action="/register" aria-label="Register">
            <div class="form-group row">
               <label for="name" class="col-md-4 col-form-label text-md-right">Имя</label>

               <div class="col-md-6">
                  <input id="name" value="<?= $user->name ?? '' ?>" type="text" class="form-control" name="name"
                         required autofocus>
                   <?php if ($user->hasError('name')): ?>
                      <div class="alert alert-danger">
                          <?= $user->getFirstError('name') ?>
                      </div>
                   <?php endif; ?>
               </div>
            </div>

            <div class="form-group row">
               <label for="email" class="col-md-4 col-form-label text-md-right">E-Mail</label>

               <div class="col-md-6">
                  <input id="email" value="<?= $user->email ?? '' ?>" type="email" class="form-control"
                         name="email" required>
                   <?php if ($user->hasError('email')): ?>
                      <div class="alert alert-danger">
                          <?= $user->getFirstError('email') ?>
                      </div>
                   <?php endif; ?>
               </div>
            </div>

            <div class="form-group row">
               <label for="password" class="col-md-4 col-form-label text-md-right">Пароль</label>

               <div class="col-md-6">
                  <input id="password" value="<?= $user->password ?? '' ?>" type="password" class="form-control"
                         name="password" required>
                   <?php if ($user->hasError('password')): ?>
                      <div class="alert alert-danger">
                          <?= $user->getFirstError('password') ?>
                      </div>
                   <?php endif; ?>
               </div>
            </div>

            <div class="form-group row">
               <label for="password-confirm" class="col-md-4 col-form-label text-md-right">Подтвердите
                  пароль</label>

               <div class="col-md-6">
                  <input id="password-confirm" value="<?= $user->passwordConfirmation ?? '' ?>" type="password"
                         class="form-control" name="passwordConfirmation"
                         required>
                   <?php if ($user->hasError('passwordConfirmation')): ?>
                      <div class="alert alert-danger">
                          <?= $user->getFirstError('passwordConfirmation') ?>
                      </div>
                   <?php endif; ?>
               </div>
            </div>

            <div class="form-group row mb-0">
               <div class="col-md-6 offset-md-4">
                  <button type="submit" class="btn btn-primary">
                     Зарегистрироваться
                  </button>
               </div>
            </div>
         </form>
         <div class="card-body">
         </div>
      </div>
   </div>
</div>
