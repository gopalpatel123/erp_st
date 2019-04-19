<?php
namespace App\Model\Entity;

use Cake\ORM\Entity;

/**
 * PurchaseInvoice Entity
 *
 * @property int $id
 * @property int $voucher_no
 * @property int $company_id
 * @property \Cake\I18n\FrozenDate $transaction_date
 * @property int $supplier_ledger_id
 * @property float $amount_before_tax
 * @property float $total_cgst
 * @property float $total_sgst
 * @property float $total_igst
 * @property float $amount_after_tax
 * @property float $round_off
 *
 * @property \App\Model\Entity\Company $company
 * @property \App\Model\Entity\SupplierLedger $supplier_ledger
 * @property \App\Model\Entity\PurchaseInvoiceRow[] $purchase_invoice_rows
 */
class PurchaseInvoice extends Entity
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
