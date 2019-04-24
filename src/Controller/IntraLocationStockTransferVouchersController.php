<?php
namespace App\Controller;

use App\Controller\AppController;

/**
 * IntraLocationStockTransferVouchers Controller
 *
 * @property \App\Model\Table\IntraLocationStockTransferVouchersTable $IntraLocationStockTransferVouchers
 *
 * @method \App\Model\Entity\IntraLocationStockTransferVoucher[] paginate($object = null, array $settings = [])
 */
class IntraLocationStockTransferVouchersController extends AppController
{

    /**
     * Index method
     *
     * @return \Cake\Http\Response|void
     */
    public function index($status = Null)
    {
		$this->viewBuilder()->layout('index_layout');
		$company_id=$this->Auth->User('session_company_id');
		$location_id=$this->Auth->User('session_location_id');
		$financialYear_id=$this->Auth->User('financialYear_id');
		$search=$this->request->query('search');
		$this->viewBuilder()->layout('index_layout');
		$voucher_no=$this->request->query('voucher_no');		
		$From=$this->request->query('From');
		$To=$this->request->query('To');
		
		
		if(!empty($voucher_no))
		{
			$where['IntraLocationStockTransferVouchers.voucher_no']=$voucher_no;
		}	
		
		if(!empty($From))
			{
				$From=date("Y-m-d",strtotime($From));
				$where['IntraLocationStockTransferVouchers.transaction_date >='] = $From;				
			}			 
		
		if(!empty($To))
			{
				$To=date("Y-m-d",strtotime($To));
				$where['IntraLocationStockTransferVouchers.transaction_date <='] = $To;				
				//pr($where);exit;
			}
	
	
		$where['IntraLocationStockTransferVouchers.company_id']=$company_id;
		$where['IntraLocationStockTransferVouchers.financial_year_id']=$financialYear_id;
		
		
		if(!empty($status))
		{
			$where['IntraLocationStockTransferVouchers.status']=$status; 
		}
	
	
		else
		{
			$where['IntraLocationStockTransferVouchers.status']='pending';
			//$where='pending';

		} //pr($where); exit;
		$this->paginate = [
            'contain' => ['TransferFromLocations','TransferToLocations'],
			'limit' => 100
        ];
		
		$intraLocationStockTransferVouchers = $this->paginate($this->IntraLocationStockTransferVouchers->find()->where($where)->where([
		'OR' => [
            'IntraLocationStockTransferVouchers.voucher_no' => $search,
            // ...
			'TransferFromLocations.name LIKE' => '%'.$search.'%',
			//...
			'IntraLocationStockTransferVouchers.transaction_date ' => date('Y-m-d',strtotime($search))
		 ]]));
		 
		$this->set(compact('intraLocationStockTransferVouchers','status','location_id','search','voucher_no'));
        $this->set('_serialize', ['intraLocationStockTransferVouchers']);
    }

