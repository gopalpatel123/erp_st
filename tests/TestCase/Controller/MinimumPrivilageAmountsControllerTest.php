<?php
namespace App\Test\TestCase\Controller;

use App\Controller\MinimumPrivilageAmountsController;
use Cake\TestSuite\IntegrationTestCase;

/**
 * App\Controller\MinimumPrivilageAmountsController Test Case
 */
class MinimumPrivilageAmountsControllerTest extends IntegrationTestCase
{

    /**
     * Fixtures
     *
     * @var array
     */
    public $fixtures = [
        'app.minimum_privilage_amounts',
        'app.companies',
        'app.states',
        'app.company_users',
        'app.users',
        'app.sales_invoices',
        'app.customers',
        'app.cities',
        'app.ledgers',
        'app.accounting_groups',
        'app.nature_of_groups',
        'app.suppliers',
        'app.reference_details',
        'app.receipts',
        'app.receipt_rows',
        'app.ref_receipts',
        'app.accounting_entries',
        'app.purchase_vouchers',
        'app.purchase_voucher_rows',
        'app.ref_purchase_vouchers',
        'app.purchase_invoices',
        'app.supplier_ledgers',
        'app.purchase_ledgers',
        'app.purchase_invoice_rows',
        'app.items',
        'app.units',
        'app.stock_groups',
        'app.item_ledgers',
        'app.stock_journals',
        'app.inwards',
        'app.outwards',
        'app.sale_returns',
        'app.locations',
        'app.grns',
        'app.grn_rows',
        'app.second_tamp_grn_records',
        'app.sizes',
        'app.shades',
        'app.gst_figures',
        'app.first_gst_figures',
        'app.second_gst_figures',
        'app.party_ledgers',
        'app.sales_ledgers',
        'app.sale_return_rows',
        'app.locations',
        'app.intra_location_stock_transfer_vouchers',
        'app.intra_location_stock_transfer_voucher_rows',
        'app.transfer_from_locations',
        'app.transfer_to_locations',
        'app.purchase_returns',
        'app.purchase_return_rows',
        'app.sales_vouchers',
        'app.sales_voucher_rows',
        'app.ref_sales_vouchers',
        'app.journal_vouchers',
        'app.journal_voucher_rows',
        'app.ref_journal_vouchers',
        'app.contra_vouchers',
        'app.contra_voucher_rows',
        'app.payments',
        'app.payment_rows',
        'app.ref_payments',
        'app.credit_notes',
        'app.credit_note_rows',
        'app.ref_credit_notes',
        'app.debit_notes',
        'app.debit_note_rows',
        'app.ref_debit_notes',
        'app.sales_invoice_rows',
        'app.output_cgst_ledgers',
        'app.output_sgst_ledgers',
        'app.output_igst_ledgers',
        'app.sales_invoice_rows_inner',
        'app.user_rights',
        'app.pages',
        'app.financial_years',
        'app.first_tamp_grn_records'
    ];

    /**
     * Test index method
     *
     * @return void
     */
    public function testIndex()
    {
        $this->markTestIncomplete('Not implemented yet.');
    }

    /**
     * Test view method
     *
     * @return void
     */
    public function testView()
    {
        $this->markTestIncomplete('Not implemented yet.');
    }

    /**
     * Test add method
     *
     * @return void
     */
    public function testAdd()
    {
        $this->markTestIncomplete('Not implemented yet.');
    }

    /**
     * Test edit method
     *
     * @return void
     */
    public function testEdit()
    {
        $this->markTestIncomplete('Not implemented yet.');
    }

    /**
     * Test delete method
     *
     * @return void
     */
    public function testDelete()
    {
        $this->markTestIncomplete('Not implemented yet.');
    }
}
