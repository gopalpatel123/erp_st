<?php
namespace App\Model\Entity;

use Cake\ORM\Entity;

/**
 * GrnRow Entity
 *
 * @property int $id
 * @property int $grn_id
 * @property int $item_id
 * @property float $quantity
 * @property float $purchase_rate
 * @property float $sale_rate
 *
 * @property \App\Model\Entity\Grn $grn
 * @property \App\Model\Entity\Item $item
 */
class GrnRow extends Entity
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
