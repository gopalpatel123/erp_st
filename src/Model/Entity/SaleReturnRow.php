<?php
namespace App\Model\Entity;

use Cake\ORM\Entity;

/**
 * SaleReturnRow Entity
 *
 * @property int $id
 * @property int $sale_return_id
 * @property int $item_id
 * @property float $quantity
 * @property float $rate
 * @property float $discount_percentage
 * @property float $taxable_value
 * @property float $net_amount
 * @property int $gst_figure_id
 * @property float $gst_value
 *
 * @property \App\Model\Entity\SaleReturn $sale_return
 * @property \App\Model\Entity\Item $item
 * @property \App\Model\Entity\GstFigure $gst_figure
 */
class SaleReturnRow extends Entity
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