    /**
     * View method
     *
     * @param string|null $id Intra Location Stock Transfer Voucher id.
     * @return \Cake\Http\Response|void
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function view($id = null)
    {
		$this->viewBuilder()->layout('index_layout');
        $intraLocationStockTransferVoucher = $this->IntraLocationStockTransferVouchers->get($id, [
            'contain' => ['TransferFromLocations','TransferToLocations', 'IntraLocationStockTransferVoucherRows'=>['Items']]
        ]);

        $this->set('intraLocationStockTransferVoucher', $intraLocationStockTransferVoucher);
        $this->set('_serialize', ['intraLocationStockTransferVoucher']);
    }

	public function viewApproved($id = null)
    {
		$this->viewBuilder()->layout('index_layout');
        $intraLocationStockTransferVoucher = $this->IntraLocationStockTransferVouchers->get($id, [
            'contain' => ['TransferFromLocations','TransferToLocations', 'IntraLocationStockTransferVoucherRows'=>['Items']]
        ]);

        $this->set('intraLocationStockTransferVoucher', $intraLocationStockTransferVoucher);
        $this->set('_serialize', ['intraLocationStockTransferVoucher']);
    }
    /**
     * Add method
     *
     * @return \Cake\Http\Response|null Redirects on successful add, renders view otherwise.
     */
    public function add($grn_id=null)
    {
		$this->viewBuilder()->layout('index_layout');
		if($grn_id){
		 $grns = $this->IntraLocationStockTransferVouchers->Grns->get($grn_id, [
            'contain' => ['GrnRows'=>['Items']]
        ]);	
		
		}
		$user_id=$this->Auth->User('id');
		$company_id=$this->Auth->User('session_company_id');
		$location_id=$this->Auth->User('session_location_id');
		$financialYear_id=$this->Auth->User('financialYear_id');
        $intraLocationStockTransferVoucher = $this->IntraLocationStockTransferVouchers->newEntity();
        if ($this->request->is('post')) {
            $intraLocationStockTransferVoucher = $this->IntraLocationStockTransferVouchers->patchEntity($intraLocationStockTransferVoucher, $this->request->getData());
			$Voucher_no = $this->IntraLocationStockTransferVouchers->find()->select(['voucher_no'])->where(['IntraLocationStockTransferVouchers.company_id'=>$company_id,'IntraLocationStockTransferVouchers.financial_year_id'=>$financialYear_id])->order(['voucher_no' => 'DESC'])->first();
			if($Voucher_no)
			{
				$intraLocationStockTransferVoucher->voucher_no = $Voucher_no->voucher_no+1;
			}
			else
			{
				$intraLocationStockTransferVoucher->voucher_no = 1;
			} 
			$intraLocationStockTransferVoucher->transaction_date = date("Y-m-d",strtotime($this->request->data['transaction_date']));
			$intraLocationStockTransferVoucher->company_id = $company_id;
			$intraLocationStockTransferVoucher->location_id = $location_id;
			$intraLocationStockTransferVoucher->financial_year_id =$financialYear_id;
			$intraLocationStockTransferVoucher->user_id = $user_id;
			$intraLocationStockTransferVoucher->transfer_by = $user_id;
            if ($this->IntraLocationStockTransferVouchers->save($intraLocationStockTransferVoucher)) 
			{
				foreach($intraLocationStockTransferVoucher->intra_location_stock_transfer_voucher_rows as $intra_location_stock_transfer_voucher_row)
				{
					$itemLedger = $this->IntraLocationStockTransferVouchers->ItemLedgers->newEntity();
					$itemLedger->item_id            = $intra_location_stock_transfer_voucher_row->item_id;
					$itemLedger->transaction_date   = date("Y-m-d",strtotime($intraLocationStockTransferVoucher->transaction_date));
					$itemLedger->quantity           = $intra_location_stock_transfer_voucher_row->quantity;
					$itemLedger->status             = 'out';
					$itemLedger->location_id   		= $intraLocationStockTransferVoucher->transfer_from_location_id;
					$itemLedger->intra_location_stock_transfer_voucher_id          = $intraLocationStockTransferVoucher->id;
					$itemLedger->intra_location_stock_transfer_voucher_row_id		= $intra_location_stock_transfer_voucher_row->id;
					$itemLedger->intra_location_transfer		= 'Yes';
					$itemLedger->company_id          = $company_id;
					$this->IntraLocationStockTransferVouchers->ItemLedgers->save($itemLedger);
				}
					$this->Flash->success(__('The inter location stock transfer voucher has been saved.'));

					return $this->redirect(['action' => 'add']);
					
				
            }
            $this->Flash->error(__('The inter location stock transfer voucher could not be saved. Please, try again.'));
        }
		$Voucher_no = $this->IntraLocationStockTransferVouchers->find()->select(['voucher_no'])->where(['IntraLocationStockTransferVouchers.company_id'=>$company_id,'IntraLocationStockTransferVouchers.financial_year_id'=>$financialYear_id])->order(['voucher_no' => 'DESC'])->first();
		if($Voucher_no){
			$voucher_no=$Voucher_no->voucher_no+1;
		}else{
			$voucher_no=1;
		} 
        $companies = $this->IntraLocationStockTransferVouchers->Companies->find('list');
        $TransferFromLocations = $this->IntraLocationStockTransferVouchers->TransferFromLocations->find('list')->where(['company_id'=>$company_id,'id'=>$location_id]);
		$TransferToLocations = $this->IntraLocationStockTransferVouchers->TransferToLocations->find('list')->where(['company_id'=>$company_id]);
		
		$items = $this->IntraLocationStockTransferVouchers->IntraLocationStockTransferVoucherRows->Items->find()
					->where(['Items.company_id'=>$company_id])
					->contain(['ItemLedgers'=>function($query) use($company_id,$location_id){
						$totalInCase = $query->newExpr()
									->addCase(
										$query->newExpr()->add(['status' => 'in']),
										$query->newExpr()->add(['quantity']),
										'integer'
									);
						$totalOutCase = $query->newExpr()
									->addCase(
										$query->newExpr()->add(['status' => 'out']),
										$query->newExpr()->add(['quantity']),
										'integer'
									);
								$query->select([
									'total_in' => $query->func()->sum($totalInCase),
									'total_out' => $query->func()->sum($totalOutCase),'item_id'
								])
								->group('ItemLedgers.item_id')
								->autoFields(true);
						
						return $query->where(['ItemLedgers.company_id' => $company_id]);
					}]);
					
			$itemOptions=[]; $item_array=[];
			foreach($items as $d)
			{
				foreach($d->item_ledgers as $dd)
				{   
					if($dd->total_in > $dd->total_out)
					{ 
					$itemOptions[]=['text'=>$d->item_code.' '.$d->name, 'value'=>$d->id];
					$item_array[]=$dd->item_id;
					}
				}
			}
		//pr($itemOptions); exit;
		
		
        $this->set(compact('intraLocationStockTransferVoucher', 'companies', 'TransferFromLocations','TransferToLocations','items','voucher_no','itemOptions','location_id','grn_id','grns','item_array'));
        $this->set('_serialize', ['intraLocationStockTransferVoucher']);
    }

