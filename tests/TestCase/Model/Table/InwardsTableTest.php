<?php
namespace App\Test\TestCase\Model\Table;

use App\Model\Table\InwardsTable;
use Cake\ORM\TableRegistry;
use Cake\TestSuite\TestCase;

/**
 * App\Model\Table\InwardsTable Test Case
 */
class InwardsTableTest extends TestCase
{

    /**
     * Test subject
     *
     * @var \App\Model\Table\InwardsTable
     */
    public $Inwards;

    /**
     * Fixtures
     *
     * @var array
     */
    public $fixtures = [
        'app.inwards',
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
        $config = TableRegistry::exists('Inwards') ? [] : ['className' => InwardsTable::class];
        $this->Inwards = TableRegistry::get('Inwards', $config);
    }

    /**
     * tearDown method
     *
     * @return void
     */
    public function tearDown()
    {
        unset($this->Inwards);

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
