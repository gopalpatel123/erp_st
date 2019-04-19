<?php
namespace App\Test\TestCase\Controller;

use App\Controller\LocationsController;
use Cake\TestSuite\IntegrationTestCase;

/**
 * App\Controller\LocationsController Test Case
 */
class LocationsControllerTest extends IntegrationTestCase
{

    /**
     * Fixtures
     *
     * @var array
     */
    public $fixtures = [
        'app.locations',
        'app.companies',
        'app.states',
        'app.company_users',
        'app.users',
        'app.financial_years',
        'app.item_ledgers',
        'app.items',
        'app.units',
        'app.stock_groups',
        'app.sizes',
        'app.shades',
        'app.gst_figures',
        'app.input_cgst_ledger',
        'app.accounting_groups',
        'app.nature_of_groups',
        'app.ledgers',
        'app.suppliers',
        'app.customers',
        'app.accounting_entries',
        'app.purchase_vouchers',
        'app.purchase_voucher_rows',
        'app.input_sgst_ledger',
        'app.input_igst_ledger',
        'app.output_cgst_ledger',
        'app.output_sgst_ledger',
        'app.output_igst_ledger',
        'app.stock_journals',
        'app.inwards',
        'app.outwards',
        'app.grns',
        'app.grn_rows'
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