    /**
     * Edit method
     *
     * @param string|null $id Intra Location Stock Transfer Voucher id.
     * @return \Cake\Http\Response|null Redirects on successful edit, renders view otherwise.
     * @throws \Cake\Network\Exception\NotFoundException When record not found.
     */
    public function edit($id = null)
    {
		$this->viewBuilder()->layout('index_layout');
		
		$company_id=$this->Auth->User('session_company_id');
		$location_id=$this->Auth->User('session_location_id');
		$user_id=$this->Auth->User('id');
        $intraLocationStockTransferVoucher = $this->IntraLocationStockTransferVouchers->get($id, [
            'contain' => ['IntraLocationStockTransferVoucherRows'=>['Items']]
        ]);
        if ($this->request->is(['patch', 'post', 'put'])) {
            $intraLocationStockTransferVoucher = $this->IntraLocationStockTransferVouchers->patchEntity($intraLocationStockTransferVoucher, $this->request->getData());
			$intraLocationStockTransferVoucher->transaction_date = date("Y-m-d",strtotime($this->request->data['transaction_date']));
			$intraLocationStockTransferVoucher->company_id = $company_id;
			$intraLocationStockTransferVoucher->location_id = $location_id;
			$intraLocationStockTransferVoucher->user_id = $user_id;
            if ($this->IntraLocationStockTransferVouchers->save($intraLocationStockTransferVoucher)) {
				$query_delete = $this->IntraLocationStockTransferVouchers->ItemLedgers->query();
				$query_delete->delete()
				->where(['intra_location_stock_transfer_voucher_id' => $intraLocationStockTransferVoucher->id,'company_id'=>$company_id])
				->execute();
				
				foreach($intraLocationStockTransferVoucher->intra_location_stock_transfer_voucher_rows as $intra_location_stock_transfer_voucher_row)
				{
					$itemLedger = $this->IntraLocationStockTransferVouchers->ItemLedgers->newEntity();
					$itemLedger->item_id            = $intra_location_stock_transfer_voucher_row->item_id;
					$itemLedger->transaction_date   = date("Y-m-d",strtotime($intraLocationStockTransferVoucher->transaction_date));
					$itemLedger->quantity           = $intra_location_stock_transfer_voucher_row->quantity;
					$itemLedger->status             = 'out';
					$itemLedger->location_id   		= $intraLocationStockTransferVoucher->transfer_from_location_id;
					$itemLedger->intra_location_stock_transfer_voucher_id          = $intraLocationStockTransferVoucher->id;
					$itemLedger->intra_location_stock_transfer_voucher_row_id		= $intra_location_stock_transfer_voucher_row->id;
					$itemLedger->intra_location_transfer		= 'Yes';
					$itemLedger->company_id          = $company_id;
					$this->IntraLocationStockTransferVouchers->ItemLedgers->save($itemLedger);
				}
                $this->Flash->success(__('The inter location stock transfer voucher has been saved.'));

                return $this->redirect(['action' => 'index']);
            }
            $this->Flash->error(__('The inter location stock transfer voucher could not be saved. Please, try again.'));
        }
        $companies = $this->IntraLocationStockTransferVouchers->Companies->find('list');
        $TransferFromLocations = $this->IntraLocationStockTransferVouchers->TransferFromLocations->find('list')->where(['company_id'=>$company_id,'id'=>$location_id]);
		$TransferToLocations = $this->IntraLocationStockTransferVouchers->TransferToLocations->find('list')->where(['company_id'=>$company_id]);
		
		$items = $this->IntraLocationStockTransferVouchers->IntraLocationStockTransferVoucherRows->Items->find()->where(['company_id'=>$company_id]);
		// $itemOptions=[];
					 // foreach($items as $item){
					 // $itemOptions[]=['text'=>$item->item_code.' '.$item->name, 'value'=>$item->id];
					 // }
		// pr($itemOptions);exit;
		$itemLedgers=[];
		foreach($items->toArray() as $data)
		{
			$itemId=$data->id;
			$query = $this->IntraLocationStockTransferVouchers->IntraLocationStockTransferVoucherRows->Items->ItemLedgers->find()
			->where(['ItemLedgers.item_id' => $itemId, 'ItemLedgers.company_id' => $company_id]);
			$totalInCase = $query->newExpr()
			->addCase(
				$query->newExpr()->add(['status' => 'In']),
				$query->newExpr()->add(['quantity']),
				'integer'
			);
		$totalOutCase = $query->newExpr()
			->addCase(
				$query->newExpr()->add(['status' => 'out']),
				$query->newExpr()->add(['quantity']),
				'integer'
			);
		$query->select([
			'total_in' => $query->func()->sum($totalInCase),
			'total_out' => $query->func()->sum($totalOutCase),'id','item_id'
		])
		->where(['ItemLedgers.item_id' => $itemId, 'ItemLedgers.company_id' => $company_id, 'ItemLedgers.location_id' => $location_id])
		->group('item_id')
		->autoFields(true)
		->contain(['Items']);
        $itemLedgers[] = ($query);
		}
		
		$itemOptions=[]; 
		foreach($itemLedgers as $d)
		{
			foreach($d as $dd)
			{
				$available_stock=$dd->total_in;
				$stock_issue=$dd->total_out;
				@$remaining=number_format($available_stock-$stock_issue, 2);
				if($remaining>=0)
				{
				$itemOptions[]=['text'=>$dd->item->item_code.' '.$dd->item->name, 'value'=>$dd->item_id];
				
				}
			}
		}
		
		
        $this->set(compact('intraLocationStockTransferVoucher', 'companies', 'TransferFromLocations','TransferToLocations','items','voucher_no','itemOptions','location_id'));
        $this->set('_serialize', ['intraLocationStockTransferVoucher']);
    }

