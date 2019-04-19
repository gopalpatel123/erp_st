<?php
namespace App\Controller;

use App\Controller\AppController;

/**
 * Shades Controller
 *
 * @property \App\Model\Table\ShadesTable $Shades
 *
 * @method \App\Model\Entity\Shade[] paginate($object = null, array $settings = [])
 */
class ShadesController extends AppController
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
        $shades = $this->paginate($this->Shades->find()->where(['company_id'=>$company_id])->contain([]));
       
        $this->set(compact('shades'));
        $this->set('_serialize', ['shades']);
    }

    /**
     * View method
     *
     * @param string|null $id Shade id.
     * @return \Cake\Http\Response|void
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function view($id = null)
    {
		$this->viewBuilder()->layout('index_layout');
		$company_id=$this->Auth->User('session_company_id');
        $shade = $this->Shades->get($id, [
            'contain' => ['Companies', 'Items']
        ]);

        $this->set('shade', $shade);
        $this->set('_serialize', ['shade']);
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
        $shade = $this->Shades->newEntity();
		$this->request->data['company_id'] =$company_id;
        if ($this->request->is('post')) {
            $shade = $this->Shades->patchEntity($shade, $this->request->getData());
			
            if ($this->Shades->save($shade)) {
                $this->Flash->success(__('The shade has been saved.'));

                return $this->redirect(['action' => 'add']);
            }
            $this->Flash->error(__('The shade could not be saved. Please, try again.'));
        }
        $this->set(compact('shade', 'companies'));
        $this->set('_serialize', ['shade']);
    }

    /**
     * Edit method
     *
     * @param string|null $id Shade id.
     * @return \Cake\Http\Response|null Redirects on successful edit, renders view otherwise.
     * @throws \Cake\Network\Exception\NotFoundException When record not found.
     */
    public function edit($id = null)
    {
		$this->viewBuilder()->layout('index_layout');
		$company_id=$this->Auth->User('session_company_id');
        $shade = $this->Shades->get($id, [
            'contain' => []
        ]);
		$this->request->data['company_id'] =$company_id;
        if ($this->request->is(['patch', 'post', 'put'])) {
            $shade = $this->Shades->patchEntity($shade, $this->request->getData());
            if ($this->Shades->save($shade)) {
                $this->Flash->success(__('The shade has been saved.'));

                return $this->redirect(['action' => 'index']);
            }
            $this->Flash->error(__('The shade could not be saved. Please, try again.'));
        }
       $this->set(compact('shade', 'companies'));
        $this->set('_serialize', ['shade']);
    }

    /**
     * Delete method
     *
     * @param string|null $id Shade id.
     * @return \Cake\Http\Response|null Redirects to index.
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function delete($id = null)
    {
		$this->viewBuilder()->layout('index_layout');
		$company_id=$this->Auth->User('session_company_id');
        $this->request->allowMethod(['post', 'delete']);
        $shade = $this->Shades->get($id);
        if ($this->Shades->delete($shade)) {
            $this->Flash->success(__('The shade has been deleted.'));
        } else {
            $this->Flash->error(__('The shade could not be deleted. Please, try again.'));
        }

        return $this->redirect(['action' => 'index']);
    }
}
