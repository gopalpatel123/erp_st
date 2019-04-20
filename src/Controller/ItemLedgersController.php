<?php
namespace App\Controller;

use App\Controller\AppController;

/**
 * ItemLedgers Controller
 *
 * @property \App\Model\Table\ItemLedgersTable $ItemLedgers
 *
 * @method \App\Model\Entity\ItemLedger[] paginate($object = null, array $settings = [])
 */
class ItemLedgersController extends AppController
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
            'contain' => ['Items']
        ];
        $itemLedgers = $this->paginate($this->ItemLedgers->find()->where(['ItemLedgers.company_id'=>$company_id]));

        $this->set(compact('itemLedgers'));
        $this->set('_serialize', ['itemLedgers']);
    }

    /**
     * View method
     *
     * @param string|null $id Item Ledger id.
     * @return \Cake\Http\Response|void
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function view($id = null)
    {
        $itemLedger = $this->ItemLedgers->get($id, [
            'contain' => ['Items']
        ]);

        $this->set('itemLedger', $itemLedger);
        $this->set('_serialize', ['itemLedger']);
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
		$itemLedger = $this->ItemLedgers->newEntity();
        if ($this->request->is('post')) {
            $itemLedger = $this->ItemLedgers->patchEntity($itemLedger, $this->request->getData());
            if ($this->ItemLedgers->save($itemLedger)) {
                $this->Flash->success(__('The item ledger has been saved.'));

                return $this->redirect(['action' => 'add']);
            }
            $this->Flash->error(__('The item ledger could not be saved. Please, try again.'));
        }
        $items = $this->ItemLedgers->Items->find('list')->where(['company_id'=>$company_id]);
        $this->set(compact('itemLedger', 'items'));
        $this->set('_serialize', ['itemLedger']);
    }

    /**
     * Edit method
     *
     * @param string|null $id Item Ledger id.
     * @return \Cake\Http\Response|null Redirects on successful edit, renders view otherwise.
     * @throws \Cake\Network\Exception\NotFoundException When record not found.
     */
    public function edit($id = null)
    {
        $this->viewBuilder()->layout('index_layout');
		$company_id=$this->Auth->User('session_company_id');
		$itemLedger = $this->ItemLedgers->get($id, [
            'contain' => []
        ]);
        if ($this->request->is(['patch', 'post', 'put'])) {
            $itemLedger = $this->ItemLedgers->patchEntity($itemLedger, $this->request->getData());
            if ($this->ItemLedgers->save($itemLedger)) {
                $this->Flash->success(__('The item ledger has been saved.'));

                return $this->redirect(['action' => 'index']);
            }
            $this->Flash->error(__('The item ledger could not be saved. Please, try again.'));
        }
        $items = $this->ItemLedgers->Items->find('list')->where(['company_id'=>$company_id]);
        $this->set(compact('itemLedger', 'items'));
        $this->set('_serialize', ['itemLedger']);
    }

    /**
     * Delete method
     *
     * @param string|null $id Item Ledger id.
     * @return \Cake\Http\Response|null Redirects to index.
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function delete($id = null)
    {
        $this->request->allowMethod(['post', 'delete']);
        $itemLedger = $this->ItemLedgers->get($id);
        if ($this->ItemLedgers->delete($itemLedger)) {
            $this->Flash->success(__('The item ledger has been deleted.'));
        } else {
            $this->Flash->error(__('The item ledger could not be deleted. Please, try again.'));
        }

        return $this->redirect(['action' => 'index']);
    }
	
	public function salesReturnReport()
    {
        $this->viewBuilder()->layout('index_layout');
		$status=$this->request->query('status'); 
		if(!empty($status)){ 
			$this->viewBuilder()->layout('excel_layout');	
		}else{ 
			$this->viewBuilder()->layout('index_layout');
		}
		
		$company_id=$this->Auth->User('session_company_id');
		$url=$this->request->here();
		$url=parse_url($url,PHP_URL_QUERY);
        $itemLedgers =$this->ItemLedgers->find()->where(['ItemLedgers.company_id'=>$company_id,'ItemLedgers.sale_return_id >' =>0])
		->contain(['Items','SaleReturns'=>['PartyLedgers']]);
		//pr($itemLedgers->toArray());
		//exit;
        $this->set(compact('itemLedgers','status','url'));
        $this->set('_serialize', ['itemLedgers']);
    }
	
	public function brandWiseReport()
    {
        $this->viewBuilder()->layout('index_layout');
		$status=$this->request->query('status');
		$company_id=$this->Auth->User('session_company_id');		
		if(!empty($status)){ 
			$this->viewBuilder()->layout('excel_layout');	
		}else{ 
			$this->viewBuilder()->layout('index_layout');
		}
		
		$from_date = $this->request->query('from_date');
		$to_date   = $this->request->query('to_date');
		$stock_group_id   = $this->request->query('stock_group_id');
		$where=[];
		if(!empty($from_date) || !empty($to_date))
		{
			$from_date = date("Y-m-d",strtotime($from_date));
			$to_date   = date("Y-m-d",strtotime($to_date));
			$where['SalesInvoices.transaction_date >=']= $from_date;
			$where['SalesInvoices.transaction_date <='] = $to_date;
		}
		else
		{ 
			 /* $from_date = date("Y-m-d",strtotime($this->coreVariable['fyValidFrom']));
			 $toDate    = $this->Ledgers->AccountingEntries->find()->order(['AccountingEntries.transaction_date'=>'DESC'])->First();
			@$to_date   = date("Y-m-d",strtotime($toDate->transaction_date)); */
		}
		if(!empty($stock_group_id)){
			
		}
		
		$salesInvoices = $this->ItemLedgers->SalesInvoices->find()->where(['SalesInvoices.company_id'=>$company_id])->where($where)
					->contain(['Companies', 'PartyLedgers'=>['Customers'], 'SalesLedgers', 'SalesInvoiceRows'=>
					['GstFigures','Items'=>function($e) use($stock_group_id){
							return $e->where(['Items.stock_group_id'=>$stock_group_id])
							->contain(['StockGroups'=>['ParentStockGroups'],'Sizes']);
							}]])
					->order(['voucher_no' => 'ASC']);
		pr($salesInvoices->toArray()); exit;
		
		$stockGroups = $this->ItemLedgers->Items->StockGroups->find('list');
		$company_id=$this->Auth->User('session_company_id');
		$url=$this->request->here();
		$url=parse_url($url,PHP_URL_QUERY);
        $itemLedgers =$this->ItemLedgers->find()->where(['ItemLedgers.company_id'=>$company_id,'ItemLedgers.sale_return_id >' =>0])
		->contain(['Items','SaleReturns'=>['PartyLedgers']]);
		//pr($itemLedgers->toArray());
		//exit;
        $this->set(compact('itemLedgers','status','url','from_date','to_date','stockGroups','stock_group_id'));
        $this->set('_serialize', ['itemLedgers']);
    }
	
	public function FetchData($item_id=null){
		$company_id=$this->Auth->User('session_company_id');
		$session_location_id =$this->Auth->User('session_location_id');
		$ItemLedgersData=$this->ItemLedgers->find()->where(['ItemLedgers.company_id'=>$company_id,'ItemLedgers.item_id'=>$item_id,'ItemLedgers.location_id'=>$session_location_id]);
		$this->set(compact('ItemLedgersData'));
        $this->set('_serialize', ['ItemLedgersData']);
		
	}
	
	public function inAllItem($id=null){
		 $this->viewBuilder()->layout('index_layout');
		$item_id=$this->request->query('id'); 
		
		$company_id=$this->Auth->User('session_company_id');
		$session_location_id =$this->Auth->User('session_location_id');
		$ItemLedgersData=$this->ItemLedgers->find()->where(['ItemLedgers.company_id'=>$company_id,'ItemLedgers.item_id'=>$item_id,'ItemLedgers.status'=>'In','ItemLedgers.grn_id IS NOT NULL'])->contain(['Grns'])->toArray();
		//pr($ItemLedgersData);exit;
		
		$this->set(compact('ItemLedgersData'));
        $this->set('_serialize', ['ItemLedgersData']);
		
		
	}
	
	
	public function stockReport()
    {
        $this->viewBuilder()->layout('index_layout');
		$status=$this->request->query('status'); 
		$total=$this->request->query('total'); 
		if(!empty($status)){ 
			$this->viewBuilder()->layout('excel_layout');	
		}else{ 
			$this->viewBuilder()->layout('index_layout');
		}
		$company_id=$this->Auth->User('session_company_id');
		$session_location_id =$this->Auth->User('session_location_id');
		$stock_group_id = $this->request->query('stock_group_id');
		$stock_sub_group_id = $this->request->query('stock_subgroup_id');
		$item_id = $this->request->query('item_id');
		
		$first_time="Yes";
		
		$where=[];
		if(!empty($stock_group_id)){
			$first_time="No";
			$Groups[]=$stock_group_id;
		$stockGroups = $this->ItemLedgers->Items->StockGroups->find('children', ['for' => $stock_group_id]);
		foreach($stockGroups as $stockGroup){
			$Groups[]=$stockGroup->id;
		}
		//pr($stockGroup->toArray()); exit;
		$where['Items.stock_group_id In']=$Groups;
		}
		if(empty($total)){ 
			$total='All';
			
		}
		if(!empty($stock_sub_group_id)){
			$first_time="No";
			$where['Items.stock_group_id']=$stock_sub_group_id;
		}
		if(!empty($item_id)){
			$first_time="No";
			$where['Items.id']=$item_id;
		}
		$to_date   = $this->request->query('to_date');
		if(!empty($to_date)){  
			$first_time="No";
			$to_date   = date("Y-m-d",strtotime($to_date));
		}
		else{
			$to_date   = date("Y-m-d");
		}
		
		
		$url=$this->request->here();
		$url=parse_url($url,PHP_URL_QUERY);
		//$x=['6176','6177','6173','6172','6174','6178','6179','6180','6025'];
		$this->paginate = [
            'limit' => 150,
            'max_limit'=>150
        ];
		
		
		if($first_time=="No"){ 
			 $Items=$this->ItemLedgers->Items->find()->contain(['Shades','Sizes','StockGroups'=>['ParentStockGroups']])->where($where)->where(['Items.company_id'=>$company_id ,'Items.created_on <='=>$to_date,'stock_finish'=>'No']); 
		    $remaining=[];$unit_rate=[];
		    foreach($Items as $item){
		        $dataexist=$this->ItemLedgers->exists(['ItemLedgers.item_id'=>$item->id,'ItemLedgers.location_id'=>$session_location_id ]);
				
				if($dataexist==1){
		          $data = $this->getdata($item->id,$company_id,$session_location_id,$to_date,$total,$remaining,$unit_rate);
				 
				  if($data){
					  $remaining = $data[0];
					$unit_rate = $data[1];
				  }
		           
				}
		    }
		}
		
		
		$companies=$this->ItemLedgers->Companies->find()->contain(['States'])->where(['Companies.id'=>$company_id])->first();
	
		$stockGroups = $this->ItemLedgers->Items->StockGroups->find('list')->where(['StockGroups.company_id'=>$company_id,'StockGroups.parent_id IS NULL']);
		$stockSubgroups=$this->ItemLedgers->Items->StockGroups->find('list')->where(['StockGroups.company_id'=>$company_id]);
		$stockItems=$this->ItemLedgers->Items->find('list')->where(['Items.company_id'=>$company_id]);
        $this->set(compact('companies','status','url','stockGroups','unit_rate','remaining','Items','stockItems','stockGroups','to_date','stockSubgroups','stock_sub_group_id','stock_group_id','total','first_time'));
        $this->set('_serialize', ['itemLedgers','Items','remaining']);
    }
	
	public function stockReportNew()
    {
        $this->viewBuilder()->layout('index_layout');
		$status=$this->request->query('status'); 
		$total=$this->request->query('total'); 
		if(!empty($status)){ 
			$this->viewBuilder()->layout('excel_layout');	
		}else{ 
			$this->viewBuilder()->layout('index_layout');
		}
		$company_id=$this->Auth->User('session_company_id');
		$session_location_id =$this->Auth->User('session_location_id');
		$stock_group_id = $this->request->query('stock_group_id');
		$stock_sub_group_id = $this->request->query('stock_subgroup_id');
		$item_id = $this->request->query('item_id');
		
		$first_time="Yes";
		
		$where=[];
		if(!empty($stock_group_id)){
			$first_time="No";
			$Groups[]=$stock_group_id;
		$stockGroups = $this->ItemLedgers->Items->StockGroups->find('children', ['for' => $stock_group_id]);
		foreach($stockGroups as $stockGroup){
			$Groups[]=$stockGroup->id;
		}
		//pr($stockGroup->toArray()); exit;
		$where['Items.stock_group_id In']=$Groups;
		}
		if(empty($total)){ 
			$total='All';
			
		}
		if(!empty($stock_sub_group_id)){
			$first_time="No";
			$where['Items.stock_group_id']=$stock_sub_group_id;
		}
		if(!empty($item_id)){
			$first_time="No";
			$where['Items.id']=$item_id;
		}
		$to_date   = $this->request->query('to_date');
		if(!empty($to_date)){  
			$first_time="No";
			$to_date   = date("Y-m-d",strtotime($to_date));
		}
		else{
			$to_date   = date("Y-m-d");
		}
		
		
		$url=$this->request->here();
		$url=parse_url($url,PHP_URL_QUERY);
		//$x=['6176','6177','6173','6172','6174','6178','6179','6180','6025'];
		$this->paginate = [
            'limit' => 50
        ];
		
		 $Items= $this->paginate($this->ItemLedgers->Items->find()->contain(['Shades','Sizes','StockGroups'=>['ParentStockGroups']])->where($where)->where(['Items.company_id'=>$company_id ,'Items.created_on <='=>$to_date,'stock_finish'=>'No'])); 
		//pr($Items); exit;
		
		$companies=$this->ItemLedgers->Companies->find()->contain(['States'])->where(['Companies.id'=>$company_id])->first();
		
	
		$stockGroups = $this->ItemLedgers->Items->StockGroups->find('list')->where(['StockGroups.company_id'=>$company_id,'StockGroups.parent_id IS NULL']);
		$stockSubgroups=$this->ItemLedgers->Items->StockGroups->find('list')->where(['StockGroups.company_id'=>$company_id]);
		$stockItems=$this->ItemLedgers->Items->find('list')->where(['Items.company_id'=>$company_id]);
		
		$Locations=$this->ItemLedgers->Companies->Locations->find()->where(['Locations.company_id'=>$company_id])->order(['Locations.name'=>'ASC']);
		
        $this->set(compact('companies','status','url','stockGroups','unit_rate','remaining','Items','stockItems','stockGroups','to_date','stockSubgroups','stock_sub_group_id','stock_group_id','total','first_time','Locations','item_id'));
        $this->set('_serialize', ['itemLedgers','Items','remaining']);
    }
	
	
	
	
	public function excelExport(){
	//echo "sdfdf";exit;
	     $this->viewBuilder()->layout('excel_layout');
		$total=$this->request->query('total'); 
		
		$company_id=$this->Auth->User('session_company_id');
		$session_location_id =$this->Auth->User('session_location_id');
		$stock_group_id = $this->request->query('stock_group_id');
		$stock_sub_group_id = $this->request->query('stock_subgroup_id');
		$item_id = $this->request->query('item_id');
		
		$first_time="Yes";
		
		$where=[];
		if(!empty($stock_group_id)){
			$first_time="No";
			$Groups[]=$stock_group_id;
		$stockGroups = $this->ItemLedgers->Items->StockGroups->find('children', ['for' => $stock_group_id]);
		foreach($stockGroups as $stockGroup){
			$Groups[]=$stockGroup->id;
		}
		//pr($stockGroup->toArray()); exit;
		$where['Items.stock_group_id In']=$Groups;
		}
		if(empty($total)){ 
			$total='All';
			
		}
		if(!empty($stock_sub_group_id)){
			$first_time="No";
			$where['Items.stock_group_id']=$stock_sub_group_id;
		}
		if(!empty($item_id)){
			$first_time="No";
			$where['Items.id']=$item_id;
		}
		$to_date   = $this->request->query('to_date');
		if(!empty($to_date)){  
			$first_time="No";
			$to_date   = date("Y-m-d",strtotime($to_date));
		}
		else{
			$to_date   = date("Y-m-d");
		}
		
		
		
		
		if($first_time=="No"){ 
			 $Items=$this->ItemLedgers->Items->find()->contain(['Shades','Sizes','StockGroups'=>['ParentStockGroups']])->where($where)->where(['Items.company_id'=>$company_id ,'Items.created_on <='=>$to_date,'stock_finish'=>'No']); 
			 $ItemsDatas=$this->ItemLedgers->Items->find()->contain(['Shades','Sizes','StockGroups'=>['ParentStockGroups']])->where($where)->where(['Items.company_id'=>$company_id ,'Items.created_on <='=>$to_date,'stock_finish'=>'No']); 
		    $remaining=[];$unit_rate=[];
		    foreach($ItemsDatas as $item){
		        $dataexist=$this->ItemLedgers->exists(['ItemLedgers.item_id'=>$item->id,'ItemLedgers.location_id'=>$session_location_id ]);
				if($dataexist==1){ 
		          $data = $this->getdata($item->id,$company_id,$session_location_id,$to_date,$total,$remaining,$unit_rate);
		           $remaining = $data[0];
		            $unit_rate = $data[1];
				}
		    }
		  
		  // pr($remaining);exit;
		   
		}
		
		$companies=$this->ItemLedgers->Companies->find()->contain(['States'])->where(['Companies.id'=>$company_id])->first();
        $this->set(compact('companies','status','url','stockGroups','unit_rate','remaining','Items','stockItems','stockGroups','to_date','stockSubgroups','stock_sub_group_id','stock_group_id','total','first_time'));
        $this->set('_serialize', ['itemLedgers','Items','remaining','unit_rate']);
	}
	
	
	public function getdata($item_id,$company_id,$session_location_id,$to_date,$total,$remaining,$unit_rate){
	   
			$purchaseRate=$this->ItemLedgers->find()->where(['ItemLedgers.company_id'=>$company_id,'ItemLedgers.transaction_date <='=>$to_date,'ItemLedgers.item_id'=>$item_id,'ItemLedgers.status'=>'in','ItemLedgers.grn_id >'=>0])->first();
			
			$queryIn=$this->ItemLedgers->find()->where(['ItemLedgers.company_id'=>$company_id,'ItemLedgers.location_id'=>$session_location_id,'ItemLedgers.transaction_date <='=>$to_date,'ItemLedgers.item_id'=>$item_id,'ItemLedgers.status'=>'in']);
			
			$queryIn->select(['item_id','totalIn' => $queryIn->func()->sum('ItemLedgers.quantity')])->group('ItemLedgers.item_id');
			//pr($session_location_id);pr($queryIn->first());
			
			$queryOut=$this->ItemLedgers->find()->where(['ItemLedgers.company_id'=>$company_id,'ItemLedgers.location_id'=>$session_location_id,'ItemLedgers.transaction_date <='=>$to_date,'ItemLedgers.item_id'=>$item_id,'ItemLedgers.status'=>'out']);
			$queryOut->select(['item_id','totalOut' => $queryOut->func()->sum('ItemLedgers.quantity')])->group('ItemLedgers.item_id');
			//pr($queryOut->first()); exit;
			$due=(@$queryIn->toArray()[0]->totalIn-@$queryOut->toArray()[0]->totalOut);
				 
			if($total=='Zero' && @$due == 0){ 
					$remaining[$item_id]=round(@$queryIn->toArray()[0]->totalIn-@$queryOut->toArray()[0]->totalOut,2);
					$unit_rate[$item_id]=	0;
				}else if($total=='Positive' && @$due > 0){
					$remaining[$item_id]=round(@$queryIn->toArray()[0]->totalIn-@$queryOut->toArray()[0]->totalOut,2);
					$unit_rate[$item_id]=	@$purchaseRate->rate;
				}else if($total=='All'){ 
					$remaining[$item_id]=round(@$queryIn->toArray()[0]->totalIn-@$queryOut->toArray()[0]->totalOut,2);
					$unit_rate[$item_id]=	@$purchaseRate->rate;
				}
			$a=[];
		    $a[]=$remaining;
		    $a[]=$unit_rate; 
		    return ($a);
	}

	public function getItemLocationWise(){
		$to_date=date("Y-m-d");
		$company_id=$this->Auth->User('session_company_id');
		$session_location_id =$this->Auth->User('session_location_id');
		$item_id=$this->request->query('item_id');
		
		$Locations=$this->ItemLedgers->Companies->Locations->find()->where(['Locations.company_id'=>$company_id])->order(['Locations.name'=>'ASC']);
		
		$purchaseRate=$this->ItemLedgers->find()->where(['ItemLedgers.company_id'=>$company_id,'ItemLedgers.transaction_date <='=>$to_date,'ItemLedgers.item_id'=>$item_id,'ItemLedgers.status'=>'in','ItemLedgers.grn_id >'=>0])->first();
		$total_Stock=[];
		foreach($Locations as $Locations){
			$session_location_id=$Locations->id;
			$queryIn=$this->ItemLedgers->find()->where(['ItemLedgers.company_id'=>$company_id,'ItemLedgers.location_id'=>$session_location_id,'ItemLedgers.transaction_date <='=>$to_date,'ItemLedgers.item_id'=>$item_id,'ItemLedgers.status'=>'in']);

			$queryIn->select(['item_id','totalIn' => $queryIn->func()->sum('ItemLedgers.quantity')])->group('ItemLedgers.item_id');
			//pr($session_location_id);pr($queryIn->first());

			$queryOut=$this->ItemLedgers->find()->where(['ItemLedgers.company_id'=>$company_id,'ItemLedgers.location_id'=>$session_location_id,'ItemLedgers.transaction_date <='=>$to_date,'ItemLedgers.item_id'=>$item_id,'ItemLedgers.status'=>'out']);
			$queryOut->select(['item_id','totalOut' => $queryOut->func()->sum('ItemLedgers.quantity')])->group('ItemLedgers.item_id');
			
			$due=(@$queryIn->toArray()[0]->totalIn-@$queryOut->toArray()[0]->totalOut);

			$total_Stock[$session_location_id]=round(@$queryIn->toArray()[0]->totalIn-@$queryOut->toArray()[0]->totalOut,2);
			$unit_rate[$item_id]=	@$purchaseRate->rate;
		}
		
		
		//$total_Stock=['2','3','4'];
		//pr($total_Stock); exit;
		$this->set(compact('total_Stock'));
      //  $this->set('_serialize', ['serialNumbers']);
	}
	
	public function itemStockFinish()
    {
        $this->viewBuilder()->layout('index_layout');
		$status=$this->request->query('status'); 
		$total=$this->request->query('total'); 
		if(!empty($status)){ 
			$this->viewBuilder()->layout('excel_layout');	
		}else{ 
			$this->viewBuilder()->layout('index_layout');
		}
		$company_id=3;
		$session_location_id =$this->Auth->User('session_location_id');
		$stock_group_id = $this->request->query('stock_group_id');
		$stock_sub_group_id = $this->request->query('stock_subgroup_id');
		
		$first_time="Yes";
		
		$where=[];
		if(!empty($stock_group_id)){
			$first_time="No";
			$Groups[]=$stock_group_id;
		$stockGroups = $this->ItemLedgers->Items->StockGroups->find('children', ['for' => $stock_group_id]);
		foreach($stockGroups as $stockGroup){
			$Groups[]=$stockGroup->id;
		}
		//pr($stockGroup->toArray()); exit;
		$where['Items.stock_group_id In']=$Groups;
		}
		if(empty($total)){ 
			$total='All';
			
		}
		
		$to_date   = date("Y-m-d");
		
		
		
		
		//$x=['6176','6177','6173','6172','6174','6178','6179','6180','6025'];
			$y=0;
			$n=0;
			$i=0;
			$Items=$this->ItemLedgers->Items->find()->where(['Items.company_id'=>$company_id ,'Items.created_on <='=>$to_date,'stock_finish'=>'Yes'])->order(['Items.id'=>'DESC'])->toArray(); 
			//pr($Items); exit; 
			
			$remaining=[];$unit_rate=[];$stock=[];
			foreach($Items as $Item){
				$dataexist=$this->ItemLedgers->exists(['ItemLedgers.item_id'=>$Item->id,'item_stock_finish'=>'Yes']);
				if($dataexist==1){ 
					
					/* $queryIn=$this->ItemLedgers->find()->where(['ItemLedgers.company_id'=>$company_id,'ItemLedgers.transaction_date <='=>$to_date,'ItemLedgers.item_id'=>$Item->id,'ItemLedgers.status'=>'in']);
					$queryIn->select(['item_id','totalIn' => $queryIn->func()->sum('ItemLedgers.quantity')])->group('ItemLedgers.item_id');
					
					$queryOut=$this->ItemLedgers->find()->where(['ItemLedgers.company_id'=>$company_id,'ItemLedgers.transaction_date <='=>$to_date,'ItemLedgers.item_id'=>$Item->id,'ItemLedgers.status'=>'out']);
					$queryOut->select(['item_id','totalOut' => $queryOut->func()->sum('ItemLedgers.quantity')])->group('ItemLedgers.item_id');
					
					$due=(@$queryIn->toArray()[0]->totalIn-@$queryOut->toArray()[0]->totalOut);
					if($due > 0){ 
						$query7 = $this->ItemLedgers->query();
						$query7->update()
						->set(['item_stock_finish' => 'No'])
						->where(['ItemLedgers.company_id'=>$company_id,'ItemLedgers.transaction_date <='=>$to_date,'ItemLedgers.item_id'=>$Item->id])
						->execute();
						
						$query7 = $this->ItemLedgers->Items->query();
						$query7->update()
						->set(['stock_finish' => 'No'])
						->where(['Items.company_id'=>$company_id,'Items.id'=>$Item->id])
						->execute();
						
					}else{
						$y++;
					} */
				}else{
				   $query7 = $this->ItemLedgers->Items->query();
						$query7->update()
						->set(['stock_finish' => 'No'])
						->where(['Items.company_id'=>$company_id,'Items.id'=>$Item->id])
						->execute();
				}
			} 
		pr($n);
		pr($y);
		pr($i);
		exit;
    }
	
		

}
