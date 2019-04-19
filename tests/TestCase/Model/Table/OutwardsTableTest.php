<?php
namespace App\Test\TestCase\Model\Table;

use App\Model\Table\OutwardsTable;
use Cake\ORM\TableRegistry;
use Cake\TestSuite\TestCase;

/**
 * App\Model\Table\OutwardsTable Test Case
 */
class OutwardsTableTest extends TestCase
{

    /**
     * Test subject
     *
     * @var \App\Model\Table\OutwardsTable
     */
    public $Outwards;

    /**
     * Fixtures
     *
     * @var array
     */
    public $fixtures = [
        'app.outwards',
        'app.items',
        'app.units',
        'app.stock_groups',
        'app.companies',
        'app.states',
        'app.company_users',
        'app.users',
        'app.financial_years',
        'app.item_ledgers',
        'app.stock_journals',
        'app.inwards'
    ];

    /**
     * setUp method
     *
     * @return void
     */
    public function setUp()
    {
        parent::setUp();
        $config = TableRegistry::exists('Outwards') ? [] : ['className' => OutwardsTable::class];
        $this->Outwards = TableRegistry::get('Outwards', $config);
    }

    /**
     * tearDown method
     *
     * @return void
     */
    public function tearDown()
    {
        unset($this->Outwards);

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
