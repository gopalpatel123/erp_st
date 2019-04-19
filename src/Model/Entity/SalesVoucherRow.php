<?php
namespace App\Model\Entity;

use Cake\ORM\Entity;

/**
 * SalesVoucherRow Entity
 *
 * @property int $id
 * @property int $sales_voucher_id
 * @property string $cr_dr
 * @property int $ledger_id
 * @property float $debit
 * @property float $credit
 * @property string $mode_of_payment
 * @property string $cheque_no
 * @property \Cake\I18n\FrozenDate $cheque_date
 *
 * @property \App\Model\Entity\SalesVoucher $sales_voucher
 * @property \App\Model\Entity\Ledger $ledger
 * @property \App\Model\Entity\ReferenceDetail[] $reference_details
 */
class SalesVoucherRow extends Entity
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
