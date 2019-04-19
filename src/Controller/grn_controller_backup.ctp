
Currently editing:  
/home/wwwarmslife/public_html/sunil_textile_erp/src/Controller/GrnsController.php
 Encoding:    Reopen  Switch to Code Editor     Close  Save

<?php
namespace App\Controller;

use App\Controller\AppController;

/**
 * Grns Controller
 *
 * @property \App\Model\Table\GrnsTable $Grns
 *
 * @method \App\Model\Entity\Grn[] paginate($object = null, array $settings = [])
 */
class GrnsController extends AppController
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
            'contain' => ['Companies','SupplierLedgers']
        ];
        $grns = $this->paginate($this->Grns->find()->where(['Grns.company_id'=>$company_id])->where([
		'OR' => [
            'Grns.voucher_no' => $search,
            // ...
            'SupplierLedgers.name LIKE' => '%'.$search.'%',
			//.....
			'Grns.reference_no LIKE' => '%'.$search.'%',
			//...
			'Grns.transaction_date ' => date('Y-m-d',strtotime($search))
		
        ]]));
		
        $this->set(compact('grns','search'));
        $this->set('_serialize', ['grns']);
    }

    /**
     * View method
     *
     * @param string|null $id Grn id.
     * @return \Cake\Http\Response|void
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function view($id = null)
    {
		$this->viewBuilder()->layout('index_layout');
		$company_id=$this->Auth->User('session_company_id');
        $grn = $this->Grns->get($id, [
            'contain' => ['Companies','GrnRows'=>['Items']]
        ]);
		if($grn->supplier_ledger_id){
		$supplier_details= $this->Grns->GrnRows->Ledgers->get($grn->supplier_ledger_id);
		$supplier_ledger=$supplier_details->name;
		}else{
		$supplier_ledger=''	;
		}
		
		$this->set(compact('grn','supplier_ledger'));
		$this->set('_serialize', ['grn']);
    }
	
	 public function printBarcode($id = null)
    {
		$this->viewBuilder()->layout('');
		$company_id=$this->Auth->User('session_company_id');
        $grn = $this->Grns->get($id, [
            'contain' => ['Companies', 'GrnRows'=>['Items'=>['Shades','Sizes']]]
        ]);
		
        $this->set('grn', $grn);
        $this->set('_serialize', ['grn']);
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
		$location_id=$this->Auth->User('session_location_id');
        $grn = $this->Grns->newEntity();
		$this->request->data['company_id'] =$company_id;
        if ($this->request->is('post')) 
		{
			$grn = $this->Grns->patchEntity($grn, $this->request->getData());
			$grn->transaction_date = date("Y-m-d",strtotime($this->request->getData()['transaction_date']));
			$Voucher_no = $this->Grns->find()->select(['voucher_no'])->where(['company_id'=>$company_id])->order(['voucher_no' => 'DESC'])->first();
			if($Voucher_no)
			{
				$grn->voucher_no = $Voucher_no->voucher_no+1;
			}
			else
			{
				$grn->voucher_no = 1;
			} 
			$grn->location_id =$location_id;
            if ($this->Grns->save($grn)) 
			{
				//Create Item_Ledger//
				foreach($grn->grn_rows as $grn_row)
				{
					$item_ledger = $this->Grns->ItemLedgers->newEntity();
					$item_ledger->transaction_date = $grn->transaction_date;
					$item_ledger->grn_id = $grn->id;
					$item_ledger->grn_row_id = $grn_row->id;
					$item_ledger->item_id = $grn_row->item_id;
					$item_ledger->quantity = $grn_row->quantity;
					$item_ledger->rate = $grn_row->purchase_rate;
					$item_ledger->sale_rate = $grn_row->sale_rate;
					$item_ledger->company_id  =$company_id;
					$item_ledger->location_id =$location_id;
					$item_ledger->status ='in';
					$item_ledger->amount=$grn_row->quantity*$grn_row->purchase_rate;
					$this->Grns->ItemLedgers->save($item_ledger);
					$item = $this->Grns->GrnRows->Items->find()->where(['Items.id'=>$grn_row->item_id])->first();
					
					
					if($item)
					{
						if($grn->transaction_date >= date("Y-m-d",strtotime($item->sales_rate_update_on)))
						{
							$query = $this->Grns->GrnRows->Items->query();
							$query->update()
									->set(['sales_rate' => $grn_row->sale_rate, 'sales_rate_update_on' => $grn->transaction_date])
									->where(['id' =>$grn_row->item_id])
									->execute();
						}
					}
				}
                $this->Flash->success(__('The grn has been saved.'));
                return $this->redirect(['action' => 'add']);
            }
            $this->Flash->error(__('The grn could not be saved. Please, try again.'));
        }
		$items = $this->Grns->GrnRows->Items->find()
					->where(['Items.company_id'=>$company_id])
					->contain(['GstFigures']);
		$itemOptions=[];
		foreach($items as $item)
		{
			$itemOptions[]=['text' =>$item->item_code.' '.$item->name, 'value' => $item->id, 'gst_figure_tax_name'=>@$item->gst_figure->name];
		}
		$Voucher_no = $this->Grns->find()->select(['voucher_no'])->where(['company_id'=>$company_id])->order(['voucher_no' => 'DESC'])->first();
		if($Voucher_no)
		{
			$voucher_no=$Voucher_no->voucher_no+1;
		}
		else
		{ 
			$voucher_no=1;
		} 
        //$locations = $this->Grns->Locations->find('list', ['limit' => 200]);
		 $partyParentGroups = $this->Grns->GrnRows->Ledgers->AccountingGroups->find()
						->where(['AccountingGroups.company_id'=>$company_id, 'AccountingGroups.
						supplier'=>'1']);
		$partyGroups=[];
		
		foreach($partyParentGroups as $partyParentGroup)
		{
			$accountingGroups = $this->Grns->GrnRows->Ledgers->AccountingGroups
			->find('children', ['for' => $partyParentGroup->id])->toArray();
			$partyGroups[]=$partyParentGroup->id;
			foreach($accountingGroups as $accountingGroup){
				$partyGroups[]=$accountingGroup->id;
			}
		}
		if($partyGroups)
		{  
			$Partyledgers = $this->Grns->SupplierLedgers->find()
							->where(['SupplierLedgers.accounting_group_id IN' =>$partyGroups,'SupplierLedgers.company_id'=>$company_id])
							->contain(['Suppliers']);
        }
		
		$partyOptions=[];
		foreach($Partyledgers as $Partyledger){
			$partyOptions[]=['text' =>$Partyledger->name, 'value' => $Partyledger->id];
		}
		
        $companies = $this->Grns->Companies->find('list');
        $this->set(compact('grn','companies','voucher_no','itemOptions','partyOptions'));
        $this->set('_serialize', ['grn']);
    }

    /**
     * Edit method
     *
     * @param string|null $id Grn id.
     * @return \Cake\Http\Response|null Redirects on successful edit, renders view otherwise.
     * @throws \Cake\Network\Exception\NotFoundException When record not found.
     */
    public function edit($id = null)
    {
	
		$this->viewBuilder()->layout('index_layout');
		$company_id=$this->Auth->User('session_company_id');
		$location_id=$this->Auth->User('session_location_id');
        $grn = $this->Grns->get($id, [
            'contain' => ['GrnRows']
        ]);
	
			
        if ($this->request->is(['patch', 'post', 'put'])) {
            $grn = $this->Grns->patchEntity($grn, $this->request->getData());
			$grn->transaction_date = date("Y-m-d",strtotime($this->request->getData()['transaction_date']));
			$grn->location_id =$location_id;
            if ($this->Grns->save($grn)) 
			{
				$query = $this->Grns->ItemLedgers->query();
				$query->delete()->where(['grn_id'=> $id,'company_id'=>$company_id])->execute();
				foreach($grn->grn_rows as $grn_row)
				{
					$item_ledger = $this->Grns->ItemLedgers->newEntity();
					$item_ledger->transaction_date = $grn->transaction_date;
					$item_ledger->grn_id = $grn->id;
					$item_ledger->grn_row_id = $grn_row->id;
					$item_ledger->item_id = $grn_row->item_id;
					$item_ledger->quantity = $grn_row->quantity;
					$item_ledger->rate = $grn_row->purchase_rate;
					$item_ledger->sale_rate = $grn_row->sale_rate;
					$item_ledger->company_id =$company_id;
					$item_ledger->location_id =$location_id;
					$item_ledger->status ='in';
					$item_ledger->amount=$grn_row->quantity*$grn_row->purchase_rate;
					$this->Grns->ItemLedgers->save($item_ledger);
					$item = $this->Grns->GrnRows->Items->find()->where(['Items.id'=>$grn_row->item_id])->first();
					if($item)
					{
						if($grn->transaction_date >= date("Y-m-d",strtotime($item->sales_rate_update_on)))
						{
							$query = $this->Grns->GrnRows->Items->query();
							$query->update()
									->set(['sales_rate' => $grn_row->sale_rate, 'sales_rate_update_on' => $grn->transaction_date])
									->where(['id' =>$grn_row->item_id])
									->execute();
						}
					}
					
				}
				
				$this->Flash->success(__('The grn has been saved.'));

                return $this->redirect(['action' => 'index']);
            }
			exit;
            $this->Flash->error(__('The grn could not be saved. Please, try again.'));
        }
		$items = $this->Grns->GrnRows->Items->find()
					->where(['Items.company_id'=>$company_id])
					->contain(['GstFigures']);
				
		$itemOptions=[];
		foreach($items as $item){
			$itemOptions[]=['text' =>$item->item_code.' '.$item->name, 'value' => $item->id ,'gst_figure_id'=>$item->gst_figure_id, 'gst_figure_tax_name'=>@$item->gst_figure->name];
		}
		$partyParentGroups = $this->Grns->GrnRows->Ledgers->AccountingGroups->find()
						->where(['AccountingGroups.company_id'=>$company_id, 'AccountingGroups.
						supplier'=>'1']);
		$partyGroups=[];
		
		foreach($partyParentGroups as $partyParentGroup)
		{
			$accountingGroups = $this->Grns->GrnRows->Ledgers->AccountingGroups
			->find('children', ['for' => $partyParentGroup->id])->toArray();
			$partyGroups[]=$partyParentGroup->id;
			foreach($accountingGroups as $accountingGroup){
				$partyGroups[]=$accountingGroup->id;
			}
		}
		if($partyGroups)
		{  
			$Partyledgers = $this->Grns->SupplierLedgers->find()
							->where(['SupplierLedgers.accounting_group_id IN' =>$partyGroups,'SupplierLedgers.company_id'=>$company_id])
							->contain(['Suppliers']);
        }
		
		$partyOptions=[];
		foreach($Partyledgers as $Partyledger){
			$partyOptions[]=['text' =>$Partyledger->name, 'value' => $Partyledger->id];
		}
        //$locations = $this->Grns->Locations->find('list', ['limit' => 200]);
        $companies = $this->Grns->Companies->find('list');
        $this->set(compact('grn','companies','itemOptions','partyOptions'));
        $this->set('_serialize', ['grn']);
    }

    /**
     * Delete method
     *
     * @param string|null $id Grn id.
     * @return \Cake\Http\Response|null Redirects to index.
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function delete($id = null)
    {
		$this->viewBuilder()->layout('index_layout');
        $this->request->allowMethod(['post', 'delete']);
        $grn = $this->Grns->get($id);
        if ($this->Grns->delete($grn)) {
            $this->Flash->success(__('The grn has been deleted.'));
        } else {
            $this->Flash->error(__('The grn could not be deleted. Please, try again.'));
        }

        return $this->redirect(['action' => 'index']);
    }
	
	public function importCsv()
	{
		$this->viewBuilder()->layout('index_layout');
		$company_id=$this->Auth->User('session_company_id');
		$import_csv = $this->Grns->newEntity();
		$units = $this->Grns->GrnRows->Items->Units->find()->where(['company_id'=>$company_id]);
		$shades = $this->Grns->GrnRows->Items->Shades->find()->where(['company_id'=>$company_id]);
		$sizes = $this->Grns->GrnRows->Items->Sizes->find()->where(['company_id'=>$company_id]);
		$this->set(compact('import_csv','units','shades','sizes'));
        $this->set('_serialize', ['import_csv']);
	}

	public function import()
	{
		$this->viewBuilder()->layout('index_layout');
		$grn = $this->Grns->newEntity();
		$user_id=$this->Auth->User('id');
		if ($this->request->is('post')) 
		{
			
			$csv = $this->request->data['csv'];
			if(!empty($csv['tmp_name']))
			{
				$ext = substr(strtolower(strrchr($csv['name'], '.')), 1); //get the extension 
				
				$arr_ext = array('csv'); 									   
				if (in_array($ext, $arr_ext)) 
				{
                  move_uploaded_file($csv['tmp_name'], WWW_ROOT . '/step_first/'.$user_id.'.'.$ext);
				  
				  $f = fopen($csv['tmp_name'], 'r') or die("ERROR OPENING DATA");
					$records=0;
					while (($line = fgetcsv($f, 4096, ';')) !== false) 
					{
						$test[]=$line;
						++$records;
					}
					foreach($test as $test1)
					{ 
					
						 $data = explode(",",$test1[0]);
						 $item = $this->Items->newEntity();
						 $item->name           = $data[0];
						 $item->item_code      = trim($data[1]); 
						 $item->hsn_code       = $data[2];
						 $item->unit_id        = $data[3];
						 $item->stock_group_id = $data[4];
						 $item->company_id     = $data[5];
						 $this->Items->save($item);
					} 
					fclose($f);
					$records;
				}
			}
		} 
		$this->set(compact('grn'));
        $this->set('_serialize', ['grn']);
	}



	public function importStep2()
	{
		$this->viewBuilder()->layout('index_layout');
		$grn = $this->Grns->SecondTampGrnRecords->newEntity();
		$user_id=$this->Auth->User('id');
		$company_id=$this->Auth->User('session_company_id');
		$location_id=$this->Auth->User('session_location_id');
		
		$countSecondTampGrnRecords=$this->Grns->SecondTampGrnRecords->find()
									->where(['user_id'=>$user_id,'company_id'=>$company_id,'import_to_grn'=>'no'])->count();
		if($countSecondTampGrnRecords>0){
			$notvalid_to_importRecords=$this->Grns->SecondTampGrnRecords->find()
										->where(['user_id'=>$user_id,'company_id'=>$company_id,'valid_to_import'=>'no'])->count();
		}
		
		if ($this->request->is('post')) 
		{
			$query = $this->Grns->SecondTampGrnRecords->query();
				$query->delete()->where(['user_id'=> $user_id,'company_id'=>$company_id])->execute();
			
			$csv = $this->request->data['csv'];
			if(!empty($csv['tmp_name']))
			{
				$ext = substr(strtolower(strrchr($csv['name'], '.')), 1); //get the extension 
				
				$arr_ext = array('csv'); 									   
				if (in_array($ext, $arr_ext)) 
				{
                  //move_uploaded_file($csv['tmp_name'], WWW_ROOT . '/step_second/'.$user_id.'.'.$ext);
				  
				  $f = fopen($csv['tmp_name'], 'r') or die("ERROR OPENING DATA");
					$records=0;
					while (($line = fgetcsv($f, 4096, ';')) !== false) 
					{
						$test[]=$line;
						++$records;
					}
					foreach($test as $key => $test1)
					{ 
						
						$data = explode(",",$test1[0]);
						
						if($key!=0)
						{
							$second_tamp_grn_records = $this->Grns->SecondTampGrnRecords->newEntity();
							$second_tamp_grn_records->item_code      = $data[0]; 
							$second_tamp_grn_records->quantity       = $data[1];
							$second_tamp_grn_records->purchase_rate  = $data[2];
							$second_tamp_grn_records->sales_rate     = $data[3];
							$second_tamp_grn_records->is_addition_item_data_required = $data[4];
							$second_tamp_grn_records->item_name      = @$data[5]; 
							$second_tamp_grn_records->hsn_code       = @$data[6];
							$second_tamp_grn_records->provided_unit  = @$data[7];
							$second_tamp_grn_records->gst_rate_fixed_or_fluid = @$data[8];
							$second_tamp_grn_records->first_gst_rate = @$data[9];
							$second_tamp_grn_records->amount_in_ref_of_gst_rate = @$data[10];
							$second_tamp_grn_records->second_gst_rate = @$data[11];
							$second_tamp_grn_records->provided_shade = @$data[12];
							$second_tamp_grn_records->provided_size = @$data[13];
							$second_tamp_grn_records->description = @$data[14];
							$second_tamp_grn_records->stock_group = @$data[15];
							$second_tamp_grn_records->processed      = 'no'; 
							$second_tamp_grn_records->user_id        = $user_id;
							$second_tamp_grn_records->company_id = $company_id;
							$second_tamp_grn_records->location_id = 1;
							$this->Grns->SecondTampGrnRecords->save($second_tamp_grn_records);
						}						
					} 
					$this->redirect(array("controller" => "SecondTampGrnRecords", 
                    "action" => "progress"));
					fclose($f);
					$records;
				}
				else{
					$this->Flash->error(__('The File Format is incorrect (not CSV type). Please, try again.'));	
				}
			}
		} 
		$this->set(compact('grn', 'countSecondTampGrnRecords', 'notvalid_to_importRecords'));
        $this->set('_serialize', ['grn']);
	}
	public function PurchaseInvoiceAdd()
	{
		$this->viewBuilder()->layout('index_layout');
		$company_id=$this->Auth->User('session_company_id');
		$stateDetails=$this->Auth->User('session_company');
		@$grn_no=$this->request->query('grn_no');
		$PurchaseInvoice="No";
		if(!empty(@$grn_no)){ 
		$Grns = $this->Grns->find()
						->where(['Grns.voucher_no' =>$grn_no,'Grns.company_id'=>$company_id])
						->contain(['Companies', 'SupplierLedgers'])
						->first();
		//pr($SalesInvoice->party_ledger->name); 
		
		$PurchaseInvoice="Yes";
		}	

		$this->set(compact('Grns','PurchaseInvoice'));
		
	}

}


