<?php
namespace App\Test\TestCase\Model\Table;

use App\Model\Table\AccountingEntriesTable;
use Cake\ORM\TableRegistry;
use Cake\TestSuite\TestCase;

/**
 * App\Model\Table\AccountingEntriesTable Test Case
 */
class AccountingEntriesTableTest extends TestCase
{

    /**
     * Test subject
     *
     * @var \App\Model\Table\AccountingEntriesTable
     */
    public $AccountingEntries;

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
        'app.financial_years',
        'app.suppliers',
        'app.customers'
    ];

    /**
     * setUp method
     *
     * @return void
     */
    public function setUp()
    {
        parent::setUp();
        $config = TableRegistry::exists('AccountingEntries') ? [] : ['className' => AccountingEntriesTable::class];
        $this->AccountingEntries = TableRegistry::get('AccountingEntries', $config);
    }

    /**
     * tearDown method
     *
     * @return void
     */
    public function tearDown()
    {
        unset($this->AccountingEntries);

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
