<?php
namespace App\Test\TestCase\Model\Table;

use App\Model\Table\ShadesTable;
use Cake\ORM\TableRegistry;
use Cake\TestSuite\TestCase;

/**
 * App\Model\Table\ShadesTable Test Case
 */
class ShadesTableTest extends TestCase
{

    /**
     * Test subject
     *
     * @var \App\Model\Table\ShadesTable
     */
    public $Shades;

    /**
     * Fixtures
     *
     * @var array
     */
    public $fixtures = [
        'app.shades',
        'app.companies',
        'app.states',
        'app.company_users',
        'app.users',
        'app.financial_years',
        'app.items',
        'app.units',
        'app.stock_groups',
        'app.item_ledgers',
        'app.stock_journals',
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
        $config = TableRegistry::exists('Shades') ? [] : ['className' => ShadesTable::class];
        $this->Shades = TableRegistry::get('Shades', $config);
    }

    /**
     * tearDown method
     *
     * @return void
     */
    public function tearDown()
    {
        unset($this->Shades);

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
