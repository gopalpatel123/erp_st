<?php
namespace App\Test\TestCase\Controller;

use App\Controller\LedgersController;
use Cake\TestSuite\IntegrationTestCase;

/**
 * App\Controller\LedgersController Test Case
 */
class LedgersControllerTest extends IntegrationTestCase
{

    /**
     * Fixtures
     *
     * @var array
     */
    public $fixtures = [
        'app.ledgers',
        'app.accounting_groups',
        'app.companies',
        'app.states',
        'app.company_users',
        'app.users',
        'app.suppliers',
        'app.customers',
        'app.accounting_entries'
    ];

    /**
     * Test index method
     *
     * @return void
     */
    public function testIndex()
    {
        $this->markTestIncomplete('Not implemented yet.');
    }

    /**
     * Test view method
     *
     * @return void
     */
    public function testView()
    {
        $this->markTestIncomplete('Not implemented yet.');
    }

    /**
     * Test add method
     *
     * @return void
     */
    public function testAdd()
    {
        $this->markTestIncomplete('Not implemented yet.');
    }

    /**
     * Test edit method
     *
     * @return void
     */
    public function testEdit()
    {
        $this->markTestIncomplete('Not implemented yet.');
    }

    /**
     * Test delete method
     *
     * @return void
     */
    public function testDelete()
    {
        $this->markTestIncomplete('Not implemented yet.');
    }
}
