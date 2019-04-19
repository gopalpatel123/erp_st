<?php
namespace App\Test\TestCase\Model\Table;

use App\Model\Table\StockJournalsTable;
use Cake\ORM\TableRegistry;
use Cake\TestSuite\TestCase;

/**
 * App\Model\Table\StockJournalsTable Test Case
 */
class StockJournalsTableTest extends TestCase
{

    /**
     * Test subject
     *
     * @var \App\Model\Table\StockJournalsTable
     */
    public $StockJournals;

    /**
     * Fixtures
     *
     * @var array
     */
    public $fixtures = [
        'app.stock_journals',
        'app.companies',
        'app.states',
        'app.company_users',
        'app.users',
        'app.financial_years',
        'app.inwards',
        'app.outwards'
    ];

    /**
     * setUp method
     *
     * @return void
     */
    public function setUp()
    {
        parent::setUp();
        $config = TableRegistry::exists('StockJournals') ? [] : ['className' => StockJournalsTable::class];
        $this->StockJournals = TableRegistry::get('StockJournals', $config);
    }

    /**
     * tearDown method
     *
     * @return void
     */
    public function tearDown()
    {
        unset($this->StockJournals);

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
