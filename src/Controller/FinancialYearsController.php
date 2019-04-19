<?php
namespace App\Controller;

use App\Controller\AppController;

/**
 * FinancialYears Controller
 *
 * @property \App\Model\Table\FinancialYearsTable $FinancialYears
 *
 * @method \App\Model\Entity\FinancialYear[] paginate($object = null, array $settings = [])
 */
class FinancialYearsController extends AppController
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
		$financialYears = $this->paginate($this->FinancialYears->find()->where(['FinancialYears.company_id'=>$company_id]));

        $this->set(compact('financialYears'));
        $this->set('_serialize', ['financialYears']);
    }

    /**
     * View method
     *
     * @param string|null $id Financial Year id.
     * @return \Cake\Http\Response|void
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function view($id = null)
    {
        $financialYear = $this->FinancialYears->get($id, [
            'contain' => []
        ]);

        $this->set('financialYear', $financialYear);
        $this->set('_serialize', ['financialYear']);
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
		$financialYear = $this->FinancialYears->newEntity();
        if ($this->request->is('post')) {
            $financialYear = $this->FinancialYears->patchEntity($financialYear, $this->request->getData());
            if ($this->FinancialYears->save($financialYear)) {
                $this->Flash->success(__('The financial year has been saved.'));

                return $this->redirect(['action' => 'index']);
            }
            $this->Flash->error(__('The financial year could not be saved. Please, try again.'));
        }
        $this->set(compact('financialYear'));
        $this->set('_serialize', ['financialYear']);
    }

    /**
     * Edit method
     *
     * @param string|null $id Financial Year id.
     * @return \Cake\Http\Response|null Redirects on successful edit, renders view otherwise.
     * @throws \Cake\Network\Exception\NotFoundException When record not found.
     */
    public function edit($id = null)
    {
        $this->viewBuilder()->layout('index_layout');
		$company_id=$this->Auth->User('session_company_id');
		$financialYear = $this->FinancialYears->get($id, [
            'contain' => []
        ]);
        if ($this->request->is(['patch', 'post', 'put'])) {
            $financialYear = $this->FinancialYears->patchEntity($financialYear, $this->request->getData());
            if ($this->FinancialYears->save($financialYear)) {
                $this->Flash->success(__('The financial year has been saved.'));

                return $this->redirect(['action' => 'index']);
            }
            $this->Flash->error(__('The financial year could not be saved. Please, try again.'));
        }
        $this->set(compact('financialYear'));
        $this->set('_serialize', ['financialYear']);
    }

    /**
     * Delete method
     *
     * @param string|null $id Financial Year id.
     * @return \Cake\Http\Response|null Redirects to index.
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function delete($id = null)
    {
        $this->request->allowMethod(['post', 'delete']);
        $financialYear = $this->FinancialYears->get($id);
        if ($this->FinancialYears->delete($financialYear)) {
            $this->Flash->success(__('The financial year has been deleted.'));
        } else {
            $this->Flash->error(__('The financial year could not be deleted. Please, try again.'));
        }

        return $this->redirect(['action' => 'index']);
    }
}
