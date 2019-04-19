<?php
namespace App\Model\Entity;

use Cake\ORM\Entity;

/**
 * SecondTampGrnRecord Entity
 *
 * @property int $id
 * @property string $item_code
 * @property float $quantity
 * @property float $purchase_rate
 * @property float $sales_rate
 * @property int $user_id
 * @property string $processed
 * @property int $is_addition_item_data_required
 * @property string $item_name
 * @property string $hsn_code
 * @property string $unit
 * @property int $gst_rate_fixed_or_fluid
 * @property float $first_gst_rate
 * @property float $amount_in_ref_of_gst_rate
 * @property float $second_gst_rate
 *
 * @property \App\Model\Entity\User $user
 */
class SecondTampGrnRecord extends Entity
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
