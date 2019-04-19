<?php
namespace App\Model\Entity;

use Cake\ORM\Entity;

/**
 * PurchaseReturn Entity
 *
 * @property int $id
 * @property int $purchase_invoice_id
 * @property string $voucher_no
 * @property int $company_id
 * @property \Cake\I18n\FrozenDate $transaction_date
 * @property float $total_amount
 *
 * @property \App\Model\Entity\PurchaseInvoice $purchase_invoice
 * @property \App\Model\Entity\Company $company
 * @property \App\Model\Entity\PurchaseReturnRow[] $purchase_return_rows
 */
class PurchaseReturn extends Entity
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
