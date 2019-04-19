<?php
namespace App\Controller;

use App\Controller\AppController;

/**
 * Outwards Controller
 *
 * @property \App\Model\Table\OutwardsTable $Outwards
 *
 * @method \App\Model\Entity\Outward[] paginate($object = null, array $settings = [])
 */
class OutwardsController extends AppController
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
        $outwards = $this->paginate($this->Outwards);

        $this->set(compact('outwards'));
        $this->set('_serialize', ['outwards']);
    }

    /**
     * View method
     *
     * @param string|null $id Outward id.
     * @return \Cake\Http\Response|void
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function view($id = null)
    {
        $outward = $this->Outwards->get($id, [
            'contain' => ['Items', 'StockJournals']
        ]);

        $this->set('outward', $outward);
        $this->set('_serialize', ['outward']);
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
		$outward = $this->Outwards->newEntity();
        if ($this->request->is('post')) {
            $outward = $this->Outwards->patchEntity($outward, $this->request->getData());
            if ($this->Outwards->save($outward)) {
                $this->Flash->success(__('The outward has been saved.'));

                return $this->redirect(['action' => 'add']);
            }
            $this->Flash->error(__('The outward could not be saved. Please, try again.'));
        }
        $items = $this->Outwards->Items->find('list')->where(['company_id'=>$company_id]);
        $stockJournals = $this->Outwards->StockJournals->find('list')->where(['company_id'=>$company_id]);
        $this->set(compact('outward', 'items', 'stockJournals'));
        $this->set('_serialize', ['outward']);
    }

    /**
     * Edit method
     *
     * @param string|null $id Outward id.
     * @return \Cake\Http\Response|null Redirects on successful edit, renders view otherwise.
     * @throws \Cake\Network\Exception\NotFoundException When record not found.
     */
    public function edit($id = null)
    {
        $this->viewBuilder()->layout('index_layout');
		$company_id=$this->Auth->User('session_company_id');
		$outward = $this->Outwards->get($id, [
            'contain' => []
        ]);
        if ($this->request->is(['patch', 'post', 'put'])) {
            $outward = $this->Outwards->patchEntity($outward, $this->request->getData());
            if ($this->Outwards->save($outward)) {
                $this->Flash->success(__('The outward has been saved.'));

                return $this->redirect(['action' => 'index']);
            }
            $this->Flash->error(__('The outward could not be saved. Please, try again.'));
        }
        $items = $this->Outwards->Items->find('list')->where(['company_id'=>$company_id]);
        $stockJournals = $this->Outwards->StockJournals->find('list')->where(['company_id'=>$company_id]);
        $this->set(compact('outward', 'items', 'stockJournals'));
        $this->set('_serialize', ['outward']);
    }

    /**
     * Delete method
     *
     * @param string|null $id Outward id.
     * @return \Cake\Http\Response|null Redirects to index.
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function delete($id = null)
    {
        $this->request->allowMethod(['post', 'delete']);
        $outward = $this->Outwards->get($id);
        if ($this->Outwards->delete($outward)) {
            $this->Flash->success(__('The outward has been deleted.'));
        } else {
            $this->Flash->error(__('The outward could not be deleted. Please, try again.'));
        }

        return $this->redirect(['action' => 'index']);
    }
}
