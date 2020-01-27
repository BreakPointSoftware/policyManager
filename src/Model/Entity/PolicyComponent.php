<?php
declare(strict_types=1);

namespace App\Model\Entity;

use Cake\ORM\Entity;

/**
 * Var Entity
 *
 * @property int $id
 * @property string|null $name
 * @property string|null $description
 * @property string|null $value
 * @property \Cake\I18n\FrozenTime|null $created
 * @property \Cake\I18n\FrozenTime|null $modified
 *
 * @property \App\Model\Entity\PolicyComponent[] $policy_vars
 */
class PolicyComponent extends Entity
{
    /**
     * Fields that can be mass assigned using newEntity() or patchEntity().
     *
     * Note that when '*' is set to true, this allows all unspecified fields to
     * be mass assigned. For security purposes, it is advised to set '*' to false
     * (or remove it), and explicitly make individual fields accessible as needed.
     *
     * @var array
     */
    protected $_accessible = [
        'name' => true,
        'description' => true,
        'value' => true,
        'created' => true,
        'modified' => true,
        'replacement' => true,
        'policy_components_policies' => true,
    ];
}
