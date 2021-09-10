<?php
(isset($category))
    ? $this->title = 'Редактировать категорию ' . $category->name
    : $this->title = 'Создать категорию';
?>

<div class="col-md-12">

    <?php
    echo (isset($category))
        ? '<h1>Редактировать Категорию <b>' . $category->name . '</b></h1>'
        : '<h1>Добавить Категорию</h1>';
    ?>

   <form method="POST" enctype="multipart/form-data"
       <?php
       echo (isset($category))
           ? "action='/admin/categories/$category->id'"
           : "action='/admin/categories'";
       ?>
   >
      <div>
         <div class="input-group row">
            <label for="code" class="col-sm-2 col-form-label">Код: </label>
            <div class="col-sm-6">
               <input type="text" class="form-control" name="code" id="code" value="<?=$category->code?>">
            </div>
         </div>
         <br>
         <div class="input-group row">
            <label for="name" class="col-sm-2 col-form-label">Название: </label>
            <div class="col-sm-6">
               <input type="text" class="form-control" name="name" id="name" value="<?=$category->name?>">
            </div>
         </div>
         <br>
         <div class="input-group row">
            <label for="description" class="col-sm-2 col-form-label">Описание: </label>
            <div class="col-sm-6">
               <textarea name="description" id="description" cols="72" rows="7"> <?=$category->description?> </textarea>
            </div>
         </div>
         <br>
         <div class="input-group row">
            <label for="image" class="col-sm-2 col-form-label">Картинка: </label>
            <div class="col-sm-10">
               <label class="btn btn-default btn-file">
                  Загрузить <input type="file" style="display: none;" name="image" id="image">
               </label>
            </div>
         </div>
         <button class="btn btn-success">Сохранить</button>
      </div>
   </form>
</div>

