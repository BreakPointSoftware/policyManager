<?php
declare(strict_types=1);

namespace App\Model\Entity;

use Cake\ORM\Entity;

/**
 * Policy Entity
 *
 * @property int $id
 * @property int|null $user_id
 * @property int|null $editor_id
 * @property string|null $title
 * @property string|null $body
 * @property \Cake\I18n\FrozenTime|null $created
 * @property \Cake\I18n\FrozenTime|null $modified
 * @property string|null $version
 * @property \Cake\I18n\FrozenTime|null $approved
 * @property \Cake\I18n\FrozenTime|null $expiry
 *
 * @property \App\Model\Entity\User $user
 * 
 * @property \App\Model\Entity\PolicyComponents[] $PolicyComponents
 */
class Policy extends Entity
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
        //'*' => true, //for debug #hack #return #no longer need this test
        'user_id' => true,
        'title' => true,
        'body' => true,
        'created' => true,
        'modified' => true,
        'version' => true,
        'approved' => true,
        'expiry' => true,
        'user' => true,
        'editors_policies' => true,
        'policy_components' => true,
    ];
}
