<?php
namespace App\Test\TestCase\Model\Table;

use App\Model\Table\FinancialYearsTable;
use Cake\ORM\TableRegistry;
use Cake\TestSuite\TestCase;

/**
 * App\Model\Table\FinancialYearsTable Test Case
 */
class FinancialYearsTableTest extends TestCase
{

    /**
     * Test subject
     *
     * @var \App\Model\Table\FinancialYearsTable
     */
    public $FinancialYears;

    /**
     * Fixtures
     *
     * @var array
     */
    public $fixtures = [
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
        $config = TableRegistry::exists('FinancialYears') ? [] : ['className' => FinancialYearsTable::class];
        $this->FinancialYears = TableRegistry::get('FinancialYears', $config);
    }

    /**
     * tearDown method
     *
     * @return void
     */
    public function tearDown()
    {
        unset($this->FinancialYears);

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
}
