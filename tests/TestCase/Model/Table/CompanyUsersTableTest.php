<?php
namespace App\Test\TestCase\Model\Table;

use App\Model\Table\CompanyUsersTable;
use Cake\ORM\TableRegistry;
use Cake\TestSuite\TestCase;

/**
 * App\Model\Table\CompanyUsersTable Test Case
 */
class CompanyUsersTableTest extends TestCase
{

    /**
     * Test subject
     *
     * @var \App\Model\Table\CompanyUsersTable
     */
    public $CompanyUsers;

    /**
     * Fixtures
     *
     * @var array
     */
    public $fixtures = [
        'app.company_users',
        'app.companies',
        'app.states',
        'app.users'
    ];

    /**
     * setUp method
     *
     * @return void
     */
    public function setUp()
    {
        parent::setUp();
        $config = TableRegistry::exists('CompanyUsers') ? [] : ['className' => CompanyUsersTable::class];
        $this->CompanyUsers = TableRegistry::get('CompanyUsers', $config);
    }

    /**
     * tearDown method
     *
     * @return void
     */
    public function tearDown()
    {
        unset($this->CompanyUsers);

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
