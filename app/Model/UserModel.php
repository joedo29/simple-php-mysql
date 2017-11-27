<?php
/**
 * Created by PhpStorm.
 * User: joedo
 * Date: 11/18/17
 * Time: 8:41 AM
 */

namespace App\Model;

use App\DB\AbstractModel;

class UserModel extends AbstractModel
{

    public function getTable(): string
    {
        return 'users';
    }
}