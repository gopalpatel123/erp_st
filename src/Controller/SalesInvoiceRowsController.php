<?php
namespace App\Controller;

use App\Controller\AppController;

/**
 * SalesInvoiceRows Controller
 *
 * @property \App\Model\Table\SalesInvoiceRowsTable $SalesInvoiceRows
 *
 * @method \App\Model\Entity\SalesInvoiceRow[] paginate($object = null, array $settings = [])
 */
class SalesInvoiceRowsController extends AppController
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
            'contain' => ['SalesInvoices', 'Items', 'GstFigures', 'OutputCgstLedgers', 'OutputSgstLedgers', 'OutputIgstLedgers']
        ];
        $salesInvoiceRows = $this->paginate($this->SalesInvoiceRows);

        $this->set(compact('salesInvoiceRows'));
        $this->set('_serialize', ['salesInvoiceRows']);
    }

    /**
     * View method
     *
     * @param string|null $id Sales Invoice Row id.
     * @return \Cake\Http\Response|void
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function view($id = null)
    {
        $salesInvoiceRow = $this->SalesInvoiceRows->get($id, [
            'contain' => ['SalesInvoices', 'Items', 'GstFigures', 'OutputCgstLedgers', 'OutputSgstLedgers', 'OutputIgstLedgers']
        ]);

        $this->set('salesInvoiceRow', $salesInvoiceRow);
        $this->set('_serialize', ['salesInvoiceRow']);
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
		$salesInvoiceRow = $this->SalesInvoiceRows->newEntity();
        if ($this->request->is('post')) {
            $salesInvoiceRow = $this->SalesInvoiceRows->patchEntity($salesInvoiceRow, $this->request->getData());
            if ($this->SalesInvoiceRows->save($salesInvoiceRow)) {
                $this->Flash->success(__('The sales invoice row has been saved.'));

                return $this->redirect(['action' => 'index']);
            }
            $this->Flash->error(__('The sales invoice row could not be saved. Please, try again.'));
        }
        $salesInvoices = $this->SalesInvoiceRows->SalesInvoices->find('list')->where(['company_id'=>$company_id]);
        $items = $this->SalesInvoiceRows->Items->find('list')->where(['company_id'=>$company_id]);
        $gstFigures = $this->SalesInvoiceRows->GstFigures->find('list')->where(['company_id'=>$company_id]);
        $outputCgstLedgers = $this->SalesInvoiceRows->OutputCgstLedgers->find('list')->where(['company_id'=>$company_id]);
        $outputSgstLedgers = $this->SalesInvoiceRows->OutputSgstLedgers->find('list')->where(['company_id'=>$company_id]);
        $outputIgstLedgers = $this->SalesInvoiceRows->OutputIgstLedgers->find('list')->where(['company_id'=>$company_id]);
        $this->set(compact('salesInvoiceRow', 'salesInvoices', 'items', 'gstFigures', 'outputCgstLedgers', 'outputSgstLedgers', 'outputIgstLedgers'));
        $this->set('_serialize', ['salesInvoiceRow']);
    }

    /**
     * Edit method
     *
     * @param string|null $id Sales Invoice Row id.
     * @return \Cake\Http\Response|null Redirects on successful edit, renders view otherwise.
     * @throws \Cake\Network\Exception\NotFoundException When record not found.
     */
    public function edit($id = null)
    {
        $this->viewBuilder()->layout('index_layout');
		$company_id=$this->Auth->User('session_company_id');
		$salesInvoiceRow = $this->SalesInvoiceRows->get($id, [
            'contain' => []
        ]);
        if ($this->request->is(['patch', 'post', 'put'])) {
            $salesInvoiceRow = $this->SalesInvoiceRows->patchEntity($salesInvoiceRow, $this->request->getData());
            if ($this->SalesInvoiceRows->save($salesInvoiceRow)) {
                $this->Flash->success(__('The sales invoice row has been saved.'));

                return $this->redirect(['action' => 'index']);
            }
            $this->Flash->error(__('The sales invoice row could not be saved. Please, try again.'));
        }
        $salesInvoices = $this->SalesInvoiceRows->SalesInvoices->find('list')->where(['company_id'=>$company_id]);
        $items = $this->SalesInvoiceRows->Items->find('list')->where(['company_id'=>$company_id]);
        $gstFigures = $this->SalesInvoiceRows->GstFigures->find('list')->where(['company_id'=>$company_id]);
        $outputCgstLedgers = $this->SalesInvoiceRows->OutputCgstLedgers->find('list')->where(['company_id'=>$company_id]);
        $outputSgstLedgers = $this->SalesInvoiceRows->OutputSgstLedgers->find('list')->where(['company_id'=>$company_id]);
        $outputIgstLedgers = $this->SalesInvoiceRows->OutputIgstLedgers->find('list')->where(['company_id'=>$company_id]);
        $this->set(compact('salesInvoiceRow', 'salesInvoices', 'items', 'gstFigures', 'outputCgstLedgers', 'outputSgstLedgers', 'outputIgstLedgers'));
        $this->set('_serialize', ['salesInvoiceRow']);
    }

    /**
     * Delete method
     *
     * @param string|null $id Sales Invoice Row id.
     * @return \Cake\Http\Response|null Redirects to index.
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function delete($id = null)
    {
        $this->request->allowMethod(['post', 'delete']);
        $salesInvoiceRow = $this->SalesInvoiceRows->get($id);
        if ($this->SalesInvoiceRows->delete($salesInvoiceRow)) {
            $this->Flash->success(__('The sales invoice row has been deleted.'));
        } else {
            $this->Flash->error(__('The sales invoice row could not be deleted. Please, try again.'));
        }

        return $this->redirect(['action' => 'index']);
    }
}