	 public function approved($id = null)
    {
		$this->viewBuilder()->layout('index_layout');
		
		$company_id=$this->Auth->User('session_company_id');
		$location_id=$this->Auth->User('session_location_id');
		$user_id=$this->Auth->User('id');
        $intraLocationStockTransferVoucher = $this->IntraLocationStockTransferVouchers->get($id, [
            'contain' => ['IntraLocationStockTransferVoucherRows'=>['Items']]
        ]);
        if ($this->request->is(['patch', 'post', 'put'])) {
            $intraLocationStockTransferVoucher = $this->IntraLocationStockTransferVouchers->patchEntity($intraLocationStockTransferVoucher, $this->request->getData());  
			$intraLocationStockTransferVoucher->transaction_date = date("Y-m-d",strtotime($this->request->data['transaction_date']));
			$intraLocationStockTransferVoucher->company_id = $company_id;
			$intraLocationStockTransferVoucher->location_id = $location_id;
			$intraLocationStockTransferVoucher->user_id = $user_id;
			$intraLocationStockTransferVoucher->transfer_to = $user_id;
			$intraLocationStockTransferVoucher->status  = 'Approved';
			if ($this->IntraLocationStockTransferVouchers->save($intraLocationStockTransferVoucher)) {
				
				foreach($intraLocationStockTransferVoucher->intra_location_stock_transfer_voucher_rows as $intra_location_stock_transfer_voucher_row)
				{
						
					$itemLedger = $this->IntraLocationStockTransferVouchers->ItemLedgers->newEntity();
					$itemLedger->item_id            = $intra_location_stock_transfer_voucher_row->item_id;
					$itemLedger->transaction_date   = date("Y-m-d",strtotime($intraLocationStockTransferVoucher->transaction_date));
					$itemLedger->quantity           = $intra_location_stock_transfer_voucher_row->receive_quantity;
					$itemLedger->status             = 'in';
					$itemLedger->location_id   		= $intraLocationStockTransferVoucher->transfer_to_location_id;
					$itemLedger->intra_location_stock_transfer_voucher_id          = $intraLocationStockTransferVoucher->id;
					$itemLedger->intra_location_stock_transfer_voucher_row_id = $intra_location_stock_transfer_voucher_row->id;
					$itemLedger->intra_location_transfer		= 'Yes';
					$itemLedger->company_id          = $company_id;
					$this->IntraLocationStockTransferVouchers->ItemLedgers->save($itemLedger);
				}
                $this->Flash->success(__('The inter location stock transfer voucher has been approved.'));

                return $this->redirect(['action' => 'index']);
            }
            $this->Flash->error(__('The inter location stock transfer voucher could not be saved. Please, try again.'));
        }
        $companies = $this->IntraLocationStockTransferVouchers->Companies->find('list');
        $TransferFromLocations = $this->IntraLocationStockTransferVouchers->TransferFromLocations->find('list')->where(['company_id'=>$company_id]);
		$TransferToLocations = $this->IntraLocationStockTransferVouchers->TransferToLocations->find('list')->where(['company_id'=>$company_id]);
		
		$items = $this->IntraLocationStockTransferVouchers->IntraLocationStockTransferVoucherRows->Items->find()->where(['company_id'=>$company_id]);
		$itemOptions=[];
		foreach($items as $item){
			$itemOptions[]=['text'=>$item->item_code.' '.$item->name, 'value'=>$item->id];
		}
        $this->set(compact('intraLocationStockTransferVoucher', 'companies', 'TransferFromLocations','TransferToLocations','items','voucher_no','itemOptions','location_id'));
        $this->set('_serialize', ['intraLocationStockTransferVoucher']);
    }
	
