<?php
namespace App\Controller;

use App\Controller\AppController;

/**
 * PurchaseInvoiceRows Controller
 *
 * @property \App\Model\Table\PurchaseInvoiceRowsTable $PurchaseInvoiceRows
 *
 * @method \App\Model\Entity\PurchaseInvoiceRow[] paginate($object = null, array $settings = [])
 */
class PurchaseInvoiceRowsController extends AppController
{

    /**
     * Index method
     *
     * @return \Cake\Http\Response|void
     */
    public function index()
    {
        $this->paginate = [
            'contain' => ['PurchaseInvoices', 'Items', 'GstFigures']
        ];
        $purchaseInvoiceRows = $this->paginate($this->PurchaseInvoiceRows);

        $this->set(compact('purchaseInvoiceRows'));
        $this->set('_serialize', ['purchaseInvoiceRows']);
    }

    /**
     * View method
     *
     * @param string|null $id Purchase Invoice Row id.
     * @return \Cake\Http\Response|void
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function view($id = null)
    {
        $purchaseInvoiceRow = $this->PurchaseInvoiceRows->get($id, [
            'contain' => ['PurchaseInvoices', 'Items', 'GstFigures']
        ]);

        $this->set('purchaseInvoiceRow', $purchaseInvoiceRow);
        $this->set('_serialize', ['purchaseInvoiceRow']);
    }

    /**
     * Add method
     *
     * @return \Cake\Http\Response|null Redirects on successful add, renders view otherwise.
     */
    public function add()
    {
        $purchaseInvoiceRow = $this->PurchaseInvoiceRows->newEntity();
        if ($this->request->is('post')) {
            $purchaseInvoiceRow = $this->PurchaseInvoiceRows->patchEntity($purchaseInvoiceRow, $this->request->getData());
            if ($this->PurchaseInvoiceRows->save($purchaseInvoiceRow)) {
                $this->Flash->success(__('The purchase invoice row has been saved.'));

                return $this->redirect(['action' => 'index']);
            }
            $this->Flash->error(__('The purchase invoice row could not be saved. Please, try again.'));
        }
        $purchaseInvoices = $this->PurchaseInvoiceRows->PurchaseInvoices->find('list', ['limit' => 200]);
        $items = $this->PurchaseInvoiceRows->Items->find('list', ['limit' => 200]);
        $gstFigures = $this->PurchaseInvoiceRows->GstFigures->find('list', ['limit' => 200]);
        $this->set(compact('purchaseInvoiceRow', 'purchaseInvoices', 'items', 'gstFigures'));
        $this->set('_serialize', ['purchaseInvoiceRow']);
    }

    /**
     * Edit method
     *
     * @param string|null $id Purchase Invoice Row id.
     * @return \Cake\Http\Response|null Redirects on successful edit, renders view otherwise.
     * @throws \Cake\Network\Exception\NotFoundException When record not found.
     */
    public function edit($id = null)
    {
        $purchaseInvoiceRow = $this->PurchaseInvoiceRows->get($id, [
            'contain' => []
        ]);
        if ($this->request->is(['patch', 'post', 'put'])) {
            $purchaseInvoiceRow = $this->PurchaseInvoiceRows->patchEntity($purchaseInvoiceRow, $this->request->getData());
            if ($this->PurchaseInvoiceRows->save($purchaseInvoiceRow)) {
                $this->Flash->success(__('The purchase invoice row has been saved.'));

                return $this->redirect(['action' => 'index']);
            }
            $this->Flash->error(__('The purchase invoice row could not be saved. Please, try again.'));
        }
        $purchaseInvoices = $this->PurchaseInvoiceRows->PurchaseInvoices->find('list', ['limit' => 200]);
        $items = $this->PurchaseInvoiceRows->Items->find('list', ['limit' => 200]);
        $gstFigures = $this->PurchaseInvoiceRows->GstFigures->find('list', ['limit' => 200]);
        $this->set(compact('purchaseInvoiceRow', 'purchaseInvoices', 'items', 'gstFigures'));
        $this->set('_serialize', ['purchaseInvoiceRow']);
    }

    /**
     * Delete method
     *
     * @param string|null $id Purchase Invoice Row id.
     * @return \Cake\Http\Response|null Redirects to index.
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function delete($id = null)
    {
        $this->request->allowMethod(['post', 'delete']);
        $purchaseInvoiceRow = $this->PurchaseInvoiceRows->get($id);
        if ($this->PurchaseInvoiceRows->delete($purchaseInvoiceRow)) {
            $this->Flash->success(__('The purchase invoice row has been deleted.'));
        } else {
            $this->Flash->error(__('The purchase invoice row could not be deleted. Please, try again.'));
        }

        return $this->redirect(['action' => 'index']);
    }
}
