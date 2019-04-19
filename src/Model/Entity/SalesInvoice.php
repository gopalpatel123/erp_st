<?php
namespace App\Model\Entity;

use Cake\ORM\Entity;

/**
 * SalesInvoice Entity
 *
 * @property int $id
 * @property int $voucher_no
 * @property int $company_id
 * @property \Cake\I18n\FrozenDate $transaction_date
 * @property string $cash_or_credit
 * @property int $customer_id
 * @property int $gst_figure_id
 * @property float $amount_before_tax
 * @property float $total_cgst
 * @property float $total_sgst
 * @property float $total_igst
 * @property float $amount_after_tax
 *
 * @property \App\Model\Entity\Company $company
 * @property \App\Model\Entity\Customer $customer
 * @property \App\Model\Entity\GstFigure $gst_figure
 * @property \App\Model\Entity\SalesInvoiceRow[] $sales_invoice_rows
 */
class SalesInvoice extends Entity
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
