<?php
namespace App\Controller;

use App\Controller\AppController;

/**
 * PurchaseInvoices Controller
 *
 * @property \App\Model\Table\PurchaseInvoicesTable $PurchaseInvoices
 *
 * @method \App\Model\Entity\PurchaseInvoice[] paginate($object = null, array $settings = [])
 */
class PurchaseInvoicesController extends AppController
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
            'contain' => ['Companies', 'SupplierLedgers','Grns'],
			'limit' => 100
        ];
		$item_id = $this->request->query('item_id');
		$where1=[];
		$where=[];
		if(!empty($item_id)){
			$where1['PurchaseInvoiceRows.item_id']=$item_id;
		}
		$where['PurchaseInvoices.company_id']=$company_id;
		$where['PurchaseInvoices.financial_year_id']=$financialYear_id;
		
        $purchaseInvoices = $this->paginate($this->PurchaseInvoices->find()
		->where($where)
		->contain(['PurchaseInvoiceRows'])
		->matching(
				'PurchaseInvoiceRows.Items', function ($q) use($where1) {
					return $q->where($where1);
				})
		->where([
		'OR' => [
            'PurchaseInvoices.voucher_no' => $search,
            // ...
            'Grns.voucher_no' => $search,
			//.....
			'SupplierLedgers.name LIKE' => '%'.$search.'%',
			//...
			'PurchaseInvoices.transaction_date ' => date('Y-m-d',strtotime($search))
		 ]])->group(['PurchaseInvoices.id'])->order(['Grns.id'=>'DESC']));
		//pr($purchaseInvoices); exit;
		$stockItems=$this->PurchaseInvoices->PurchaseInvoiceRows->Items->find('list')->where(['Items.company_id'=>$company_id]);
        $this->set(compact('purchaseInvoices','search','stockItems','item_id'));
        $this->set('_serialize', ['purchaseInvoices']);
    }

	
		public function hsnWiseReport(){
		$status=$this->request->query('status'); 
		$url=$this->request->here();
		$url=parse_url($url,PHP_URL_QUERY);
		
		if(!empty($status)){ 
			$this->viewBuilder()->layout('excel_layout');	
		}else{ 
			$this->viewBuilder()->layout('index_layout');
		}
		$company_id=$this->Auth->User('session_company_id');
		$stateDetails=$this->Auth->User('session_company');
		
		$From=$this->request->query('From');
		$To=$this->request->query('To');
		
		$where=[];
		if(!empty($From)){
			$From=date("Y-m-d",strtotime($this->request->query('From')));
			$where['PurchaseInvoices.transaction_date >=']=$From;
		}
		if(!empty($To)){
			$To=date("Y-m-d",strtotime($this->request->query('To')));
			$where['PurchaseInvoices.transaction_date <=']=$To;
		}
		
		$SalesInvoices =$this->PurchaseInvoices->find()->contain(['PurchaseInvoiceRows'=>['Items'=>['Units','StockGroups']]])->where($where)->where(['company_id'=>$company_id]);
		$hsn=[];
		$quantity=[];
		$taxable_value=[];
		$item_category=[];
		$total_value=[];
		$unit=[];
		$gst=[];
		
		foreach($SalesInvoices as $Invoice){ 
			foreach($Invoice->purchase_invoice_rows as $invoice_row){  
				$hsn[$invoice_row->item->hsn_code]=$invoice_row->item->hsn_code;
				$item_category[$invoice_row->item->hsn_code]=@$invoice_row->item->stock_group->name;
				$unit[$invoice_row->item->hsn_code]=$invoice_row->item->unit->name;
				@$quantity[@$invoice_row->item->hsn_code]+=@$invoice_row->quantity;
				@$total_value[@$invoice_row->item->hsn_code]+=@$invoice_row->net_amount;
				@$taxable_value[@$invoice_row->item->hsn_code]+=@$invoice_row->taxable_value;
				@$gst[@$invoice_row->item->hsn_code]+=@$invoice_row->gst_value;
			}
		}
		if(empty($From)){
			$From=date("Y-m-d");
			$To=date("Y-m-d");
		}
		//pr($hsn); exit;
		$this->set(compact('url','status','From','To','hsn','item_category','unit','quantity','taxable_value','gst','total_value'));
		
	}
    /**
     * View method
     *
     * @param string|null $id Purchase Invoice id.
     * @return \Cake\Http\Response|void
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function view($id = null)
    {
		$this->viewBuilder()->layout('index_layout');
		$company_id=$this->Auth->User('session_company_id');
		$stateDetails=$this->Auth->User('session_company');
		$state_id=$stateDetails->state_id;
        $purchaseInvoice = $this->PurchaseInvoices->get($id, [
            'contain' => ['Companies'=>['States'], 'Grns','SupplierLedgers'=>['Suppliers'], 'PurchaseInvoiceRows'=>['Items']]
        ]);
		$supplier_state_id=$purchaseInvoice->supplier_ledger->supplier->state_id;
		//pr($purchaseInvoice->toArray());
		//exit;
		$this->set(compact('purchaseInvoice','supplier_state_id','state_id'));
       
        $this->set('_serialize', ['purchaseInvoice']);
    }
	
	public function DateUpdate()
	{
		$company_id=$this->Auth->User('session_company_id');
		$PurchaseInvoices = $this->PurchaseInvoices->find();
		foreach($PurchaseInvoices as $PurchaseInvoice){
			$AccountSecondSubgroupsexists = $this->PurchaseInvoices->ReferenceDetails->exists(['purchase_invoice_id' => $PurchaseInvoice->id,'company_id'=>$company_id]);
			if($AccountSecondSubgroupsexists==1){
			
			$query = $this->PurchaseInvoices->ReferenceDetails->query();
				$query->update()
					->set(['transaction_date'=>$PurchaseInvoice->transaction_date])
					->where(['purchase_invoice_id' => $PurchaseInvoice->id])
					->execute();
			}
			//pr($PurchaseInvoice); exit;
		}  exit;
	}

    /**
     * Add method
     *
     * @return \Cake\Http\Response|null Redirects on successful add, renders view otherwise.
     */
    public function add($id=null)
    {
		$this->viewBuilder()->layout('index_layout');
		$company_id=$this->Auth->User('session_company_id');
		$location_id=$this->Auth->User('session_location_id');
		$stateDetails=$this->Auth->User('session_company');
		$financialYear_id=$this->Auth->User('financialYear_id');
		$state_id=$stateDetails->state_id;
        $Grns = $this->PurchaseInvoices->Grns->get($id, [
            'contain' => (['GrnRows'=>['Items'=>['FirstGstFigures','SecondGstFigures']],'SupplierLedgers'])
        ]);
		
		$supplier_status="False";
		if($Grns->supplier_ledger_id==0){
			$supplier_status="True";
			goto go;
		}
		 $supplier_ledger_id=$Grns->supplier_ledger_id;
		$Voucher_no_last = $this->PurchaseInvoices->find()->select(['voucher_no'])->where(['PurchaseInvoices.company_id'=>$company_id,'PurchaseInvoices.financial_year_id'=>$financialYear_id])->order(['voucher_no' => 'DESC'])->first();
		//pr($Grns->supplier_ledger_id); exit;
        $purchaseInvoice = $this->PurchaseInvoices->newEntity();
        if ($this->request->is('post')) {
            $purchaseInvoice = $this->PurchaseInvoices->patchEntity($purchaseInvoice, $this->request->getData());
			$purchaseInvoice->transaction_date = date("Y-m-d",strtotime($this->request->getData()['transaction_date']));
			$due_days=$this->request->data['due_days']; 
			$Voucher_no = $this->PurchaseInvoices->find()->select(['voucher_no'])->where(['PurchaseInvoices.company_id'=>$company_id,'PurchaseInvoices.financial_year_id'=>$financialYear_id])->order(['voucher_no' => 'DESC'])->first();
			if($Voucher_no)
			{
				$purchaseInvoice->voucher_no = $Voucher_no->voucher_no+1;
			}
			else
			{
				$purchaseInvoice->voucher_no = 1;
			} 
			$purchaseInvoice->financial_year_id =$financialYear_id;
			$purchaseInvoice->company_id = $company_id;
			$purchaseInvoice->grn_id = $Grns->id;
                        $purchaseInvoice->purchase_ledger_id=$purchaseInvoice->purchase_ledger_id;
                        $purchaseInvoice->supplier_ledger_id=$Grns->supplier_ledger_id;
			
			
			//pr($purchaseInvoice); exit;
			//exit;
            if ($this->PurchaseInvoices->save($purchaseInvoice)) { 
				
				$query = $this->PurchaseInvoices->Grns->query();
				$query->update()
					->set(['status'=>'Invoice Booked'])
					->where(['id' => $Grns->id])
					->execute();
				
				//Accounting Entries for Purchase account total_taxable_value //
				$AccountingEntrie = $this->PurchaseInvoices->AccountingEntries->newEntity(); 
				$AccountingEntrie->ledger_id=$purchaseInvoice->purchase_ledger_id;
				$AccountingEntrie->debit=$purchaseInvoice->total_taxable_value;
				$AccountingEntrie->credit=0;
				$AccountingEntrie->transaction_date=$purchaseInvoice->transaction_date;
				$AccountingEntrie->company_id=$company_id;
				$AccountingEntrie->purchase_invoice_id=$purchaseInvoice->id;
				$this->PurchaseInvoices->AccountingEntries->save($AccountingEntrie);
			  
			  
			  //Accounting Entries for Supplier account//
				$AccountingEntrie = $this->PurchaseInvoices->AccountingEntries->newEntity(); 
				$AccountingEntrie->ledger_id=$purchaseInvoice->supplier_ledger_id;
				$AccountingEntrie->credit=$purchaseInvoice->total_amount;
				$AccountingEntrie->debit=0;
				$AccountingEntrie->transaction_date=$purchaseInvoice->transaction_date;
				$AccountingEntrie->company_id=$company_id;
				$AccountingEntrie->purchase_invoice_id=$purchaseInvoice->id;
				$this->PurchaseInvoices->AccountingEntries->save($AccountingEntrie);
				
			//Accounting Entries for Round of Amount//
				$AccountingEntrie = $this->PurchaseInvoices->AccountingEntries->newEntity(); 
				$RoundofLedgers = $this->PurchaseInvoices->PurchaseInvoiceRows->Ledgers->find()->where(['Ledgers.round_off'=>1,'Ledgers.company_id'=>$company_id])->first(); 
				$AccountingEntrie->ledger_id=$RoundofLedgers->id;
				$AccountingEntrie->transaction_date=$purchaseInvoice->transaction_date;
				$AccountingEntrie->company_id=$company_id;
				$AccountingEntrie->purchase_invoice_id=$purchaseInvoice->id;
				
				if($purchaseInvoice->total_round_off > 0){
					$AccountingEntrie->debit=abs($purchaseInvoice->total_round_off);
					$AccountingEntrie->credit=0;
				}else{
					$AccountingEntrie->credit=abs($purchaseInvoice->total_round_off);
					$AccountingEntrie->debit=0;
				}
				if($purchaseInvoice->total_round_off != 0){
					$this->PurchaseInvoices->AccountingEntries->save($AccountingEntrie);
				}
				
				if($purchaseInvoice->is_interstate=='0'){
					foreach($purchaseInvoice->purchase_invoice_rows as $purchase_invoice_row)
					   { 
					   $gstAmtdata=$purchase_invoice_row->gst_value/2;
					   $gstAmtInsert=round($gstAmtdata,2);
					   
					   //Accounting Entries for GST//
					  $AccountingEntrieCGST = $this->PurchaseInvoices->AccountingEntries->newEntity(); 
						$gstLedgerCGST = $this->PurchaseInvoices->PurchaseInvoiceRows->Ledgers->find()
							->where(['Ledgers.gst_figure_id' =>$purchase_invoice_row->item_gst_figure_id,'Ledgers.company_id'=>$company_id, 'Ledgers.input_output'=>'input', 'Ledgers.gst_type'=>'CGST'])->first();
							
						$AccountingEntrieCGST->ledger_id=$gstLedgerCGST->id;
						$AccountingEntrieCGST->debit=$gstAmtInsert;
						$AccountingEntrieCGST->credit=0;
						$AccountingEntrieCGST->transaction_date=$purchaseInvoice->transaction_date;
						$AccountingEntrieCGST->company_id=$company_id;
						$AccountingEntrieCGST->purchase_invoice_id=$purchaseInvoice->id;
						$this->PurchaseInvoices->AccountingEntries->save($AccountingEntrieCGST);
						
						$AccountingEntrieSGST = $this->PurchaseInvoices->AccountingEntries->newEntity(); 
						$gstLedgerSGST = $this->PurchaseInvoices->PurchaseInvoiceRows->Ledgers->find()
							->where(['Ledgers.gst_figure_id' =>$purchase_invoice_row->item_gst_figure_id,'Ledgers.company_id'=>$company_id, 'Ledgers.input_output'=>'input', 'Ledgers.gst_type'=>'SGST'])->first();
						$AccountingEntrieSGST->ledger_id=$gstLedgerSGST->id;
						$AccountingEntrieSGST->debit=$gstAmtInsert;
						$AccountingEntrieSGST->credit=0;
						$AccountingEntrieSGST->transaction_date=$purchaseInvoice->transaction_date;
						$AccountingEntrieSGST->company_id=$company_id;
						$AccountingEntrieSGST->purchase_invoice_id=$purchaseInvoice->id;
						$this->PurchaseInvoices->AccountingEntries->save($AccountingEntrieSGST);
					   }
				}else{
					foreach($purchaseInvoice->purchase_invoice_rows as $purchase_invoice_row)
					   {
						   //Accounting Entries for IGST//
						$AccountingEntrieIGST = $this->PurchaseInvoices->AccountingEntries->newEntity(); 
						$gstLedgerSGST = $this->PurchaseInvoices->PurchaseInvoiceRows->Ledgers->find()
							->where(['Ledgers.gst_figure_id' =>$purchase_invoice_row->item_gst_figure_id,'Ledgers.company_id'=>$company_id, 'Ledgers.input_output'=>'input', 'Ledgers.gst_type'=>'IGST'])->first();
						$AccountingEntrieIGST->ledger_id=$gstLedgerSGST->id;
						$AccountingEntrieIGST->debit=$purchase_invoice_row->gst_value;
						$AccountingEntrieIGST->credit=0;
						$AccountingEntrieIGST->transaction_date=$purchaseInvoice->transaction_date;
						$AccountingEntrieIGST->company_id=$company_id;
						$AccountingEntrieIGST->purchase_invoice_id=$purchaseInvoice->id;
						$this->PurchaseInvoices->AccountingEntries->save($AccountingEntrieIGST);
					   }
				}
				//Freight Entry Start//
				if($purchaseInvoice->fright_amount > 0){
					
					//Accounting Entries for Freight Account//
					$AccountingEntrie = $this->PurchaseInvoices->AccountingEntries->newEntity(); 
					$AccountingEntrie->ledger_id=$purchaseInvoice->fright_ledger_id;
					$AccountingEntrie->debit=$purchaseInvoice->fright_amount;
					$AccountingEntrie->credit=0;
					$AccountingEntrie->transaction_date=$purchaseInvoice->transaction_date;
					$AccountingEntrie->company_id=$company_id;
					$AccountingEntrie->purchase_invoice_id=$purchaseInvoice->id;
					$this->PurchaseInvoices->AccountingEntries->save($AccountingEntrie);
					
					
					if($purchaseInvoice->is_interstate=='0'){
						 
						   $gstAmtdata=$purchaseInvoice->fright_gst_amount/2;
						   $gstAmtInsert=round($gstAmtdata,2);
						   
						   //Accounting Entries for GST//
						  $AccountingEntrieCGST = $this->PurchaseInvoices->AccountingEntries->newEntity(); 
							$gstLedgerCGST = $this->PurchaseInvoices->PurchaseInvoiceRows->Ledgers->find()
								->where(['Ledgers.gst_figure_id' =>$purchaseInvoice->fright_gst,'Ledgers.company_id'=>$company_id, 'Ledgers.input_output'=>'input', 'Ledgers.gst_type'=>'CGST'])->first();
								
							$AccountingEntrieCGST->ledger_id=$gstLedgerCGST->id;
							$AccountingEntrieCGST->debit=$gstAmtInsert;
							$AccountingEntrieCGST->credit=0;
							$AccountingEntrieCGST->transaction_date=$purchaseInvoice->transaction_date;
							$AccountingEntrieCGST->company_id=$company_id;
							$AccountingEntrieCGST->purchase_invoice_id=$purchaseInvoice->id;
							$this->PurchaseInvoices->AccountingEntries->save($AccountingEntrieCGST);
							
							$AccountingEntrieSGST = $this->PurchaseInvoices->AccountingEntries->newEntity(); 
							$gstLedgerSGST = $this->PurchaseInvoices->PurchaseInvoiceRows->Ledgers->find()
								->where(['Ledgers.gst_figure_id' =>$purchaseInvoice->fright_gst,'Ledgers.company_id'=>$company_id, 'Ledgers.input_output'=>'input', 'Ledgers.gst_type'=>'SGST'])->first();
							$AccountingEntrieSGST->ledger_id=$gstLedgerSGST->id;
							$AccountingEntrieSGST->debit=$gstAmtInsert;
							$AccountingEntrieSGST->credit=0;
							$AccountingEntrieSGST->transaction_date=$purchaseInvoice->transaction_date;
							$AccountingEntrieSGST->company_id=$company_id;
							$AccountingEntrieSGST->purchase_invoice_id=$purchaseInvoice->id;
							$this->PurchaseInvoices->AccountingEntries->save($AccountingEntrieSGST);
						   
					}else{
						
						   //Accounting Entries for IGST//
							$AccountingEntrieIGST = $this->PurchaseInvoices->AccountingEntries->newEntity(); 
							$gstLedgerSGST = $this->PurchaseInvoices->PurchaseInvoiceRows->Ledgers->find()
								->where(['Ledgers.gst_figure_id' =>$purchaseInvoice->fright_gst,'Ledgers.company_id'=>$company_id, 'Ledgers.input_output'=>'input', 'Ledgers.gst_type'=>'IGST'])->first();
								
							$AccountingEntrieIGST->ledger_id=$gstLedgerSGST->id;
							$AccountingEntrieIGST->debit=$purchaseInvoice->fright_gst_amount;
							$AccountingEntrieIGST->credit=0;
							$AccountingEntrieIGST->transaction_date=$purchaseInvoice->transaction_date;
							$AccountingEntrieIGST->company_id=$company_id;
							$AccountingEntrieIGST->purchase_invoice_id=$purchaseInvoice->id; 
							//pr($AccountingEntrieIGST); exit;
							$this->PurchaseInvoices->AccountingEntries->save($AccountingEntrieIGST);
					}
				}
			//Freight Entry End//
				//Refrence Details For Party//
				
				$Ledgers = $this->PurchaseInvoices->PurchaseInvoiceRows->Ledgers->get($purchaseInvoice->supplier_ledger_id);
					if($Ledgers->bill_to_bill_accounting=="yes"){
					$ReferenceDetail = $this->PurchaseInvoices->ReferenceDetails->newEntity(); 
					$ReferenceDetail->ledger_id=$purchaseInvoice->supplier_ledger_id;
					$ReferenceDetail->credit=$purchaseInvoice->total_amount;
					$ReferenceDetail->debit=0;
					$ReferenceDetail->transaction_date=$purchaseInvoice->transaction_date;
					$ReferenceDetail->company_id=$company_id;
					$ReferenceDetail->type='New Ref';
					$ReferenceDetail->ref_name='PI'.$purchaseInvoice->voucher_no;
					$ReferenceDetail->purchase_invoice_id=$purchaseInvoice->id;
					$ReferenceDetail->due_days = $due_days;
					$this->PurchaseInvoices->ReferenceDetails->save($ReferenceDetail);
				}
				  
				
                $this->Flash->success(__('The purchase invoice has been saved.'));

                return $this->redirect(['action' => 'index']);
            }
            $this->Flash->error(__('The purchase invoice could not be saved. Please, try again.'));
        }
		
		$partyParentGroups = $this->PurchaseInvoices->Grns->GrnRows->Ledgers->AccountingGroups->find()
						->where(['AccountingGroups.company_id'=>$company_id, 'AccountingGroups.
						purchase_invoice_party'=>'1']);
		$partyGroups=[];
		

		foreach($partyParentGroups as $partyParentGroup)
		{
			$accountingGroups = $this->PurchaseInvoices->Grns->GrnRows->Ledgers->AccountingGroups
			->find('children', ['for' => $partyParentGroup->id])->toArray();
			$partyGroups[]=$partyParentGroup->id;
			foreach($accountingGroups as $accountingGroup){
				$partyGroups[]=$accountingGroup->id;
			}
		}
		if($partyGroups)
		{  
			$Partyledgers = $this->PurchaseInvoices->Grns->SupplierLedgers->find()
							->where(['SupplierLedgers.accounting_group_id IN' =>$partyGroups,'SupplierLedgers.company_id'=>$company_id])
							->contain(['Suppliers']);
        }

		$SupplierLedgersDetails = $this->PurchaseInvoices->Grns->SupplierLedgers->find()->where(['SupplierLedgers.id'=>$Grns->supplier_ledger->id])->contain(['Suppliers'])->first();
		$supplier_state_id=$SupplierLedgersDetails->supplier->state_id;
		
		//$supplier_state_id=$supplier_ledger_detail->supplier->state_id;
		
		$partyOptions=[];
		foreach($Partyledgers as $Partyledger){ 
		
			$partyOptions[]=['text' =>@$Partyledger->name, 'value' => @$Partyledger->id,'state_id'=>@$Partyledger->supplier->state_id,'default_days'=>@$Partyledger->default_credit_days];
		} 
		
		$accountLedgers = $this->PurchaseInvoices->Grns->GrnRows->Ledgers->AccountingGroups->find()->where(['AccountingGroups.purchase_invoice_purchase_account'=>1,'AccountingGroups.company_id'=>$company_id])->first();

		$accountingGroups2 = $this->PurchaseInvoices->Grns->GrnRows->Ledgers->AccountingGroups
		->find('children', ['for' => $accountLedgers->id])
		->find('List')->toArray();
		$accountingGroups2[$accountLedgers->id]=$accountLedgers->name;
		ksort($accountingGroups2);
		if($accountingGroups2)
		{   
			$account_ids="";
			foreach($accountingGroups2 as $key=>$accountingGroup)
			{
				$account_ids .=$key.',';
			}
			$account_ids = explode(",",trim($account_ids,','));
			$Accountledgers = $this->PurchaseInvoices->Grns->GrnRows->Ledgers->find('list')->where(['Ledgers.accounting_group_id IN' =>$account_ids]);
        }
		go:
		//pr($Accountledgers->toArray());
		//exit;
        $Gstfigures1 = $this->PurchaseInvoices->PurchaseInvoiceRows->Items->Gstfigures->find()->where(['company_id'=>$company_id]);
		$Gstfigures=[];
		foreach($Gstfigures1 as $dd)
			{  
				
				$Gstfigures[]=['text'=>$dd->name, 'value'=>$dd->id, 'FirstGstFigure'=>$dd->tax_percentage];
				
			}
		$freightLedgers = $this->PurchaseInvoices->Grns->GrnRows->Ledgers->find('list')->where(['Ledgers.freight'=>"Yes",'Ledgers.company_id'=>$company_id]);
        $companies = $this->PurchaseInvoices->Companies->find('list', ['limit' => 200]);
        $supplierLedgers = $this->PurchaseInvoices->SupplierLedgers->find('list', ['limit' => 200]);
        $this->set(compact('purchaseInvoice', 'companies', 'supplierLedgers','Grns','partyOptions','state_id','Accountledgers','supplier_state_id','Voucher_no_last','supplier_status','supplier_ledger_id','Gstfigures','freightLedgers'));
        $this->set('_serialize', ['purchaseInvoice']);
    }

    /**
     * Edit method
     *
     * @param string|null $id Purchase Invoice id.
     * @return \Cake\Http\Response|null Redirects on successful edit, renders view otherwise.
     * @throws \Cake\Network\Exception\NotFoundException When record not found.
     */
    public function edit($id = null)
    {
		$this->viewBuilder()->layout('index_layout');
		$company_id=$this->Auth->User('session_company_id');
		$location_id=$this->Auth->User('session_location_id');
		$stateDetails=$this->Auth->User('session_company');
		$state_id=$stateDetails->state_id;
        $purchaseInvoice = $this->PurchaseInvoices->get($id, [
            'contain' => ['PurchaseInvoiceRows'=>['Items'=>['FirstGstFigures']]]
        ]);
		
        if ($this->request->is(['patch', 'post', 'put'])) {
            $purchaseInvoice = $this->PurchaseInvoices->patchEntity($purchaseInvoice, $this->request->getData());
			$purchaseInvoice->transaction_date = date("Y-m-d",strtotime($this->request->getData()['transaction_date']));
			$purchaseInvoice->company_id = $company_id;
			
            if ($this->PurchaseInvoices->save($purchaseInvoice)) {
				
				//$this->PurchaseInvoices->ItemLedgers->deleteAll(['ItemLedgers.purchase_invoice_id' => $purchaseInvoice->id]);
				$this->PurchaseInvoices->AccountingEntries->deleteAll(['AccountingEntries.purchase_invoice_id' => $purchaseInvoice->id]);
				$this->PurchaseInvoices->ReferenceDetails->deleteAll(['ReferenceDetails.purchase_invoice_id' => $purchaseInvoice->id]);
				
				
				//Accounting Entries for Purchase account//
				$AccountingEntrie = $this->PurchaseInvoices->AccountingEntries->newEntity(); 
				$AccountingEntrie->ledger_id=$purchaseInvoice->purchase_ledger_id;
				$AccountingEntrie->debit=$purchaseInvoice->total_taxable_value;
				$AccountingEntrie->credit=0;
				$AccountingEntrie->transaction_date=$purchaseInvoice->transaction_date;
				$AccountingEntrie->company_id=$company_id;
				$AccountingEntrie->purchase_invoice_id=$purchaseInvoice->id;
				$this->PurchaseInvoices->AccountingEntries->save($AccountingEntrie);
			  
			  
			  //Accounting Entries for Supplier account//
				$AccountingEntrie = $this->PurchaseInvoices->AccountingEntries->newEntity(); 
				$AccountingEntrie->ledger_id=$purchaseInvoice->supplier_ledger_id;
				$AccountingEntrie->credit=$purchaseInvoice->total_amount;
				$AccountingEntrie->debit=0;
				$AccountingEntrie->transaction_date=$purchaseInvoice->transaction_date;
				$AccountingEntrie->company_id=$company_id;
				$AccountingEntrie->purchase_invoice_id=$purchaseInvoice->id;
				$this->PurchaseInvoices->AccountingEntries->save($AccountingEntrie);
				
			//Accounting Entries for Round of Amount//
				$AccountingEntrie = $this->PurchaseInvoices->AccountingEntries->newEntity(); 
				$RoundofLedgers = $this->PurchaseInvoices->PurchaseInvoiceRows->Ledgers->find()->where(['Ledgers.round_off'=>1,'Ledgers.company_id'=>$company_id])->first(); 
				$AccountingEntrie->ledger_id=$RoundofLedgers->id;
				$AccountingEntrie->transaction_date=$purchaseInvoice->transaction_date;
				$AccountingEntrie->company_id=$company_id;
				$AccountingEntrie->purchase_invoice_id=$purchaseInvoice->id;
				
				if($purchaseInvoice->total_round_amount > 0){
					$AccountingEntrie->debit=abs($purchaseInvoice->total_round_amount);
					$AccountingEntrie->credit=0;
				}else{
					$AccountingEntrie->credit=abs($purchaseInvoice->total_round_amount);
					$AccountingEntrie->debit=0;
				}
				if($purchaseInvoice->total_round_amount != 0){
					$this->PurchaseInvoices->AccountingEntries->save($AccountingEntrie);
				}
				
				if($purchaseInvoice->is_interstate=='0'){
					foreach($purchaseInvoice->purchase_invoice_rows as $purchase_invoice_row)
					   { 
					   $gstAmtdata=$purchase_invoice_row->gst_value/2;
					   $gstAmtInsert=round($gstAmtdata,2);
					   
					   //Accounting Entries for GST//
					  $AccountingEntrieCGST = $this->PurchaseInvoices->AccountingEntries->newEntity(); 
						$gstLedgerCGST = $this->PurchaseInvoices->PurchaseInvoiceRows->Ledgers->find()
							->where(['Ledgers.gst_figure_id' =>$purchase_invoice_row->item_gst_figure_id,'Ledgers.company_id'=>$company_id, 'Ledgers.input_output'=>'input', 'Ledgers.gst_type'=>'CGST'])->first();
							
						$AccountingEntrieCGST->ledger_id=$gstLedgerCGST->id;
						$AccountingEntrieCGST->debit=$gstAmtInsert;
						$AccountingEntrieCGST->credit=0;
						$AccountingEntrieCGST->transaction_date=$purchaseInvoice->transaction_date;
						$AccountingEntrieCGST->company_id=$company_id;
						$AccountingEntrieCGST->purchase_invoice_id=$purchaseInvoice->id;
						$this->PurchaseInvoices->AccountingEntries->save($AccountingEntrieCGST);
						
						$AccountingEntrieSGST = $this->PurchaseInvoices->AccountingEntries->newEntity(); 
						$gstLedgerSGST = $this->PurchaseInvoices->PurchaseInvoiceRows->Ledgers->find()
							->where(['Ledgers.gst_figure_id' =>$purchase_invoice_row->item_gst_figure_id,'Ledgers.company_id'=>$company_id, 'Ledgers.input_output'=>'input', 'Ledgers.gst_type'=>'SGST'])->first();
						$AccountingEntrieSGST->ledger_id=$gstLedgerSGST->id;
						$AccountingEntrieSGST->debit=$gstAmtInsert;
						$AccountingEntrieSGST->credit=0;
						$AccountingEntrieSGST->transaction_date=$purchaseInvoice->transaction_date;
						$AccountingEntrieSGST->company_id=$company_id;
						$AccountingEntrieSGST->purchase_invoice_id=$purchaseInvoice->id;
						$this->PurchaseInvoices->AccountingEntries->save($AccountingEntrieSGST);
					   }
				}else{
					foreach($purchaseInvoice->purchase_invoice_rows as $purchase_invoice_row)
					   {
						   //Accounting Entries for IGST//
						$AccountingEntrieIGST = $this->PurchaseInvoices->AccountingEntries->newEntity(); 
						$gstLedgerSGST = $this->PurchaseInvoices->PurchaseInvoiceRows->Ledgers->find()
							->where(['Ledgers.gst_figure_id' =>$purchase_invoice_row->item_gst_figure_id,'Ledgers.company_id'=>$company_id, 'Ledgers.input_output'=>'input', 'Ledgers.gst_type'=>'IGST'])->first();
						$AccountingEntrieIGST->ledger_id=$gstLedgerSGST->id;
						$AccountingEntrieIGST->debit=$purchase_invoice_row->gst_value;
						$AccountingEntrieIGST->credit=0;
						$AccountingEntrieIGST->transaction_date=$purchaseInvoice->transaction_date;
						$AccountingEntrieIGST->company_id=$company_id;
						$AccountingEntrieIGST->purchase_invoice_id=$purchaseInvoice->id;
						$this->PurchaseInvoices->AccountingEntries->save($AccountingEntrieIGST);
					   }
				}
				
				//Refrence Details For Party/Supplier  //
				
				$Ledgers = $this->PurchaseInvoices->PurchaseInvoiceRows->Ledgers->get($purchaseInvoice->supplier_ledger_id);
				if($Ledgers->bill_to_bill_accounting=="yes"){ 
					$ReferenceDetail = $this->PurchaseInvoices->ReferenceDetails->newEntity(); 
					$ReferenceDetail->ledger_id=$purchaseInvoice->supplier_ledger_id;
					$ReferenceDetail->credit=$purchaseInvoice->total_amount;
					$ReferenceDetail->debit=0;
					$ReferenceDetail->transaction_date=$purchaseInvoice->transaction_date;
					$ReferenceDetail->company_id=$company_id;
					$ReferenceDetail->type='New Ref';
					$ReferenceDetail->ref_name='PI'.$purchaseInvoice->voucher_no;
					$ReferenceDetail->purchase_invoice_id=$purchaseInvoice->id; //pr($ReferenceDetail); exit;
					$this->PurchaseInvoices->ReferenceDetails->save($ReferenceDetail);
				}
				  
                $this->Flash->success(__('The purchase invoice has been saved.'));

                return $this->redirect(['action' => 'index']);
            }else{
				//pr($purchaseInvoice); exit;
			}
            $this->Flash->error(__('The purchase invoice could not be saved. Please, try again.'));
        }
		
		$partyParentGroups = $this->PurchaseInvoices->Grns->GrnRows->Ledgers->AccountingGroups->find()
						->where(['AccountingGroups.company_id'=>$company_id, 'AccountingGroups.
						purchase_invoice_party'=>'1']);
						//pr($partyParentGroups->toArray()); exit;
		$partyGroups=[];
		foreach($partyParentGroups as $partyParentGroup)
		{
			$accountingGroups = $this->PurchaseInvoices->Grns->GrnRows->Ledgers->AccountingGroups
			->find('children', ['for' => $partyParentGroup->id])->toArray();
			$partyGroups[]=$partyParentGroup->id;
			foreach($accountingGroups as $accountingGroup){
				$partyGroups[]=$accountingGroup->id;
			}
		}
		
		if($partyGroups)
		{  
			$Partyledgers = $this->PurchaseInvoices->Grns->SupplierLedgers->find()
							->where(['SupplierLedgers.accounting_group_id IN' =>$partyGroups,'SupplierLedgers.company_id'=>$company_id])
							->contain(['Suppliers']);
        }
		
		
		$partyOptions=[];
		foreach($Partyledgers as $Partyledger){ 
			//pr($Partyledger->supplier->state_id);
			$partyOptions[]=['text' =>$Partyledger->name, 'value' => $Partyledger->id,'state_id'=>$Partyledger->supplier->state_id];
		}
		
		$accountLedgers = $this->PurchaseInvoices->Grns->GrnRows->Ledgers->AccountingGroups->find()->where(['AccountingGroups.purchase_invoice_purchase_account'=>1,'AccountingGroups.company_id'=>$company_id])->first();
		
		$supplier_ledger_detail = $this->PurchaseInvoices->SupplierLedgers->find()
							->where(['SupplierLedgers.id'=>$purchaseInvoice->supplier_ledger_id])
							->contain(['Suppliers'])
							->first();
						//pr($supplier_ledger_detail); exit;
		$supplier_state_id=$supplier_ledger_detail->supplier->state_id;
		$accountingGroups2 = $this->PurchaseInvoices->Grns->GrnRows->Ledgers->AccountingGroups
		->find('children', ['for' => $accountLedgers->id])
		->find('List')->toArray();
		$accountingGroups2[$accountLedgers->id]=$accountLedgers->name;
		ksort($accountingGroups2);
		if($accountingGroups2)
		{   
			$account_ids="";
			foreach($accountingGroups2 as $key=>$accountingGroup)
			{
				$account_ids .=$key.',';
			}
			$account_ids = explode(",",trim($account_ids,','));
			$Accountledgers = $this->PurchaseInvoices->Grns->GrnRows->Ledgers->find('list')->where(['Ledgers.accounting_group_id IN' =>$account_ids]);
        }
		
        $companies = $this->PurchaseInvoices->Companies->find('list', ['limit' => 200]);
        $supplierLedgers = $this->PurchaseInvoices->SupplierLedgers->find('list', ['limit' => 200]);
        $this->set(compact('purchaseInvoice', 'companies', 'supplierLedgers', 'companies', 'partyOptions','state_id','Accountledgers','supplier_state_id'));
        $this->set('_serialize', ['purchaseInvoice']);
    }

    /**
     * Delete method
     *
     * @param string|null $id Purchase Invoice id.
     * @return \Cake\Http\Response|null Redirects to index.
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function delete($id = null)
    {
        $this->request->allowMethod(['post', 'delete']);
        $purchaseInvoice = $this->PurchaseInvoices->get($id);
        if ($this->PurchaseInvoices->delete($purchaseInvoice)) {
            $this->Flash->success(__('The purchase invoice has been deleted.'));
        } else {
            $this->Flash->error(__('The purchase invoice could not be deleted. Please, try again.'));
        }

        return $this->redirect(['action' => 'index']);
    }
	
	public function PurchaseInvoiceReturn()
	{ 
		$this->viewBuilder()->layout('index_layout');
		$company_id=$this->Auth->User('session_company_id');
		$stateDetails=$this->Auth->User('session_company');
		@$purchase_invoice_no=$this->request->query('purchase_invoice_no');
		@$item_code=$this->request->query('item_code');
		$financialYear_id=$this->Auth->User('financialYear_id');
		$PurchaseInvoiceStatus="No";
		if(!empty(@$purchase_invoice_no)){ 
		$PurchaseInvoice = $this->PurchaseInvoices->find()
						->where(['PurchaseInvoices.voucher_no' =>$purchase_invoice_no,'PurchaseInvoices.company_id'=>$company_id])
						->contain(['Companies', 'SupplierLedgers']);
		//pr($PurchaseInvoices);  exit;
		
		$PurchaseInvoiceStatus="Yes";
		}
		
		if(!empty($item_code)){
			$items = $this->PurchaseInvoices->PurchaseInvoiceRows->Items->find()->select(['id'])
					->where(['Items.company_id'=>$company_id,'Items.id'=>$item_code])->first();
				
				$item_id=@$items->id;
			
				$PurchaseInvoice = $this->PurchaseInvoices->find()->contain(['SupplierLedgers'])->where(['PurchaseInvoices.company_id' => $company_id]);
				 $PurchaseInvoice->innerJoinWith('PurchaseInvoiceRowsDatas',function ($q) use($item_id,$company_id) {
					return $q->where(['PurchaseInvoiceRowsDatas.item_id' =>$item_id]);
				}); 
				
				$PurchaseInvoice->where(['PurchaseInvoices.company_id' => $company_id])->contain(['PurchaseInvoiceRowsDatas'=>function ($q) use($item_id,$company_id) {
					return $q->where(['PurchaseInvoiceRowsDatas.item_id' =>$item_id]);
				}]); 
				
				//pr($PurchaseInvoice->toArray()); exit;
			$PurchaseInvoiceStatus="Yes";
		}
		
		
		$stockItems1=$this->PurchaseInvoices->PurchaseInvoiceRows->Items->find()->where(['Items.company_id'=>$company_id]);
		$stockItems=[];
		foreach($stockItems1 as $data){
		//	$merge=
		//$stockItems[]=['text'=>$data->name.'('.$data->item_code.')', 'value'=>$data->id];
		$stockItems[]=['text'=>$data->item_code.' ('.$data->name.') ', 'value'=>$data->id];
		}
		//pr($PurchaseInvoice->toArray());  exit;
		$this->set(compact('PurchaseInvoiceStatus','PurchaseInvoice','stockItems'));
		
	}
	
	public function cancel($id = null)
    {
		// $this->request->allowMethod(['post', 'delete']);
        $PurchaseInvoice = $this->PurchaseInvoices->get($id);
		$company_id=$this->Auth->User('session_company_id');
		//pr($salesInvoice);exit;
		$PurchaseInvoice->status='cancel';
        if ($this->PurchaseInvoices->save($PurchaseInvoice)) {
				$query = $this->PurchaseInvoices->Grns->query();
				$query->update()
					->set(['status'=>'Pending'])
					->where(['Grns.id' => $PurchaseInvoice->grn_id])
					->execute();
				$deleteRefDetails = $this->PurchaseInvoices->ReferenceDetails->query();
				$deleteRef = $deleteRefDetails->delete()
					->where(['ReferenceDetails.purchase_invoice_id' => $PurchaseInvoice->id])
					->execute();
				$deleteAccountEntries = $this->PurchaseInvoices->AccountingEntries->query();
				$result = $deleteAccountEntries->delete()
				->where(['AccountingEntries.purchase_invoice_id' => $PurchaseInvoice->id])
				->execute();
			  $this->Flash->success(__('The Purchase Invoice has been cancelled.'));
        } else {
            $this->Flash->error(__('The Purchase Invoice could not be cancelled. Please, try again.'));
        }

        return $this->redirect(['action' => 'index']);
    }
	public function reportFilter()
    { 
		$this->viewBuilder()->layout('index_layout');
		$company_id=$this->Auth->User('session_company_id');
		@$partyParentGroups = $this->PurchaseInvoices->PurchaseInvoiceRows->Ledgers->AccountingGroups->find()
						->where(['AccountingGroups.company_id'=>$company_id, 'AccountingGroups.purchase_invoice_party'=>'1']);
		$partyGroups=[];
		
		foreach($partyParentGroups as $partyParentGroup)
		{
			$accountingGroups = $this->PurchaseInvoices->PurchaseInvoiceRows->Ledgers->AccountingGroups
			->find('children', ['for' => $partyParentGroup->id])->toArray();
			$partyGroups[]=$partyParentGroup->id;
			foreach($accountingGroups as $accountingGroup){
				$partyGroups[]=$accountingGroup->id;
			}
		}
	
		if($partyGroups)
		{  
			$Partyledgers = $this->PurchaseInvoices->PurchaseInvoiceRows->Ledgers->find()
							->where(['Ledgers.accounting_group_id IN' =>$partyGroups,'Ledgers.company_id'=>$company_id])
							->contain(['Suppliers']);
        }
		
		$partyOptions=[];
		foreach($Partyledgers as $Partyledger){
		
		$receiptAccountLedgers = $this->PurchaseInvoices->PurchaseInvoiceRows->Ledgers->AccountingGroups->find()
		//->where(['AccountingGroups.id'=>$Partyledger->accounting_group_id,'AccountingGroups.customer'=>1])
		->Where(['AccountingGroups.id'=>$Partyledger->accounting_group_id,'AccountingGroups.supplier'=>1])->first();
		
	
		
		
		if($receiptAccountLedgers)
		{
			$receiptAccountLedgersName='1';
		}
		else{
			$receiptAccountLedgersName='0';
		}
			$partyOptions[]=['text' =>str_pad(@$Partyledger->supplier->id, 4, '0', STR_PAD_LEFT).' - '.$Partyledger->name, 'value' => $Partyledger->id ,'party_state_id'=>@$Partyledger->supplier->state_id, 'partyexist'=>$receiptAccountLedgersName, 'billToBillAccounting'=>$Partyledger->bill_to_bill_accounting];
		}
		
		$this->set(compact('partyOptions'));
    }
	
	 public function report($id=null)
    {
		$status=$this->request->query('status'); 
		if(!empty($status)){ 
			$this->viewBuilder()->layout('excel_layout');	
		}else{ 
			$this->viewBuilder()->layout('index_layout');
		}
		
		$company_id=$this->Auth->User('session_company_id');
		$url=$this->request->here();
		$url=parse_url($url,PHP_URL_QUERY);
	    $from=$this->request->query('from_date');
		$to=$this->request->query('to_date');
		
		$where=[];
		$where1=[];
		if(!empty($from)){ 
			$from_date=date('Y-m-d', strtotime($from));
		$where['PurchaseInvoices.transaction_date >=']= $from_date;
		}
		if(!empty($to)){
			$to_date=date('Y-m-d', strtotime($to));
			$where['PurchaseInvoices.transaction_date <='] = $to_date;
		}
		$party_ids=$this->request->query('supplier_ledger_id');
		
	
		
		if(!empty($party_ids)){
		$where['PurchaseInvoices.supplier_ledger_id IN'] = $party_ids;
		}
		$invoice_no=$this->request->query('invoice_no');
	
		if(!empty($invoice_no)){
		$invoices_explode_commas=explode(',',$invoice_no);
			
			if($invoices_explode_commas){
			$invoice_ids=[];
		
			
			
			foreach($invoices_explode_commas as $invoices_explode_comma)
			{
				@$invoices_explode_dashs=explode('-',$invoices_explode_comma);
				
				
				$size=sizeOf($invoices_explode_dashs);
				if($size==2){
					$var1=$invoices_explode_dashs[0];
					$var2=$invoices_explode_dashs[1];
					for($i=$var1; $i<= $var2; $i++){
						$invoice_ids[]=$i;
					}
				}else{
					$invoice_ids[]=$invoices_explode_dashs[0];
				}
			}
		}
		$where1['PurchaseInvoices.voucher_no IN'] = $invoice_ids;
		$where1['PurchaseInvoices.company_id'] = $company_id;
		}
		if(!empty($where)){
		$purchaseInvoices = $this->PurchaseInvoices->find()->where(['PurchaseInvoices.company_id'=>$company_id])->where($where)->orWhere($where1)
		->contain(['Companies', 'SupplierLedgers'=>['Suppliers'], 'PurchaseLedgers', 'PurchaseInvoicerows'=>['Items'=>['Sizes','FirstGstFigures']]])
        ->order(['voucher_no' => 'ASC']);
		}
		else{
		$purchaseInvoices = $this->PurchaseInvoices->find()->where(['PurchaseInvoices.company_id'=>$company_id])->where($where1)
		->contain(['Companies', 'SupplierLedgers'=>['Suppliers'], 'PurchaseLedgers', 'PurchaseInvoicerows'=>['Items'=>['Sizes','FirstGstFigures']]])
        ->order(['voucher_no' => 'ASC']);
		}
		
		$i=0; 
		foreach($purchaseInvoices as $purchaseInvoice)
		{ 
			$data_date=strtotime($purchaseInvoice->transaction_date);
			$PurchaseInvoices[$data_date][$i]=$purchaseInvoice;
			$i++;
		}
		ksort($PurchaseInvoices);
		//pr($purchaseInvoices->toArray());
		$companies=$this->PurchaseInvoices->Companies->find()->contain(['States'])->where(['Companies.id'=>$company_id])->first();
		
		$this->set(compact('companies','PurchaseInvoices', 'from', 'to','party_ids','invoice_no','url','status'));
        $this->set('_serialize', ['purchaseInvoices']);
    } 
}