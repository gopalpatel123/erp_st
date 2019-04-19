<?php
namespace App\Controller;

use App\Controller\AppController;

/**
 * GrnRows Controller
 *
 * @property \App\Model\Table\GrnRowsTable $GrnRows
 *
 * @method \App\Model\Entity\GrnRow[] paginate($object = null, array $settings = [])
 */
class GrnRowsController extends AppController
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
            'contain' => ['Grns', 'Items']
        ];
        $grnRows = $this->paginate($this->GrnRows);

        $this->set(compact('grnRows'));
        $this->set('_serialize', ['grnRows']);
    }

    /**
     * View method
     *
     * @param string|null $id Grn Row id.
     * @return \Cake\Http\Response|void
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function view($id = null)
    {
        $grnRow = $this->GrnRows->get($id, [
            'contain' => ['Grns', 'Items']
        ]);

        $this->set('grnRow', $grnRow);
        $this->set('_serialize', ['grnRow']);
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
		$grnRow = $this->GrnRows->newEntity();
        if ($this->request->is('post')) {
            $grnRow = $this->GrnRows->patchEntity($grnRow, $this->request->getData());
            if ($this->GrnRows->save($grnRow)) {
                $this->Flash->success(__('The grn row has been saved.'));

                return $this->redirect(['action' => 'index']);
            }
            $this->Flash->error(__('The grn row could not be saved. Please, try again.'));
        }
        $grns = $this->GrnRows->Grns->find('list')->where(['company_id'=>$company_id]);
        $items = $this->GrnRows->Items->find('list')->where(['company_id'=>$company_id]);
        $this->set(compact('grnRow', 'grns', 'items'));
        $this->set('_serialize', ['grnRow']);
    }

    /**
     * Edit method
     *
     * @param string|null $id Grn Row id.
     * @return \Cake\Http\Response|null Redirects on successful edit, renders view otherwise.
     * @throws \Cake\Network\Exception\NotFoundException When record not found.
     */
    public function edit($id = null)
    {
        $this->viewBuilder()->layout('index_layout');
		$company_id=$this->Auth->User('session_company_id');
		$grnRow = $this->GrnRows->get($id, [
            'contain' => []
        ]);
        if ($this->request->is(['patch', 'post', 'put'])) {
            $grnRow = $this->GrnRows->patchEntity($grnRow, $this->request->getData());
            if ($this->GrnRows->save($grnRow)) {
                $this->Flash->success(__('The grn row has been saved.'));

                return $this->redirect(['action' => 'index']);
            }
            $this->Flash->error(__('The grn row could not be saved. Please, try again.'));
        }
        $grns = $this->GrnRows->Grns->find('list')->where(['company_id'=>$company_id]);
        $items = $this->GrnRows->Items->find('list')->where(['company_id'=>$company_id]);
        $this->set(compact('grnRow', 'grns', 'items'));
        $this->set('_serialize', ['grnRow']);
    }

    /**
     * Delete method
     *
     * @param string|null $id Grn Row id.
     * @return \Cake\Http\Response|null Redirects to index.
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function delete($id = null)
    {
        $this->request->allowMethod(['post', 'delete']);
        $grnRow = $this->GrnRows->get($id);
        if ($this->GrnRows->delete($grnRow)) {
            $this->Flash->success(__('The grn row has been deleted.'));
        } else {
            $this->Flash->error(__('The grn row could not be deleted. Please, try again.'));
        }

        return $this->redirect(['action' => 'index']);
    }
}
