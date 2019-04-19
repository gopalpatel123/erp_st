<?php
namespace App\Test\TestCase\Model\Table;

use App\Model\Table\StockGroupsTable;
use Cake\ORM\TableRegistry;
use Cake\TestSuite\TestCase;

/**
 * App\Model\Table\StockGroupsTable Test Case
 */
class StockGroupsTableTest extends TestCase
{

    /**
     * Test subject
     *
     * @var \App\Model\Table\StockGroupsTable
     */
    public $StockGroups;

    /**
     * Fixtures
     *
     * @var array
     */
    public $fixtures = [
        'app.stock_groups',
        'app.companies',
        'app.states',
        'app.users',
        'app.items'
    ];

    /**
     * setUp method
     *
     * @return void
     */
    public function setUp()
    {
        parent::setUp();
        $config = TableRegistry::exists('StockGroups') ? [] : ['className' => StockGroupsTable::class];
        $this->StockGroups = TableRegistry::get('StockGroups', $config);
    }

    /**
     * tearDown method
     *
     * @return void
     */
    public function tearDown()
    {
        unset($this->StockGroups);

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
