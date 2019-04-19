<?php
namespace App\Controller;

use App\Controller\AppController;

/**
 * Sizes Controller
 *
 * @property \App\Model\Table\SizesTable $Sizes
 *
 * @method \App\Model\Entity\Size[] paginate($object = null, array $settings = [])
 */
class SizesController extends AppController
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
        $Sizes = $this->paginate($this->Sizes->find()->where(['company_id'=>$company_id]));
       
        $this->set(compact('Sizes'));
        $this->set('_serialize', ['Sizes']);
    }

    /**
     * View method
     *
     * @param string|null $id Size id.
     * @return \Cake\Http\Response|void
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function view($id = null)
    {
		$this->viewBuilder()->layout('index_layout');
		$company_id=$this->Auth->User('session_company_id');
        $size = $this->Sizes->get($id, [
            'contain' => ['Companies', 'Items']
        ]);

        $this->set('size', $size);
        $this->set('_serialize', ['size']);
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
        $size = $this->Sizes->newEntity();
        $this->request->data['company_id'] =$company_id;
		if ($this->request->is('post')) {
            $size = $this->Sizes->patchEntity($size, $this->request->getData());
            if ($this->Sizes->save($size)) {
                $this->Flash->success(__('The size has been saved.'));

                return $this->redirect(['action' => 'index']);
            }
            $this->Flash->error(__('The size could not be saved. Please, try again.'));
        }
        $companies = $this->Sizes->Companies->find('list');
        $this->set(compact('size', 'companies'));
        $this->set('_serialize', ['size']);
    }

    /**
     * Edit method
     *
     * @param string|null $id Size id.
     * @return \Cake\Http\Response|null Redirects on successful edit, renders view otherwise.
     * @throws \Cake\Network\Exception\NotFoundException When record not found.
     */
    public function edit($id = null)
    {
		$this->viewBuilder()->layout('index_layout');
		$company_id=$this->Auth->User('session_company_id');
        $size = $this->Sizes->get($id, [
            'contain' => []
        ]);
        if ($this->request->is(['patch', 'post', 'put'])) {
            $size = $this->Sizes->patchEntity($size, $this->request->getData());
            if ($this->Sizes->save($size)) {
                $this->Flash->success(__('The size has been saved.'));

                return $this->redirect(['action' => 'index']);
            }
            $this->Flash->error(__('The size could not be saved. Please, try again.'));
        }
        $companies = $this->Sizes->Companies->find('list');
        $this->set(compact('size', 'companies'));
        $this->set('_serialize', ['size']);
    }

    /**
     * Delete method
     *
     * @param string|null $id Size id.
     * @return \Cake\Http\Response|null Redirects to index.
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function delete($id = null)
    {
		$this->viewBuilder()->layout('index_layout');
		$company_id=$this->Auth->User('session_company_id');
        $this->request->allowMethod(['post', 'delete']);
        $size = $this->Sizes->get($id);
        if ($this->Sizes->delete($size)) {
            $this->Flash->success(__('The size has been deleted.'));
        } else {
            $this->Flash->error(__('The size could not be deleted. Please, try again.'));
        }

        return $this->redirect(['action' => 'index']);
    }
}
