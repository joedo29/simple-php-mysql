<?php
/**
 * Created by PhpStorm.
 * User: joedo
 * Date: 11/18/17
 * Time: 9:51 AM
 */

namespace App\Model;


use App\DB\AbstractModel;

class BookModel extends AbstractModel
{
    public function getTable(): string
    {
        return 'books';
    }
}