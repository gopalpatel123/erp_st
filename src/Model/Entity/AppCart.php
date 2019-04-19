<?php
namespace App\Model\Entity;

use Cake\ORM\Entity;

/**
 * AppCart Entity
 *
 * @property int $id
 * @property int $item_id
 * @property int $user_id
 * @property float $quantity
 * @property int $company_id
 * @property int $location_id
 *
 * @property \App\Model\Entity\Item $item
 * @property \App\Model\Entity\User $user
 * @property \App\Model\Entity\Company $company
 * @property \App\Model\Entity\Location $location
 */
class AppCart extends Entity
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
        '*' => true,
        'id' => false
    ];
}
