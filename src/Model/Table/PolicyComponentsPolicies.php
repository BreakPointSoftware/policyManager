<?php
declare(strict_types=1);

namespace App\Model\Table;

use Cake\ORM\Table;


class PolicyComponentsPolicies extends Table
{
    /**
     * Initialize method
     *
     * @param array $config The configuration for the Table.
     * @return void
     */
    public function initialize(array $config): void
    {
        $this->belongsTo('PolicyComponents');
        $this->belongsTo('Policies');
    }
}


