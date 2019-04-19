<?php
namespace App\Test\TestCase\Controller;

use App\Controller\AccountingEntriesController;
use Cake\TestSuite\IntegrationTestCase;

/**
 * App\Controller\AccountingEntriesController Test Case
 */
class AccountingEntriesControllerTest extends IntegrationTestCase
{

    /**
     * Fixtures
     *
     * @var array
     */
    public $fixtures = [
        'app.accounting_entries',
        'app.ledgers',
        'app.accounting_groups',
        'app.nature_of_groups',
        'app.companies',
        'app.states',
        'app.company_users',
        'app.users',
        'app.locations',
        'app.grns',
        'app.supplier_ledgers',
        'app.suppliers',
        'app.customers',
        'app.cities',
        'app.grn_rows',
        'app.items',
        'app.units',
        'app.stock_groups',
        'app.item_ledgers',
        'app.stock_journals',
        'app.inwards',
        'app.outwards',
        'app.intra_location_stock_transfer_vouchers',
        'app.intra_location_stock_transfer_voucher_rows',
        'app.transfer_from_locations',
        'app.transfer_to_locations',
        'app.sizes',
        'app.shades',
        'app.gst_figures',
        'app.first_gst_figures',
        'app.second_gst_figures',
        'app.second_tamp_grn_records',
        'app.financial_years',
        'app.first_tamp_grn_records',
        'app.purchase_vouchers',
        'app.purchase_voucher_rows',
        'app.reference_details',
        'app.receipts',
        'app.receipt_rows',
        'app.payments',
        'app.payment_rows',
        'app.credit_note_rows',
        'app.credit_notes',
        'app.sales_voucher_rows',
        'app.sales_vouchers',
        'app.journal_voucher_rows',
        'app.journal_vouchers',
        'app.sales_invoices',
        'app.sales_invoice_rows',
        'app.output_cgst_ledgers',
        'app.output_sgst_ledgers',
        'app.output_igst_ledgers',
        'app.party_ledgers',
        'app.sales_ledgers',
        'app.sale_returns',
        'app.sale_return_rows'
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
