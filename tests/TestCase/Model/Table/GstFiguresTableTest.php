<?php
namespace App\Test\TestCase\Model\Table;

use App\Model\Table\GstFiguresTable;
use Cake\ORM\TableRegistry;
use Cake\TestSuite\TestCase;

/**
 * App\Model\Table\GstFiguresTable Test Case
 */
class GstFiguresTableTest extends TestCase
{

    /**
     * Test subject
     *
     * @var \App\Model\Table\GstFiguresTable
     */
    public $GstFigures;

    /**
     * Fixtures
     *
     * @var array
     */
    public $fixtures = [
        'app.gst_figures',
        'app.companies',
        'app.states',
        'app.company_users',
        'app.users',
        'app.financial_years'
    ];

    /**
     * setUp method
     *
     * @return void
     */
    public function setUp()
    {
        parent::setUp();
        $config = TableRegistry::exists('GstFigures') ? [] : ['className' => GstFiguresTable::class];
        $this->GstFigures = TableRegistry::get('GstFigures', $config);
    }

    /**
     * tearDown method
     *
     * @return void
     */
    public function tearDown()
    {
        unset($this->GstFigures);

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
