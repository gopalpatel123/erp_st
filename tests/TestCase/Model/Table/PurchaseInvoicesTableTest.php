<?php
namespace App\Test\TestCase\Model\Table;

use App\Model\Table\PurchaseInvoicesTable;
use Cake\ORM\TableRegistry;
use Cake\TestSuite\TestCase;

/**
 * App\Model\Table\PurchaseInvoicesTable Test Case
 */
class PurchaseInvoicesTableTest extends TestCase
{

    /**
     * Test subject
     *
     * @var \App\Model\Table\PurchaseInvoicesTable
     */
    public $PurchaseInvoices;

    /**
     * Fixtures
     *
     * @var array
     */
    public $fixtures = [
        'app.purchase_invoices',
        'app.companies',
        'app.states',
        'app.company_users',
        'app.users',
        'app.locations',
        'app.grns',
        'app.supplier_ledgers',
        'app.accounting_groups',
        'app.nature_of_groups',
        'app.ledgers',
        'app.suppliers',
        'app.customers',
        'app.cities',
        'app.accounting_entries',
        'app.purchase_vouchers',
        'app.purchase_voucher_rows',
        'app.sales_invoices',
        'app.gst_figures',
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
        'app.first_gst_figures',
        'app.second_gst_figures',
        'app.sales_invoice_rows',
        'app.output_cgst_ledgers',
        'app.output_sgst_ledgers',
        'app.output_igst_ledgers',
        'app.party_ledgers',
        'app.sales_ledgers',
        'app.sale_returns',
        'app.sale_return_rows',
        'app.sales_vouchers',
        'app.sales_voucher_rows',
        'app.reference_details',
        'app.receipts',
        'app.receipt_rows',
        'app.payments',
        'app.payment_rows',
        'app.credit_note_rows',
        'app.credit_notes',
        'app.grn_rows',
        'app.second_tamp_grn_records',
        'app.financial_years',
        'app.first_tamp_grn_records',
        'app.purchase_invoice_rows'
    ];

    /**
     * setUp method
     *
     * @return void
     */
    public function setUp()
    {
        parent::setUp();
        $config = TableRegistry::exists('PurchaseInvoices') ? [] : ['className' => PurchaseInvoicesTable::class];
        $this->PurchaseInvoices = TableRegistry::get('PurchaseInvoices', $config);
    }

    /**
     * tearDown method
     *
     * @return void
     */
    public function tearDown()
    {
        unset($this->PurchaseInvoices);

        parent::tearDown();
    }

    /**
     * Test initialize method
     *
     * @return void
     */
    public function testInitialize()
    {
        $this->markTestIncomplete('Not implemented yet.');
    }

    /**
     * Test validationDefault method
     *
     * @return void
     */
    public function testValidationDefault()
    {
        $this->markTestIncomplete('Not implemented yet.');
    }

    /**
     * Test buildRules method
     *
     * @return void
     */
    public function testBuildRules()
    {
        $this->markTestIncomplete('Not implemented yet.');
    }
}
