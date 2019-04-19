<?php
namespace App\Controller;

use App\Controller\AppController;

/**
 * StockJournals Controller
 *
 * @property \App\Model\Table\StockJournalsTable $StockJournals
 *
 * @method \App\Model\Entity\StockJournal[] paginate($object = null, array $settings = [])
 */
class StockJournalsController extends AppController
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
		$financialYear_id=$this->Auth->User('financialYear_id');
		$search=$this->request->query('search');
		$this->paginate = [
            'contain' => ['Companies'],
			'limit' => 100
        ];
        $stockJournals = $this->paginate($this->StockJournals->find()->where(['StockJournals.company_id'=>$company_id,'StockJournals.financial_year_id'=>$financialYear_id])->where([
		'OR' => [
            'StockJournals.voucher_no' => $search,
            // ...
			'StockJournals.reference_no LIKE' => '%'.$search.'%',
			//...
			'StockJournals.transaction_date ' => date('Y-m-d',strtotime($search))
		 ]]));

        $this->set(compact('stockJournals','search'));
        $this->set('_serialize', ['stockJournals']);
    }

    /**
     * View method
     *
     * @param string|null $id Stock Journal id.
     * @return \Cake\Http\Response|void
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function view($id = null)
    {
		$this->viewBuilder()->layout('index_layout');
        $stockJournal = $this->StockJournals->get($id, [
            'contain' => ['Companies', 'Inwards'=>['Items'], 'Outwards'=>['Items']]
        ]);
       // pr($stockJournal->toArray());exit;
        $this->set('stockJournal', $stockJournal);
        $this->set('_serialize', ['stockJournal']);
    }

    /**
     * Add method
     *
     * @return \Cake\Http\Response|null Redirects on successful add, renders view otherwise.
     */
    public function add()
    {
		$this->viewBuilder()->layout('index_layout');
        $stockJournal = $this->StockJournals->newEntity();
		$company_id=$this->Auth->User('session_company_id');
		$location_id = $this->Auth->User('session_location_id');
		$financialYear_id=$this->Auth->User('financialYear_id');
		$user_id=$this->Auth->User('id');
        if ($this->request->is('post')) {
            $stockJournal = $this->StockJournals->patchEntity($stockJournal, $this->request->getData());
			//pr($stockJournal);exit;
			$Voucher_no=$this->StockJournals->find()->select(['voucher_no'])->where(['company_id'=>$company_id,'financial_year_id'=>$financialYear_id])->order(['voucher_no' => 'DESC'])->first();
			if($Voucher_no){
				$stockJournal->voucher_no=$Voucher_no->voucher_no+1;
			}else{
				$stockJournal->voucher_no=1;
			} 
			$stockJournal->transaction_date = date("Y-m-d",strtotime($this->request->data['transaction_date']));
			$stockJournal->company_id       = $company_id;
			$stockJournal->created_by       = $user_id;
			$stockJournal->created_on       = date('Y-m-d');
			$stockJournal->location_id      = $location_id;
			$stockJournal->financial_year_id =$financialYear_id;		
            if ($this->StockJournals->save($stockJournal)) {
				    if(!empty($stockJournal->inwards))
					{
						foreach($stockJournal->inwards as $inward)
						{
							$itemLedger = $this->StockJournals->ItemLedgers->newEntity();
							$itemLedger->item_id            = $inward->item_id;
							$itemLedger->transaction_date   = date("Y-m-d",strtotime($stockJournal->transaction_date));
							$itemLedger->quantity           = $inward->quantity;
							$itemLedger->rate               = $inward->rate;
							$itemLedger->amount             = $inward->amount;
							$itemLedger->status             = 'in';
							$itemLedger->stock_journal_id   = $stockJournal->id;
							$itemLedger->inward_id          = $inward->id;
							
							$this->StockJournals->ItemLedgers->save($itemLedger);
						}
					}
					if(!empty($stockJournal->outwards))
					{
						foreach($stockJournal->outwards as $outward)
						{
							$itemLedger = $this->StockJournals->ItemLedgers->newEntity();
							$itemLedger->item_id            = $outward->item_id;
							$itemLedger->transaction_date   = date("Y-m-d",strtotime($stockJournal->transaction_date));
							$itemLedger->quantity           = $outward->quantity;
							$itemLedger->rate               = $outward->rate;
							$itemLedger->amount             = $outward->amount;
							$itemLedger->status             = 'out';
							$itemLedger->stock_journal_id   = $stockJournal->id;
							$itemLedger->outward_id          = $outward->id;
							
							$this->StockJournals->ItemLedgers->save($itemLedger);
						}
					}
                $this->Flash->success(__('The stock journal has been saved.'));

                return $this->redirect(['action' => 'add']);
            }
            $this->Flash->error(__('The stock journal could not be saved. Please, try again.'));
        }
		$items     = $this->StockJournals->Inwards->Items->find()->where(['company_id'=>$company_id]);
		$itemOptions=[];
		foreach($items as $item){
			$itemOptions[]=['text'=>$item->item_code.' '.$item->name, 'value'=>$item->id,'item_code'=>$item->item_code];
		}
        $Voucher_no=$this->StockJournals->find()->select(['voucher_no'])->where(['company_id'=>$company_id,'financial_year_id'=>$financialYear_id])->order(['voucher_no' => 'DESC'])->first();
		if($Voucher_no){
			$voucher_no=$Voucher_no->voucher_no+1;
		}else{
			$voucher_no=1;
		} 
        $this->set(compact('stockJournal', 'items','voucher_no','itemOptions'));
        $this->set('_serialize', ['stockJournal']);
    }

    /**
     * Edit method
     *
     * @param string|null $id Stock Journal id.
     * @return \Cake\Http\Response|null Redirects on successful edit, renders view otherwise.
     * @throws \Cake\Network\Exception\NotFoundException When record not found.
     */
    public function edit($id = null)
    {
		$this->viewBuilder()->layout('index_layout');
        $stockJournal = $this->StockJournals->get($id, [
            'contain' => ['Inwards'=>['Items'],'Outwards'=>['Items']]
        ]);
		$company_id=$this->Auth->User('session_company_id');
		$location_id = $this->Auth->User('session_location_id');
		$user_id=$this->Auth->User('id');
		
        if ($this->request->is(['patch', 'post', 'put'])) {
			$data=$this->request->getData();
			//$data['outwards']=null;
			//pr($data); exit;
			$stockJournal = $this->StockJournals->get($id);
            $stockJournal = $this->StockJournals->patchEntity($stockJournal, $data);
			$stockJournal->edited_by = $user_id;
			$stockJournal->edited_on = date('Y-m-d');
			$stockJournal->transaction_date = date("Y-m-d",strtotime($this->request->data['transaction_date']));
			$stockJournal->location_id = $location_id;
			//pr($stockJournal);exit;
            if($this->StockJournals->save($stockJournal)) {
				 $query_delete = $this->StockJournals->ItemLedgers->query();
					$query_delete->delete()
					->where(['stock_journal_id' => $stockJournal->id,'company_id'=>$company_id])
					->execute();
					if(!empty($stockJournal->inwards))
					{
						foreach($stockJournal->inwards as $inward)
						{
							$itemLedger = $this->StockJournals->ItemLedgers->newEntity();
							$itemLedger->item_id            = $inward->item_id;
							$itemLedger->transaction_date   = date("Y-m-d",strtotime($stockJournal->transaction_date));
							$itemLedger->quantity           = $inward->quantity;
							$itemLedger->rate               = $inward->rate;
							$itemLedger->amount             = $inward->amount;
							$itemLedger->status             = 'in';
							$itemLedger->stock_journal_id   = $stockJournal->id;
							$itemLedger->inward_id          = $inward->id;
							
							$this->StockJournals->ItemLedgers->save($itemLedger);
						}
					}
					if(!empty($stockJournal->outwards))
					{
						foreach($stockJournal->outwards as $outward)
						{
							$itemLedger = $this->StockJournals->ItemLedgers->newEntity();
							$itemLedger->item_id            = $outward->item_id;
							$itemLedger->transaction_date   = date("Y-m-d",strtotime($stockJournal->transaction_date));
							$itemLedger->quantity           = $outward->quantity;
							$itemLedger->rate               = $outward->rate;
							$itemLedger->amount             = $outward->amount;
							$itemLedger->status             = 'out';
							$itemLedger->stock_journal_id   = $stockJournal->id;
							$itemLedger->outward_id          = $outward->id;
							
							$this->StockJournals->ItemLedgers->save($itemLedger);
						}
					}
                $this->Flash->success(__('The stock journal has been saved.'));

                return $this->redirect(['action' => 'index']);
            }else{
				
            $this->Flash->error(__('The stock journal could not be saved. Please, try again.'));
			}
        }
		$items     = $this->StockJournals->Inwards->Items->find()->where(['company_id'=>$company_id]);
		$itemOptions=[];
		foreach($items as $item){
			$itemOptions[]=['text'=>$item->item_code.' '.$item->name, 'value'=>$item->id,'item_code'=>$item->item_code];
		}
        $this->set(compact('stockJournal', 'items','itemOptions'));
        $this->set('_serialize', ['stockJournal']);
    }

    /**
     * Delete method
     *
     * @param string|null $id Stock Journal id.
     * @return \Cake\Http\Response|null Redirects to index.
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function cancel($id = null)
    {
		$stockJournals = $this->StockJournals->get($id);
		$company_id=$this->Auth->User('session_company_id');
		$stockJournals->status='cancel';
		
        if ($this->StockJournals->save($stockJournals)) {
			
				$deleteItemLedgers = $this->StockJournals->ItemLedgers->query();
				$result = $deleteItemLedgers->delete()
				->where(['ItemLedgers.stock_journal_id' => $stockJournals->id])
				->execute();
				
            $this->Flash->success(__('The Stock Journal has been cancelled.'));
        } else {
            $this->Flash->error(__('The Stock Journal could not be deleted. Please, try again.'));
        }
        return $this->redirect(['action' => 'index']);
    }
}
