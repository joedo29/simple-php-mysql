<?php
declare(strict_types=1);

namespace App\Model;


use App\DB\AbstractModel;

/**
 * Class SailorModel
 * @package App\Model
 */
class SailorModel extends AbstractModel
{
    protected $table;

    public function getTable(): string
    {
        return 'sailors';
    }
}