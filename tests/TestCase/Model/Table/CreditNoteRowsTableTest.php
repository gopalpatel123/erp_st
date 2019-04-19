<?php
namespace App\Test\TestCase\Model\Table;

use App\Model\Table\CreditNoteRowsTable;
use Cake\ORM\TableRegistry;
use Cake\TestSuite\TestCase;

/**
 * App\Model\Table\CreditNoteRowsTable Test Case
 */
class CreditNoteRowsTableTest extends TestCase
{

    /**
     * Test subject
     *
     * @var \App\Model\Table\CreditNoteRowsTable
     */
    public $CreditNoteRows;

    /**
     * Fixtures
     *
     * @var array
     */
    public $fixtures = [
        'app.credit_note_rows',
        'app.credit_notes',
        'app.companies',
        'app.states',
        'app.company_users',
        'app.users',
        'app.locations',
        'app.grns',
        'app.grn_rows',
        'app.items',
        'app.units',
        'app.stock_groups',
        'app.item_ledgers',
        'app.stock_journals',
        'app.inwards',
        'app.outwards',
        'app.sizes',
        'app.shades',
        'app.gst_figures',
        'app.ledgers',
        'app.accounting_groups',
        'app.nature_of_groups',
        'app.suppliers',
        'app.customers',
        'app.accounting_entries',
        'app.purchase_vouchers',
        'app.purchase_voucher_rows',
        'app.first_gst_figures',
        'app.second_gst_figures',
        'app.input_cgst_ledger',
        'app.input_sgst_ledger',
        'app.input_igst_ledger',
        'app.output_cgst_ledger',
        'app.output_sgst_ledger',
        'app.output_igst_ledger',
        'app.second_tamp_grn_records',
        'app.financial_years',
        'app.first_tamp_grn_records',
        'app.party_ledgers',
        'app.sales_ledgers'
    ];

    /**
     * setUp method
     *
     * @return void
     */
    public function setUp()
    {
        parent::setUp();
        $config = TableRegistry::exists('CreditNoteRows') ? [] : ['className' => CreditNoteRowsTable::class];
        $this->CreditNoteRows = TableRegistry::get('CreditNoteRows', $config);
    }

    /**
     * tearDown method
     *
     * @return void
     */
    public function tearDown()
    {
        unset($this->CreditNoteRows);

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
