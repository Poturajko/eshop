<?php

namespace App\Core\Orm\QueryBuilder;

interface IQueryBuilder
{
    public function insertQuery(): string;

    public function deleteQuery(): string;

    public function updateQuery(): string;

    public function selectQuery(): string;

    public function searchQuery(): string;

}