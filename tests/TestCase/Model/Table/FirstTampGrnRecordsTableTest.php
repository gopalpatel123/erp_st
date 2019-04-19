<?php
namespace App\Test\TestCase\Model\Table;

use App\Model\Table\FirstTampGrnRecordsTable;
use Cake\ORM\TableRegistry;
use Cake\TestSuite\TestCase;

/**
 * App\Model\Table\FirstTampGrnRecordsTable Test Case
 */
class FirstTampGrnRecordsTableTest extends TestCase
{

    /**
     * Test subject
     *
     * @var \App\Model\Table\FirstTampGrnRecordsTable
     */
    public $FirstTampGrnRecords;

    /**
     * Fixtures
     *
     * @var array
     */
    public $fixtures = [
        'app.first_tamp_grn_records',
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
        'app.input_cgst_ledger',
        'app.accounting_groups',
        'app.nature_of_groups',
        'app.ledgers',
        'app.suppliers',
        'app.customers',
        'app.accounting_entries',
        'app.purchase_vouchers',
        'app.purchase_voucher_rows',
        'app.input_sgst_ledger',
        'app.input_igst_ledger',
        'app.output_cgst_ledger',
        'app.output_sgst_ledger',
        'app.output_igst_ledger',
        'app.stock_journals',
        'app.inwards',
        'app.outwards',
        'app.locations',
        'app.grns',
        'app.grn_rows'
    ];

    /**
     * setUp method
     *
     * @return void
     */
    public function setUp()
    {
        parent::setUp();
        $config = TableRegistry::exists('FirstTampGrnRecords') ? [] : ['className' => FirstTampGrnRecordsTable::class];
        $this->FirstTampGrnRecords = TableRegistry::get('FirstTampGrnRecords', $config);
    }

    /**
     * tearDown method
     *
     * @return void
     */
    public function tearDown()
    {
        unset($this->FirstTampGrnRecords);

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
