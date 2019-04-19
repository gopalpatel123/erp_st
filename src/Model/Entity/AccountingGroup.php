<?php
namespace App\Model\Entity;

use Cake\ORM\Entity;

/**
 * AccountingGroup Entity
 *
 * @property int $id
 * @property int $nature_of_group_id
 * @property string $name
 * @property int $parent_id
 * @property int $lft
 * @property int $rght
 * @property int $company_id
 * @property bool $customer
 * @property bool $supplier
 *
 * @property \App\Model\Entity\NatureOfGroup $nature_of_group
 * @property \App\Model\Entity\AccountingGroup $parent_accounting_group
 * @property \App\Model\Entity\Company $company
 * @property \App\Model\Entity\AccountingGroup[] $child_accounting_groups
 * @property \App\Model\Entity\Ledger[] $ledgers
 */
class AccountingGroup extends Entity
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
