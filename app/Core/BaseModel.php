<?php

namespace App\Core;

use App\Core\Database\DatabaseModel;
use App\Models\Product;

abstract class BaseModel extends DatabaseModel
{

    abstract public function attributes(): array;

    abstract public function rules(): array;

    abstract public function tableName(): string;

}