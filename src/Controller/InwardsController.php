<?php
namespace App\Controller;

use App\Controller\AppController;

/**
 * Inwards Controller
 *
 * @property \App\Model\Table\InwardsTable $Inwards
 *
 * @method \App\Model\Entity\Inward[] paginate($object = null, array $settings = [])
 */
class InwardsController extends AppController
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
            'contain' => ['Items', 'StockJournals']
        ];
        $inwards = $this->paginate($this->Inwards->find());

        $this->set(compact('inwards'));
        $this->set('_serialize', ['inwards']);
    }

    /**
     * View method
     *
     * @param string|null $id Inward id.
     * @return \Cake\Http\Response|void
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function view($id = null)
    {
		$this->viewBuilder()->layout('index_layout');
		$company_id=$this->Auth->User('session_company_id');
		$inward = $this->Inwards->get($id, [
            'contain' => ['Items', 'StockJournals']
        ]);

        $this->set('inward', $inward);
        $this->set('_serialize', ['inward']);
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
		$inward = $this->Inwards->newEntity();
        if ($this->request->is('post')) {
            $inward = $this->Inwards->patchEntity($inward, $this->request->getData());
            if ($this->Inwards->save($inward)) {
                $this->Flash->success(__('The inward has been saved.'));

                return $this->redirect(['action' => 'add']);
            }
            $this->Flash->error(__('The inward could not be saved. Please, try again.'));
        }
        $items = $this->Inwards->Items->find('list')->where(['company_id'=>$company_id]);
        $stockJournals = $this->Inwards->StockJournals->find('list')->where(['company_id'=>$company_id]);
        $this->set(compact('inward', 'items', 'stockJournals'));
        $this->set('_serialize', ['inward']);
    }

    /**
     * Edit method
     *
     * @param string|null $id Inward id.
     * @return \Cake\Http\Response|null Redirects on successful edit, renders view otherwise.
     * @throws \Cake\Network\Exception\NotFoundException When record not found.
     */
    public function edit($id = null)
    {
        $this->viewBuilder()->layout('index_layout');
		$company_id=$this->Auth->User('session_company_id');
		$inward = $this->Inwards->get($id, [
            'contain' => []
        ]);
        if ($this->request->is(['patch', 'post', 'put'])) {
            $inward = $this->Inwards->patchEntity($inward, $this->request->getData());
            if ($this->Inwards->save($inward)) {
                $this->Flash->success(__('The inward has been saved.'));

                return $this->redirect(['action' => 'index']);
            }
            $this->Flash->error(__('The inward could not be saved. Please, try again.'));
        }
        $items = $this->Inwards->Items->find('list')->where(['company_id'=>$company_id]);
        $stockJournals = $this->Inwards->StockJournals->find('list')->where(['company_id'=>$company_id]);
        $this->set(compact('inward', 'items', 'stockJournals'));
        $this->set('_serialize', ['inward']);
    }

    /**
     * Delete method
     *
     * @param string|null $id Inward id.
     * @return \Cake\Http\Response|null Redirects to index.
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function delete($id = null)
    {
        $this->request->allowMethod(['post', 'delete']);
        $inward = $this->Inwards->get($id);
        if ($this->Inwards->delete($inward)) {
            $this->Flash->success(__('The inward has been deleted.'));
        } else {
            $this->Flash->error(__('The inward could not be deleted. Please, try again.'));
        }

        return $this->redirect(['action' => 'index']);
    }
}
