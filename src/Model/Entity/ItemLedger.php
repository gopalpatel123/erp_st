<?php
namespace App\Model\Entity;

use Cake\ORM\Entity;

/**
 * ItemLedger Entity
 *
 * @property int $id
 * @property int $item_id
 * @property \Cake\I18n\FrozenDate $transaction_date
 * @property float $quantity
 * @property float $rate
 * @property float $amount
 * @property string $status
 * @property string $is_opening_balance
 *
 * @property \App\Model\Entity\Item $item
 */
class ItemLedger extends Entity
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
