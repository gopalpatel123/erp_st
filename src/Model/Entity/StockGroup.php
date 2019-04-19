<?php
namespace App\Model\Entity;

use Cake\ORM\Entity;

/**
 * StockGroup Entity
 *
 * @property int $id
 * @property string $name
 * @property int $parent_id
 * @property int $lft
 * @property int $rght
 * @property int $company_id
 *
 * @property \App\Model\Entity\ParentStockGroup $parent_stock_group
 * @property \App\Model\Entity\Company $company
 * @property \App\Model\Entity\Item[] $items
 * @property \App\Model\Entity\ChildStockGroup[] $child_stock_groups
 */
class StockGroup extends Entity
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
