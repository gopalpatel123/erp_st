<?php
namespace App\Test\TestCase\Model\Table;

use App\Model\Table\SalesInvoiceRowsTable;
use Cake\ORM\TableRegistry;
use Cake\TestSuite\TestCase;

/**
 * App\Model\Table\SalesInvoiceRowsTable Test Case
 */
class SalesInvoiceRowsTableTest extends TestCase
{

    /**
     * Test subject
     *
     * @var \App\Model\Table\SalesInvoiceRowsTable
     */
    public $SalesInvoiceRows;

    /**
     * Fixtures
     *
     * @var array
     */
    public $fixtures = [
        'app.sales_invoice_rows',
        'app.sales_invoices',
        'app.companies',
        'app.states',
        'app.company_users',
        'app.users',
        'app.financial_years',
        'app.customers',
        'app.ledgers',
        'app.accounting_groups',
        'app.nature_of_groups',
        'app.suppliers',
        'app.accounting_entries',
        'app.purchase_vouchers',
        'app.purchase_voucher_rows',
        'app.gst_figures',
        'app.items',
        'app.units',
        'app.stock_groups',
        'app.item_ledgers',
        'app.stock_journals',
        'app.inwards',
        'app.outwards',
        'app.sizes',
        'app.shades',
        'app.output_cgst_ledgers',
        'app.output_sgst_ledgers',
        'app.output_igst_ledgers'
    ];

    /**
     * setUp method
     *
     * @return void
     */
    public function setUp()
    {
        parent::setUp();
        $config = TableRegistry::exists('SalesInvoiceRows') ? [] : ['className' => SalesInvoiceRowsTable::class];
        $this->SalesInvoiceRows = TableRegistry::get('SalesInvoiceRows', $config);
    }

    /**
     * tearDown method
     *
     * @return void
     */
    public function tearDown()
    {
        unset($this->SalesInvoiceRows);

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
