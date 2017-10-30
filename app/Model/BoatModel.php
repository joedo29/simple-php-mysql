<?php
declare(strict_types=1);

namespace App\Model;


use App\DB\AbstractModel;

/**
 * Class BoatModel
 * @package App\Model
 */
class BoatModel extends AbstractModel
{
    protected $table;

    public function getTable(): string
    {
        return 'boats';
    }
}