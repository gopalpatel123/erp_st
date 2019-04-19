<?php
namespace App\Model\Entity;

use Cake\ORM\Entity;

/**
 * ReferenceDetail Entity
 *
 * @property int $id
 * @property int $company_id
 * @property int $ledger_id
 * @property string $type
 * @property string $name
 * @property float $debit
 * @property float $credit
 * @property int $receipt_id
 * @property int $receipt_row_id
 * @property int $payment_id
 * @property int $payment_row_id
 *
 * @property \App\Model\Entity\Company $company
 * @property \App\Model\Entity\Ledger $ledger
 * @property \App\Model\Entity\Receipt $receipt
 * @property \App\Model\Entity\ReceiptRow $receipt_row
 * @property \App\Model\Entity\Payment $payment
 * @property \App\Model\Entity\PaymentRow $payment_row
 */
class ReferenceDetail extends Entity
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
