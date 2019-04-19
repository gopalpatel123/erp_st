<?php
namespace App\Controller;

use App\Controller\AppController;

/**
 * StockGroups Controller
 *
 * @property \App\Model\Table\StockGroupsTable $StockGroups
 *
 * @method \App\Model\Entity\StockGroup[] paginate($object = null, array $settings = [])
 */
class StockGroupsController extends AppController
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
		$search=$this->request->query('search');
		$this->paginate = [
            'contain' => ['ParentStockGroups', 'Companies'],
			'limit' => 100
        ];
        $stockGroups = $this->paginate($this->StockGroups->find()->where(['StockGroups.company_id'=>$company_id])->where([
		'OR' => [
            'StockGroups.name LIKE' => '%'.$search.'%',
			//...
			'ParentStockGroups.name LIKE' => '%'.$search.'%'
		 ]]));

        $this->set(compact('stockGroups','search'));
        $this->set('_serialize', ['stockGroups']);
    }

    /**
     * View method
     *
     * @param string|null $id Stock Group id.
     * @return \Cake\Http\Response|void
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function view($id = null)
    {
        $stockGroup = $this->StockGroups->get($id, [
            'contain' => ['ParentStockGroups', 'Companies', 'Items', 'ChildStockGroups']
        ]);

        $this->set('stockGroup', $stockGroup);
        $this->set('_serialize', ['stockGroup']);
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
        $stockGroup = $this->StockGroups->newEntity();
        if ($this->request->is('post')) {
            $stockGroup = $this->StockGroups->patchEntity($stockGroup, $this->request->getData());
			$stockGroup->company_id = $company_id;
            if ($this->StockGroups->save($stockGroup)) {
                $this->Flash->success(__('The stock group has been saved.'));

                return $this->redirect(['action' => 'add']);
            }
            $this->Flash->error(__('The stock group could not be saved. Please, try again.'));
        }
        $parentStockGroups = $this->StockGroups->ParentStockGroups->find('list')->where(['company_id'=>$company_id]);
        $this->set(compact('stockGroup', 'parentStockGroups'));
        $this->set('_serialize', ['stockGroup']);
    }

    /**
     * Edit method
     *
     * @param string|null $id Stock Group id.
     * @return \Cake\Http\Response|null Redirects on successful edit, renders view otherwise.
     * @throws \Cake\Network\Exception\NotFoundException When record not found.
     */
    public function edit($id = null)
    {
		$this->viewBuilder()->layout('index_layout');
        $stockGroup = $this->StockGroups->get($id, [
            'contain' => []
        ]);
		$company_id=$this->Auth->User('session_company_id');
        if ($this->request->is(['patch', 'post', 'put'])) {
            $stockGroup = $this->StockGroups->patchEntity($stockGroup, $this->request->getData());
            if ($this->StockGroups->save($stockGroup)) {
                $this->Flash->success(__('The stock group has been saved.'));

                return $this->redirect(['action' => 'index']);
            }
            $this->Flash->error(__('The stock group could not be saved. Please, try again.'));
        }
        $parentStockGroups = $this->StockGroups->ParentStockGroups->find('list')->where(['company_id'=>$company_id]);
        $companies = $this->StockGroups->Companies->find('list');
        $this->set(compact('stockGroup', 'parentStockGroups', 'companies'));
        $this->set('_serialize', ['stockGroup']);
    }

    /**
     * Delete method
     *
     * @param string|null $id Stock Group id.
     * @return \Cake\Http\Response|null Redirects to index.
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function delete($id = null)
    {
        $this->request->allowMethod(['post', 'delete']);
        $stockGroup = $this->StockGroups->get($id);
        if ($this->StockGroups->delete($stockGroup)) {
            $this->Flash->success(__('The stock group has been deleted.'));
        } else {
            $this->Flash->error(__('The stock group could not be deleted. Please, try again.'));
        }
		return $this->redirect(['action' => 'index']);
    }
	
	 public function stockSubGroup($id = null)
    { 
		$this->viewBuilder()->layout('');
		$company_id=$this->Auth->User('session_company_id');
        //$stockGroup = $this->StockGroups->get($id);
		$stockSubgroups=$this->StockGroups->find('list')->where(['StockGroups.company_id'=>$company_id,'StockGroups.parent_id' => $id]); //pr($stockSubgroups); exit;
		$this->set(compact('stockSubgroups'));
    }
	
	
	public function summary(){
		$stockGroupId = $this->request->query('stock-group-id');
		$from_date         = $this->request->query('from-date');
		$to_date           = $this->request->query('to-date');
		
		$this->viewBuilder()->layout('index_layout');
		$company_id=$this->Auth->User('session_company_id');
		
		if(!$stockGroupId){
			$subGroups=$this->StockGroups->find()->where(['StockGroups.parent_id is null','StockGroups.company_id'=>$company_id]);
			/* $subGroupBalances=[];
			foreach($subGroups as $subGroup){
				$subGroupBalances[$subGroup->id]=$this->groupBalance($subGroup->id,date('Y-m-d',strtotime($to_date)));
			} */
			
			$Items=$this->StockGroups->Items->find()->where(['Items.stock_group_id'=>0, 'Items.company_id'=>$company_id]);
			
			$closingBalances=[];
			foreach($Items as $Item){
				$closingBalances[$Item->id]=$this->closingBalance($Item->id,date('Y-m-d',strtotime($to_date)));
			}
		}
		
		$this->set(compact('stockGroupId', 'from_date', 'to_date', 'subGroups', 'Items', 'closingBalances'));
	}
}