	public function editApproved($id = null)
    {
		$this->viewBuilder()->layout('index_layout');
		
		$company_id=$this->Auth->User('session_company_id');
		$location_id=$this->Auth->User('session_location_id');
		$user_id=$this->Auth->User('id');
        $intraLocationStockTransferVoucher = $this->IntraLocationStockTransferVouchers->get($id, [
            'contain' => ['IntraLocationStockTransferVoucherRows'=>['Items']]
        ]);
        if ($this->request->is(['patch', 'post', 'put'])) {
            $intraLocationStockTransferVoucher = $this->IntraLocationStockTransferVouchers->patchEntity($intraLocationStockTransferVoucher, $this->request->getData());
			$intraLocationStockTransferVoucher->transaction_date = date("Y-m-d",strtotime($this->request->data['transaction_date']));
			$intraLocationStockTransferVoucher->company_id = $company_id;
			$intraLocationStockTransferVoucher->location_id = $location_id;
			$intraLocationStockTransferVoucher->user_id = $user_id;
			$intraLocationStockTransferVoucher->transfer_to = $user_id;
            if ($this->IntraLocationStockTransferVouchers->save($intraLocationStockTransferVoucher)) {
				$query_delete = $this->IntraLocationStockTransferVouchers->ItemLedgers->query();
				$query_delete->delete()
				->where(['intra_location_stock_transfer_voucher_id' => $intraLocationStockTransferVoucher->id,'company_id'=>$company_id])
				->execute();
				
				foreach($intraLocationStockTransferVoucher->intra_location_stock_transfer_voucher_rows as $intra_location_stock_transfer_voucher_row)
				{
					$itemLedger = $this->IntraLocationStockTransferVouchers->ItemLedgers->newEntity();
					$itemLedger->item_id            = $intra_location_stock_transfer_voucher_row->item_id;
					$itemLedger->transaction_date   = date("Y-m-d",strtotime($intraLocationStockTransferVoucher->transaction_date));
					$itemLedger->quantity           = $intra_location_stock_transfer_voucher_row->receive_quantity;
					$itemLedger->status             = 'in';
					$itemLedger->location_id   		= $intraLocationStockTransferVoucher->transfer_to_location_id;
					$itemLedger->intra_location_stock_transfer_voucher_id          = $intraLocationStockTransferVoucher->id;
					$itemLedger->intra_location_stock_transfer_voucher_row_id		= $intra_location_stock_transfer_voucher_row->id;
					$itemLedger->intra_location_transfer		= 'Yes';
					$itemLedger->company_id          = $company_id;
					$this->IntraLocationStockTransferVouchers->ItemLedgers->save($itemLedger);
				}
                $this->Flash->success(__('The inter location stock transfer voucher has been saved.'));

                return $this->redirect(['action' => 'index']);
            }
            $this->Flash->error(__('The inter location stock transfer voucher could not be saved. Please, try again.'));
        }
        $companies = $this->IntraLocationStockTransferVouchers->Companies->find('list');
        $TransferFromLocations = $this->IntraLocationStockTransferVouchers->TransferFromLocations->find('list')->where(['company_id'=>$company_id]);
		$TransferToLocations = $this->IntraLocationStockTransferVouchers->TransferToLocations->find('list')->where(['company_id'=>$company_id]);
		
		$items = $this->IntraLocationStockTransferVouchers->IntraLocationStockTransferVoucherRows->Items->find()->where(['company_id'=>$company_id]);
		$itemOptions=[];
		foreach($items as $item){
			$itemOptions[]=['text'=>$item->item_code.' '.$item->name, 'value'=>$item->id];
		}
        $this->set(compact('intraLocationStockTransferVoucher', 'companies', 'TransferFromLocations','TransferToLocations','items','voucher_no','itemOptions','location_id'));
        $this->set('_serialize', ['intraLocationStockTransferVoucher']);
    }
	
