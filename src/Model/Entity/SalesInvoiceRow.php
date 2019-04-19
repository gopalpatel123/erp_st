<?php
namespace App\Model\Entity;

use Cake\ORM\Entity;

/**
 * SalesInvoiceRow Entity
 *
 * @property int $id
 * @property int $sales_invoice_id
 * @property int $item_id
 * @property float $quantity
 * @property float $rate
 * @property float $discount_percentage
 * @property float $taxable_value
 * @property int $gst_figure_id
 * @property int $output_cgst_ledger_id
 * @property int $output_sgst_ledger_id
 * @property int $output_igst_ledger_id
 *
 * @property \App\Model\Entity\SalesInvoice $sales_invoice
 * @property \App\Model\Entity\Item $item
 * @property \App\Model\Entity\GstFigure $gst_figure
 * @property \App\Model\Entity\OutputCgstLedger $output_cgst_ledger
 * @property \App\Model\Entity\OutputSgstLedger $output_sgst_ledger
 * @property \App\Model\Entity\OutputIgstLedger $output_igst_ledger
 */
class SalesInvoiceRow extends Entity
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
