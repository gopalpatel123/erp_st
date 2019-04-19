<?php
namespace App\Controller;

use App\Controller\AppController;

/**
 * IntraLocationStockTransferVoucherRows Controller
 *
 * @property \App\Model\Table\IntraLocationStockTransferVoucherRowsTable $IntraLocationStockTransferVoucherRows
 *
 * @method \App\Model\Entity\IntraLocationStockTransferVoucherRow[] paginate($object = null, array $settings = [])
 */
class IntraLocationStockTransferVoucherRowsController extends AppController
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
            'contain' => ['IntraLocationStockTransferVouchers', 'Items']
        ];
        $intraLocationStockTransferVoucherRows = $this->paginate($this->IntraLocationStockTransferVoucherRows);

        $this->set(compact('intraLocationStockTransferVoucherRows'));
        $this->set('_serialize', ['intraLocationStockTransferVoucherRows']);
    }

    /**
     * View method
     *
     * @param string|null $id Intra Location Stock Transfer Voucher Row id.
     * @return \Cake\Http\Response|void
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function view($id = null)
    {
        $intraLocationStockTransferVoucherRow = $this->IntraLocationStockTransferVoucherRows->get($id, [
            'contain' => ['IntraLocationStockTransferVouchers', 'Items']
        ]);

        $this->set('intraLocationStockTransferVoucherRow', $intraLocationStockTransferVoucherRow);
        $this->set('_serialize', ['intraLocationStockTransferVoucherRow']);
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
		$intraLocationStockTransferVoucherRow = $this->IntraLocationStockTransferVoucherRows->newEntity();
        if ($this->request->is('post')) {
            $intraLocationStockTransferVoucherRow = $this->IntraLocationStockTransferVoucherRows->patchEntity($intraLocationStockTransferVoucherRow, $this->request->getData());
            if ($this->IntraLocationStockTransferVoucherRows->save($intraLocationStockTransferVoucherRow)) {
                $this->Flash->success(__('The intra location stock transfer voucher row has been saved.'));

                return $this->redirect(['action' => 'index']);
            }
            $this->Flash->error(__('The intra location stock transfer voucher row could not be saved. Please, try again.'));
        }
        $intraLocationStockTransferVouchers = $this->IntraLocationStockTransferVoucherRows->IntraLocationStockTransferVouchers->find('list')->where(['company_id'=>$company_id]);
        $items = $this->IntraLocationStockTransferVoucherRows->Items->find('list')->where(['company_id'=>$company_id]);
        $this->set(compact('intraLocationStockTransferVoucherRow', 'intraLocationStockTransferVouchers', 'items'));
        $this->set('_serialize', ['intraLocationStockTransferVoucherRow']);
    }

    /**
     * Edit method
     *
     * @param string|null $id Intra Location Stock Transfer Voucher Row id.
     * @return \Cake\Http\Response|null Redirects on successful edit, renders view otherwise.
     * @throws \Cake\Network\Exception\NotFoundException When record not found.
     */
    public function edit($id = null)
    {
        $this->viewBuilder()->layout('index_layout');
		$company_id=$this->Auth->User('session_company_id');
		$intraLocationStockTransferVoucherRow = $this->IntraLocationStockTransferVoucherRows->get($id, [
            'contain' => []
        ]);
        if ($this->request->is(['patch', 'post', 'put'])) {
            $intraLocationStockTransferVoucherRow = $this->IntraLocationStockTransferVoucherRows->patchEntity($intraLocationStockTransferVoucherRow, $this->request->getData());
            if ($this->IntraLocationStockTransferVoucherRows->save($intraLocationStockTransferVoucherRow)) {
                $this->Flash->success(__('The intra location stock transfer voucher row has been saved.'));

                return $this->redirect(['action' => 'index']);
            }
            $this->Flash->error(__('The intra location stock transfer voucher row could not be saved. Please, try again.'));
        }
        $intraLocationStockTransferVouchers = $this->IntraLocationStockTransferVoucherRows->IntraLocationStockTransferVouchers->find('list')->where(['company_id'=>$company_id]);
        $items = $this->IntraLocationStockTransferVoucherRows->Items->find('list')->where(['company_id'=>$company_id]);
        $this->set(compact('intraLocationStockTransferVoucherRow', 'intraLocationStockTransferVouchers', 'items'));
        $this->set('_serialize', ['intraLocationStockTransferVoucherRow']);
    }

    /**
     * Delete method
     *
     * @param string|null $id Intra Location Stock Transfer Voucher Row id.
     * @return \Cake\Http\Response|null Redirects to index.
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function delete($id = null)
    {
        $this->request->allowMethod(['post', 'delete']);
        $intraLocationStockTransferVoucherRow = $this->IntraLocationStockTransferVoucherRows->get($id);
        if ($this->IntraLocationStockTransferVoucherRows->delete($intraLocationStockTransferVoucherRow)) {
            $this->Flash->success(__('The intra location stock transfer voucher row has been deleted.'));
        } else {
            $this->Flash->error(__('The intra location stock transfer voucher row could not be deleted. Please, try again.'));
        }

        return $this->redirect(['action' => 'index']);
    }
}