	public function ajaxItemQuantity($itemId=null)
    {
	    $this->viewBuilder()->layout('');
		$company_id=$this->Auth->User('session_company_id');
		$stateDetails=$this->Auth->User('session_company');
		$location_id=$this->Auth->User('session_location_id');
		$state_id=$stateDetails->state_id;
		$items = $this->IntraLocationStockTransferVouchers->IntraLocationStockTransferVoucherRows->Items->find()
					->where(['Items.company_id'=>$company_id, 'Items.id'=>$itemId])
					->contain(['Units'])->first();
					$itemUnit=$items->unit->name;
					
		
		$query = $this->IntraLocationStockTransferVouchers->IntraLocationStockTransferVoucherRows->Items->ItemLedgers->find()->where(['ItemLedgers.company_id'=>$company_id]);
		$totalInCase = $query->newExpr()
			->addCase(
				$query->newExpr()->add(['status' => 'In']),
				$query->newExpr()->add(['quantity']),
				'integer'
			);
		$totalOutCase = $query->newExpr()
			->addCase(
				$query->newExpr()->add(['status' => 'out']),
				$query->newExpr()->add(['quantity']),
				'integer'
			);
		$query->select([
			'total_in' => $query->func()->sum($totalInCase),
			'total_out' => $query->func()->sum($totalOutCase),'id','item_id'
		])
		->where(['ItemLedgers.item_id' => $itemId, 'ItemLedgers.company_id' => $company_id, 'ItemLedgers.location_id' => $location_id])
		->group('item_id')
		->autoFields(true)
		->contain(['Items']);
        $itemLedgers = ($query);
		
		
		if($itemLedgers->toArray())
		{
			  foreach($itemLedgers as $itemLedger){
				   $available_stock=$itemLedger->total_in;
				   $stock_issue=$itemLedger->total_out;
				 @$remaining=number_format($available_stock-$stock_issue, 2);
				 $mainstock=str_replace(',','',$remaining);
				 $stock='current stock is '. $remaining. ' ' .$itemUnit;
				 if($remaining>0)
				 {
				 $stockType='false';
				 }
				 else{
				 $stockType='true';
				 }
				 $h=array('text'=>$stock, 'type'=>$stockType, 'mainStock'=>$mainstock);
				 echo  $f=json_encode($h);
			  }
		  }
		  else{
		 
				 @$remaining=0;
				 $stock='current stock is '. $remaining. ' ' .$itemUnit;
				 if($remaining>0)
				 {
				 $stockType='false';
				 }
				 else{
				 $stockType='true';
				 }
				 $h=array('text'=>$stock, 'type'=>$stockType);
				 echo  $f=json_encode($h);
		  }
		  exit;
}	
    /**
     * Delete method
     *
     * @param string|null $id Intra Location Stock Transfer Voucher id.
     * @return \Cake\Http\Response|null Redirects to index.
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function delete($id = null)
    {
        $this->request->allowMethod(['post', 'delete']);
        $intraLocationStockTransferVoucher = $this->IntraLocationStockTransferVouchers->get($id);
        if ($this->IntraLocationStockTransferVouchers->delete($intraLocationStockTransferVoucher)) {
            $this->Flash->success(__('The intra location stock transfer voucher has been deleted.'));
        } else {
            $this->Flash->error(__('The intra location stock transfer voucher could not be deleted. Please, try again.'));
        }

        return $this->redirect(['action' => 'index']);
    }
	
		public function cancel($id = null)
    {
		// $this->request->allowMethod(['post', 'delete']);
        $intraLocationStockTransferVoucher = $this->IntraLocationStockTransferVouchers->get($id);
		$company_id=$this->Auth->User('session_company_id');
		//pr($salesInvoice);exit;
		$intraLocationStockTransferVoucher->cancel_status='cancel';
		
        if ($this->IntraLocationStockTransferVouchers->save($intraLocationStockTransferVoucher)) {
			$deleteItemLedger = $this->IntraLocationStockTransferVouchers->ItemLedgers->query();
				$deleteResult = $deleteItemLedger->delete()
					->where(['ItemLedgers.intra_location_stock_transfer_voucher_id' => $intraLocationStockTransferVoucher->id])
					->execute();
				
            $this->Flash->success(__('The intra location stock transfer voucher has been cancelled.'));
        } else {
            $this->Flash->error(__('The intra location stock transfer voucher could not be cancelled. Please, try again.'));
        }

        return $this->redirect(['action' => 'index']);
    }
}
