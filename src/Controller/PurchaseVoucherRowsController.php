<?php
namespace App\Controller;

use App\Controller\AppController;

/**
 * PurchaseVoucherRows Controller
 *
 * @property \App\Model\Table\PurchaseVoucherRowsTable $PurchaseVoucherRows
 *
 * @method \App\Model\Entity\PurchaseVoucherRow[] paginate($object = null, array $settings = [])
 */
class PurchaseVoucherRowsController extends AppController
{

    /**
     * Index method
     *
     * @return \Cake\Http\Response|void
     */
    public function index()
    {
        $this->viewBuilder()->layout('index_layout');
		$company_id=$this->Auth->User('session_company_id');
		$this->paginate = [
            'contain' => ['PurchaseVouchers', 'Ledgers']
        ];
        $purchaseVoucherRows = $this->paginate($this->PurchaseVoucherRows);

        $this->set(compact('purchaseVoucherRows'));
        $this->set('_serialize', ['purchaseVoucherRows']);
    }

    /**
     * View method
     *
     * @param string|null $id Purchase Voucher Row id.
     * @return \Cake\Http\Response|void
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function view($id = null)
    {
        $purchaseVoucherRow = $this->PurchaseVoucherRows->get($id, [
            'contain' => ['PurchaseVouchers', 'Ledgers']
        ]);

        $this->set('purchaseVoucherRow', $purchaseVoucherRow);
        $this->set('_serialize', ['purchaseVoucherRow']);
    }

    /**
     * Add method
     *
     * @return \Cake\Http\Response|null Redirects on successful add, renders view otherwise.
     */
    public function add()
    {
        $this->viewBuilder()->layout('index_layout');
		$company_id=$this->Auth->User('session_company_id');
		$purchaseVoucherRow = $this->PurchaseVoucherRows->newEntity();
        if ($this->request->is('post')) {
            $purchaseVoucherRow = $this->PurchaseVoucherRows->patchEntity($purchaseVoucherRow, $this->request->getData());
            if ($this->PurchaseVoucherRows->save($purchaseVoucherRow)) {
                $this->Flash->success(__('The purchase voucher row has been saved.'));

                return $this->redirect(['action' => 'index']);
            }
            $this->Flash->error(__('The purchase voucher row could not be saved. Please, try again.'));
        }
        $purchaseVouchers = $this->PurchaseVoucherRows->PurchaseVouchers->find('list')->where(['company_id'=>$company_id]);
        $ledgers = $this->PurchaseVoucherRows->Ledgers->find('list')->where(['company_id'=>$company_id]);
        $this->set(compact('purchaseVoucherRow', 'purchaseVouchers', 'ledgers'));
        $this->set('_serialize', ['purchaseVoucherRow']);
    }

    /**
     * Edit method
     *
     * @param string|null $id Purchase Voucher Row id.
     * @return \Cake\Http\Response|null Redirects on successful edit, renders view otherwise.
     * @throws \Cake\Network\Exception\NotFoundException When record not found.
     */
    public function edit($id = null)
    {
        $this->viewBuilder()->layout('index_layout');
		$company_id=$this->Auth->User('session_company_id');
		$purchaseVoucherRow = $this->PurchaseVoucherRows->get($id, [
            'contain' => []
        ]);
        if ($this->request->is(['patch', 'post', 'put'])) {
            $purchaseVoucherRow = $this->PurchaseVoucherRows->patchEntity($purchaseVoucherRow, $this->request->getData());
            if ($this->PurchaseVoucherRows->save($purchaseVoucherRow)) {
                $this->Flash->success(__('The purchase voucher row has been saved.'));

                return $this->redirect(['action' => 'index']);
            }
            $this->Flash->error(__('The purchase voucher row could not be saved. Please, try again.'));
        }
        $purchaseVouchers = $this->PurchaseVoucherRows->PurchaseVouchers->find('list')->where(['company_id'=>$company_id]);
        $ledgers = $this->PurchaseVoucherRows->Ledgers->find('list')->where(['company_id'=>$company_id]);
        $this->set(compact('purchaseVoucherRow', 'purchaseVouchers', 'ledgers'));
        $this->set('_serialize', ['purchaseVoucherRow']);
    }

    /**
     * Delete method
     *
     * @param string|null $id Purchase Voucher Row id.
     * @return \Cake\Http\Response|null Redirects to index.
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function delete($id = null)
    {
        $this->request->allowMethod(['post', 'delete']);
        $purchaseVoucherRow = $this->PurchaseVoucherRows->get($id);
        if ($this->PurchaseVoucherRows->delete($purchaseVoucherRow)) {
            $this->Flash->success(__('The purchase voucher row has been deleted.'));
        } else {
            $this->Flash->error(__('The purchase voucher row could not be deleted. Please, try again.'));
        }

        return $this->redirect(['action' => 'index']);
    }
}
