<?php
namespace App\Controller;

use App\Controller\AppController;

/**
 * SalesVoucherRows Controller
 *
 * @property \App\Model\Table\SalesVoucherRowsTable $SalesVoucherRows
 *
 * @method \App\Model\Entity\SalesVoucherRow[] paginate($object = null, array $settings = [])
 */
class SalesVoucherRowsController extends AppController
{

    /**
     * Index method
     *
     * @return \Cake\Http\Response|void
     */
    public function index()
    {
        $this->paginate = [
            'contain' => ['SalesVouchers', 'Ledgers']
        ];
        $salesVoucherRows = $this->paginate($this->SalesVoucherRows);

        $this->set(compact('salesVoucherRows'));
        $this->set('_serialize', ['salesVoucherRows']);
    }

    /**
     * View method
     *
     * @param string|null $id Sales Voucher Row id.
     * @return \Cake\Http\Response|void
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function view($id = null)
    {
        $salesVoucherRow = $this->SalesVoucherRows->get($id, [
            'contain' => ['SalesVouchers', 'Ledgers', 'ReferenceDetails']
        ]);

        $this->set('salesVoucherRow', $salesVoucherRow);
        $this->set('_serialize', ['salesVoucherRow']);
    }

    /**
     * Add method
     *
     * @return \Cake\Http\Response|null Redirects on successful add, renders view otherwise.
     */
    public function add()
    {
        $salesVoucherRow = $this->SalesVoucherRows->newEntity();
        if ($this->request->is('post')) {
            $salesVoucherRow = $this->SalesVoucherRows->patchEntity($salesVoucherRow, $this->request->getData());
            if ($this->SalesVoucherRows->save($salesVoucherRow)) {
                $this->Flash->success(__('The sales voucher row has been saved.'));

                return $this->redirect(['action' => 'index']);
            }
            $this->Flash->error(__('The sales voucher row could not be saved. Please, try again.'));
        }
        $salesVouchers = $this->SalesVoucherRows->SalesVouchers->find('list', ['limit' => 200]);
        $ledgers = $this->SalesVoucherRows->Ledgers->find('list', ['limit' => 200]);
        $this->set(compact('salesVoucherRow', 'salesVouchers', 'ledgers'));
        $this->set('_serialize', ['salesVoucherRow']);
    }

    /**
     * Edit method
     *
     * @param string|null $id Sales Voucher Row id.
     * @return \Cake\Http\Response|null Redirects on successful edit, renders view otherwise.
     * @throws \Cake\Network\Exception\NotFoundException When record not found.
     */
    public function edit($id = null)
    {
        $salesVoucherRow = $this->SalesVoucherRows->get($id, [
            'contain' => []
        ]);
        if ($this->request->is(['patch', 'post', 'put'])) {
            $salesVoucherRow = $this->SalesVoucherRows->patchEntity($salesVoucherRow, $this->request->getData());
            if ($this->SalesVoucherRows->save($salesVoucherRow)) {
                $this->Flash->success(__('The sales voucher row has been saved.'));

                return $this->redirect(['action' => 'index']);
            }
            $this->Flash->error(__('The sales voucher row could not be saved. Please, try again.'));
        }
        $salesVouchers = $this->SalesVoucherRows->SalesVouchers->find('list', ['limit' => 200]);
        $ledgers = $this->SalesVoucherRows->Ledgers->find('list', ['limit' => 200]);
        $this->set(compact('salesVoucherRow', 'salesVouchers', 'ledgers'));
        $this->set('_serialize', ['salesVoucherRow']);
    }

    /**
     * Delete method
     *
     * @param string|null $id Sales Voucher Row id.
     * @return \Cake\Http\Response|null Redirects to index.
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function delete($id = null)
    {
        $this->request->allowMethod(['post', 'delete']);
        $salesVoucherRow = $this->SalesVoucherRows->get($id);
        if ($this->SalesVoucherRows->delete($salesVoucherRow)) {
            $this->Flash->success(__('The sales voucher row has been deleted.'));
        } else {
            $this->Flash->error(__('The sales voucher row could not be deleted. Please, try again.'));
        }

        return $this->redirect(['action' => 'index']);
    }
}
