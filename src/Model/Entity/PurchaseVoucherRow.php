<?php
namespace App\Model\Entity;

use Cake\ORM\Entity;

/**
 * PurchaseVoucherRow Entity
 *
 * @property int $id
 * @property int $purchase_voucher_id
 * @property int $ledger_id
 * @property float $debit
 * @property float $credit
 *
 * @property \App\Model\Entity\PurchaseVoucher $purchase_voucher
 * @property \App\Model\Entity\Ledger $ledger
 */
class PurchaseVoucherRow extends Entity
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
