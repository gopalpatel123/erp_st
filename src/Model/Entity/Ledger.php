<?php
namespace App\Model\Entity;

use Cake\ORM\Entity;

/**
 * Ledger Entity
 *
 * @property int $id
 * @property string $name
 * @property int $accounting_group_id
 * @property bool $freeze
 * @property int $company_id
 * @property int $supplier_id
 * @property int $customer_id
 * @property float $tax_percentage
 * @property string $gst_type
 *
 * @property \App\Model\Entity\AccountingGroup $accounting_group
 * @property \App\Model\Entity\Company $company
 * @property \App\Model\Entity\Supplier $supplier
 * @property \App\Model\Entity\Customer $customer
 * @property \App\Model\Entity\AccountingEntry[] $accounting_entries
 */
class Ledger extends Entity
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
