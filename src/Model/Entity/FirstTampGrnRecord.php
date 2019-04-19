<?php
namespace App\Model\Entity;

use Cake\ORM\Entity;

/**
 * FirstTampGrnRecord Entity
 *
 * @property int $id
 * @property string $item_code
 * @property float $quantity
 * @property float $purchase_rate
 * @property float $sales_rate
 * @property int $user_id
 * @property string $processed
 * @property string $is_addition_item_data_required
 *
 * @property \App\Model\Entity\User $user
 */
class FirstTampGrnRecord extends Entity
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
