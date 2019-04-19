<?php
namespace App\Model\Entity;

use Cake\ORM\Entity;

/**
 * SaleReturn Entity
 *
 * @property int $id
 * @property int $voucher_no
 * @property int $company_id
 * @property \Cake\I18n\FrozenDate $transaction_date
 * @property int $customer_id
 * @property float $amount_before_tax
 * @property float $total_cgst
 * @property float $total_sgst
 * @property float $total_igst
 * @property float $amount_after_tax
 * @property float $round_off
 * @property int $sales_ledger_id
 * @property int $party_ledger_id
 * @property int $location_id
 * @property int $sales_invoice_id
 *
 * @property \App\Model\Entity\Company $company
 * @property \App\Model\Entity\Customer $customer
 * @property \App\Model\Entity\SalesLedger $sales_ledger
 * @property \App\Model\Entity\PartyLedger $party_ledger
 * @property \App\Model\Entity\Location $location
 * @property \App\Model\Entity\SalesInvoice $sales_invoice
 * @property \App\Model\Entity\SaleReturnRow[] $sale_return_rows
 */
class SaleReturn extends Entity
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
