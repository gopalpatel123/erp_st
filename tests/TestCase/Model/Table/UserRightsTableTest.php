<?php
namespace App\Test\TestCase\Model\Table;

use App\Model\Table\UserRightsTable;
use Cake\ORM\TableRegistry;
use Cake\TestSuite\TestCase;

/**
 * App\Model\Table\UserRightsTable Test Case
 */
class UserRightsTableTest extends TestCase
{

    /**
     * Test subject
     *
     * @var \App\Model\Table\UserRightsTable
     */
    public $UserRights;

    /**
     * Fixtures
     *
     * @var array
     */
    public $fixtures = [
        'app.user_rights',
        'app.pages',
        'app.users',
        'app.company_users',
        'app.companies',
        'app.states',
        'app.financial_years',
        'app.item_ledgers',
        'app.items',
        'app.units',
        'app.stock_groups',
        'app.sizes',
        'app.shades',
        'app.gst_figures',
        'app.ledgers',
        'app.accounting_groups',
        'app.nature_of_groups',
        'app.suppliers',
        'app.cities',
        'app.customers',
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
        'app.grns',
        'app.grn_rows',
        'app.second_tamp_grn_records',
        'app.first_gst_figures',
        'app.second_gst_figures',
        'app.purchase_returns',
        'app.purchase_return_rows',
        'app.sales_invoices',
        'app.sales_invoice_rows',
        'app.output_cgst_ledgers',
        'app.output_sgst_ledgers',
        'app.output_igst_ledgers',
        'app.party_ledgers',
        'app.sales_ledgers',
        'app.sale_returns',
        'app.locations',
        'app.sale_return_rows',
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
        'app.stock_journals',
        'app.inwards',
        'app.outwards',
        'app.locations',
        'app.intra_location_stock_transfer_vouchers',
        'app.intra_location_stock_transfer_voucher_rows',
        'app.transfer_from_locations',
        'app.transfer_to_locations',
        'app.first_tamp_grn_records'
    ];

    /**
     * setUp method
     *
     * @return void
     */
    public function setUp()
    {
        parent::setUp();
        $config = TableRegistry::exists('UserRights') ? [] : ['className' => UserRightsTable::class];
        $this->UserRights = TableRegistry::get('UserRights', $config);
    }

    /**
     * tearDown method
     *
     * @return void
     */
    public function tearDown()
    {
        unset($this->UserRights);

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
