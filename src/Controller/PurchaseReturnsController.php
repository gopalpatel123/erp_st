<?php
namespace App\Controller;

use App\Controller\AppController;

/**
 * PurchaseReturns Controller
 *
 * @property \App\Model\Table\PurchaseReturnsTable $PurchaseReturns
 *
 * @method \App\Model\Entity\PurchaseReturn[] paginate($object = null, array $settings = [])
 */
class PurchaseReturnsController extends AppController
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
		$location_id=$this->Auth->User('session_location_id');
		$stateDetails=$this->Auth->User('session_company');
		$financialYear_id=$this->Auth->User('financialYear_id');
		$search=$this->request->query('search');
         $this->paginate = [
            'contain' => ['Companies', 'PurchaseInvoices'],
			'limit' => 100
        ];
		
		$item_id = $this->request->query('item_id');
		$where1=[];
		$where=[];
		if(!empty($item_id)){
			$where1['PurchaseReturnRows.item_id']=$item_id;
		}
		$where['PurchaseReturns.company_id']=$company_id;
		$where['PurchaseReturns.financial_year_id']=$financialYear_id;
		
		if(!empty($search) || !empty($item_id) ){
        $purchaseReturns = $this->paginate($this->PurchaseReturns->find()
		->where($where)
		->contain(['PurchaseReturnRows'])
		->matching(
				'PurchaseReturnRows.Items', function ($q) use($where1) {
					return $q->where($where1);
				})
		);
		}
		else {
		 $purchaseReturns = $this->paginate($this->PurchaseReturns->find()->where(['PurchaseReturns.company_id'=>$company_id,'PurchaseReturns.financial_year_id'=>$financialYear_id]));	
		}
		//pr( $purchaseReturns); exit;
		$stockItems=$this->PurchaseReturns->PurchaseReturnRows->Items->find('list')->where(['Items.company_id'=>$company_id]);
        $this->set(compact('purchaseReturns','search','stockItems','item_id'));
        $this->set('_serialize', ['purchaseReturns']);
    }

    /**
     * View method
     *
     * @param string|null $id Purchase Return id.
     * @return \Cake\Http\Response|void
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function view($id = null)
    {	
	
		$this->viewBuilder()->layout('index_layout');
		$company_id=$this->Auth->User('session_company_id');
		$location_id=$this->Auth->User('session_location_id');
		$stateDetails=$this->Auth->User('session_company');
		$state_id=$stateDetails->state_id;
        $purchaseReturn = $this->PurchaseReturns->get($id, [
            'contain' => (['Companies'=>['States'],'PurchaseReturnRows'=>['Items'=>['FirstGstFigures']],'PurchaseInvoices'=>['PurchaseInvoiceRows'=>['Items'=>['FirstGstFigures']],'PurchaseLedgers','SupplierLedgers'=>['Suppliers']]])]);
		$supplier_state_id=$purchaseReturn->purchase_invoice->supplier_ledger->supplier->state_id;
	//	pr($purchaseReturn); exit;
        $this->set('purchaseReturn', $purchaseReturn);
		$this->set(compact('state_id','supplier_state_id'));
        $this->set('_serialize', ['purchaseReturn']);
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
        $PurchaseInvoice = $this->PurchaseReturns->PurchaseInvoices->get($id, [
            'contain' => (['PurchaseInvoiceRows'=>['Items'=>['FirstGstFigures']],'PurchaseLedgers','SupplierLedgers'=>['Suppliers'],'PurchaseReturns'=>['PurchaseReturnRows' => function($q) {
				return $q->select(['purchase_return_id','purchase_invoice_row_id','item_id','total' => $q->func()->sum('PurchaseReturnRows.quantity')])->group('PurchaseReturnRows.purchase_invoice_row_id');
			}]])
        ]);
		
		$Voucher_no = $this->PurchaseReturns->find()->select(['voucher_no'])->where(['PurchaseReturns.company_id'=>$company_id,'PurchaseReturns.financial_year_id'=>$financialYear_id])->order(['voucher_no' => 'DESC'])->first();
		
		$NewVoucherNo=0;
		if(!empty($Voucher_no)){
		$NewVoucherNo=@$Voucher_no->voucher_no;
		}
		 $purchase_return_qty=[];
		foreach($PurchaseInvoice->purchase_returns as $purchase_return){
			foreach($purchase_return->purchase_return_rows as $purchase_return_row){
				//pr($purchase_return_row); exit;
				$purchase_return_qty[@$purchase_return_row->purchase_invoice_row_id]=@$purchase_return_qty[$purchase_return_row->purchase_invoice_row_id]+$purchase_return_row->total;
			}
		} 
		
		$supplier_state_id=$PurchaseInvoice->supplier_ledger->supplier->state_id;
		//pr($PurchaseInvoice); exit;
        $purchaseReturn = $this->PurchaseReturns->newEntity();
        if ($this->request->is('post')) {
            $purchaseReturn = $this->PurchaseReturns->patchEntity($purchaseReturn, $this->request->getData());
			$purchaseReturn->transaction_date = date("Y-m-d",strtotime($this->request->getData()['transaction_date']));
			$Voucher_no = $this->PurchaseReturns->find()->select(['voucher_no'])->where(['PurchaseReturns.company_id'=>$company_id,'PurchaseReturns.financial_year_id'=>$financialYear_id])->order(['PurchaseReturns.voucher_no' => 'DESC'])->first();
			if($Voucher_no)
			{
				$purchaseReturn->voucher_no = $Voucher_no->voucher_no+1;
			}
			else
			{
				$purchaseReturn->voucher_no = 1;
			} 
			$purchaseReturn->company_id = $company_id;
			$purchaseReturn->purchase_invoice_id = $PurchaseInvoice->id;
			$purchaseReturn->financial_year_id =$financialYear_id;
			//pr($PurchaseInvoice); 
		//	pr($purchaseReturn); exit;
            if ($this->PurchaseReturns->save($purchaseReturn)) { 
				//pr($purchaseReturn); exit;
				$roundOfAmt=0;
				$total_taxable_value=0;
				$total_amount=0;
				foreach($purchaseReturn->purchase_return_rows as $purchase_return_row)
				{ 
					$roundOfAmt+=$purchase_return_row->round_off;
					$total_taxable_value+=$purchase_return_row->taxable_value;
					$total_amount+=$purchase_return_row->net_amount;
					$item_id=$purchase_return_row['item_id'];
					$Grns = $this->PurchaseReturns->Grns->GrnRows->find()->where(['GrnRows.item_id'=>$item_id])->first();
					$ItemLedger = $this->PurchaseReturns->ItemLedgers->newEntity(); 
					$ItemLedger->item_id=$purchase_return_row->item_id;
					$ItemLedger->transaction_date=$purchaseReturn->transaction_date;
					$ItemLedger->quantity=$purchase_return_row->quantity;
					$ItemLedger->rate=$Grns->purchase_rate;
					$ItemLedger->amount=$Grns->purchase_rate*$purchase_return_row->quantity;
					$ItemLedger->status='out';
					$ItemLedger->company_id=$company_id;
					$ItemLedger->purchase_return_id=$purchaseReturn->id;
					$ItemLedger->purchase_return_row_id=$purchase_return_row->id;
					$this->PurchaseReturns->ItemLedgers->save($ItemLedger);
				}
				
				//Accounting Entries for Supplier account//
				$AccountingEntrie = $this->PurchaseReturns->AccountingEntries->newEntity(); 
				$AccountingEntrie->ledger_id=$PurchaseInvoice->supplier_ledger_id;
				$AccountingEntrie->debit=$total_amount;
				$AccountingEntrie->credit=0;
				$AccountingEntrie->transaction_date=$purchaseReturn->transaction_date;
				$AccountingEntrie->company_id=$company_id;
				$AccountingEntrie->purchase_return_id=$purchaseReturn->id;
				$this->PurchaseReturns->AccountingEntries->save($AccountingEntrie);
               
				
				//Accounting Entries for Purchase account//
				$AccountingEntrie = $this->PurchaseReturns->AccountingEntries->newEntity(); 
				$AccountingEntrie->ledger_id=$PurchaseInvoice->purchase_ledger_id;
				$AccountingEntrie->credit=$total_taxable_value;
				$AccountingEntrie->debit=0;
				$AccountingEntrie->transaction_date=$purchaseReturn->transaction_date;
				$AccountingEntrie->company_id=$company_id;
				$AccountingEntrie->purchase_return_id=$purchaseReturn->id;
				$this->PurchaseReturns->AccountingEntries->save($AccountingEntrie);
			   $this->Flash->success(__('The purchase return has been saved.'));
				//Accounting Entries for Round of Amount//
				$AccountingEntrie = $this->PurchaseReturns->AccountingEntries->newEntity(); 
				$RoundofLedgers = $this->PurchaseReturns->PurchaseInvoices->PurchaseInvoiceRows->Ledgers->find()->where(['Ledgers.round_off'=>1,'Ledgers.company_id'=>$company_id])->first(); 
				$AccountingEntrie->ledger_id=$RoundofLedgers->id;
				$AccountingEntrie->transaction_date=$purchaseReturn->transaction_date;
				$AccountingEntrie->company_id=$company_id;
				$AccountingEntrie->purchase_return_id=$purchaseReturn->id;
				
				if($roundOfAmt > 0){
					$AccountingEntrie->credit=abs($roundOfAmt);
					$AccountingEntrie->debit=0;
				}else{
					$AccountingEntrie->debit=abs($roundOfAmt);
					$AccountingEntrie->credit=0;
					
				}
				//pr(abs($roundOfAmt)); exit;
				if($roundOfAmt != 0){
					$this->PurchaseReturns->AccountingEntries->save($AccountingEntrie);
				}
				
				if($purchaseReturn->is_interstate=='0'){
					foreach($purchaseReturn->purchase_return_rows as $purchase_return_row)
					{ 
					   $gstAmtdata=$purchase_return_row->gst_value/2;
					   $gstAmtInsert=round($gstAmtdata,2);
					   
					   //Accounting Entries for GST//
					  $AccountingEntrieCGST = $this->PurchaseReturns->AccountingEntries->newEntity(); 
					  
						$gstLedgerCGST = $this->PurchaseReturns->PurchaseInvoices->PurchaseInvoiceRows->Ledgers->find()
							->where(['Ledgers.gst_figure_id' =>$purchase_return_row->item_gst_figure_id,'Ledgers.company_id'=>$company_id, 'Ledgers.input_output'=>'input', 'Ledgers.gst_type'=>'CGST'])->first();
							
						$AccountingEntrieCGST->ledger_id=$gstLedgerCGST->id;
						$AccountingEntrieCGST->credit=$gstAmtInsert;
						$AccountingEntrieCGST->debit=0;
						$AccountingEntrieCGST->transaction_date=$purchaseReturn->transaction_date;
						$AccountingEntrieCGST->company_id=$company_id;
						$AccountingEntrieCGST->purchase_return_id=$purchaseReturn->id;
						$this->PurchaseReturns->AccountingEntries->save($AccountingEntrieCGST);
						
						$AccountingEntrieSGST = $this->PurchaseReturns->AccountingEntries->newEntity(); 
						$gstLedgerSGST = $this->PurchaseReturns->PurchaseInvoices->PurchaseInvoiceRows->Ledgers->find()
							->where(['Ledgers.gst_figure_id' =>$purchase_return_row->item_gst_figure_id,'Ledgers.company_id'=>$company_id, 'Ledgers.input_output'=>'input', 'Ledgers.gst_type'=>'SGST'])->first();
						$AccountingEntrieSGST->ledger_id=$gstLedgerSGST->id;
						$AccountingEntrieSGST->credit=$gstAmtInsert;
						$AccountingEntrieSGST->debit=0;
						$AccountingEntrieSGST->transaction_date=$purchaseReturn->transaction_date;
						$AccountingEntrieSGST->company_id=$company_id;
						$AccountingEntrieSGST->purchase_return_id=$purchaseReturn->id;
						$this->PurchaseReturns->AccountingEntries->save($AccountingEntrieSGST);
					   }
				}else{
					foreach($purchaseReturn->purchase_return_rows as $purchase_return_row)
						{ 
						   //Accounting Entries for IGST//
						$AccountingEntrieIGST = $this->PurchaseReturns->AccountingEntries->newEntity(); 
						$gstLedgerSGST = $this->PurchaseReturns->PurchaseInvoices->PurchaseInvoiceRows->Ledgers->find()
							->where(['Ledgers.gst_figure_id' =>$purchase_return_row->item_gst_figure_id,'Ledgers.company_id'=>$company_id, 'Ledgers.input_output'=>'input', 'Ledgers.gst_type'=>'IGST'])->first();
						$AccountingEntrieIGST->ledger_id=$gstLedgerSGST->id;
						$AccountingEntrieIGST->credit=$purchase_return_row->gst_value;
						$AccountingEntrieIGST->debit=0;
						$AccountingEntrieIGST->transaction_date=$purchaseReturn->transaction_date;
						$AccountingEntrieIGST->company_id=$company_id;
						$AccountingEntrieIGST->purchase_return_id=$purchaseReturn->id;
						$this->PurchaseReturns->AccountingEntries->save($AccountingEntrieIGST);
					   }
				}
				//Refrence Details For Party/Supplier  //
				
				$Ledgers = $this->PurchaseReturns->PurchaseInvoices->PurchaseInvoiceRows->Ledgers->get($PurchaseInvoice->supplier_ledger_id);
				if($Ledgers->bill_to_bill_accounting=="yes"){
					$ReferenceDetail = $this->PurchaseReturns->ReferenceDetails->newEntity(); 
					$ReferenceDetail->ledger_id=$PurchaseInvoice->supplier_ledger_id;
					$ReferenceDetail->debit=$total_amount;
					$ReferenceDetail->credit=0;
					$ReferenceDetail->transaction_date=$purchaseReturn->transaction_date;
					$ReferenceDetail->company_id=$company_id;
					$ReferenceDetail->type='New Ref';
					$ReferenceDetail->ref_name='PR'.$purchaseReturn->voucher_no;
					$ReferenceDetail->purchase_return_id=$purchaseReturn->id;
					$this->PurchaseReturns->ReferenceDetails->save($ReferenceDetail);
				}

                return $this->redirect(['action' => 'index']);
            }
            $this->Flash->error(__('The purchase return could not be saved. Please, try again.'));
        }
        $purchaseInvoices = $this->PurchaseReturns->PurchaseInvoices->find('list', ['limit' => 200]);
        $companies = $this->PurchaseReturns->Companies->find('list', ['limit' => 200]);
        $this->set(compact('purchaseReturn', 'purchaseInvoices', 'companies','PurchaseInvoice','state_id','supplier_state_id','purchase_return_qty','NewVoucherNo'));
        $this->set('_serialize', ['purchaseReturn']);
    }

    /**
     * Edit method
     *
     * @param string|null $id Purchase Return id.
     * @return \Cake\Http\Response|null Redirects on successful edit, renders view otherwise.
     * @throws \Cake\Network\Exception\NotFoundException When record not found.
     */
    public function edit($id = null)
    {
        $purchaseReturn = $this->PurchaseReturns->get($id, [
            'contain' => []
        ]);
        if ($this->request->is(['patch', 'post', 'put'])) {
            $purchaseReturn = $this->PurchaseReturns->patchEntity($purchaseReturn, $this->request->getData());
            if ($this->PurchaseReturns->save($purchaseReturn)) {
                $this->Flash->success(__('The purchase return has been saved.'));

                return $this->redirect(['action' => 'index']);
            }
            $this->Flash->error(__('The purchase return could not be saved. Please, try again.'));
        }
        $purchaseInvoices = $this->PurchaseReturns->PurchaseInvoices->find('list', ['limit' => 200]);
        $companies = $this->PurchaseReturns->Companies->find('list', ['limit' => 200]);
        $this->set(compact('purchaseReturn', 'purchaseInvoices', 'companies'));
        $this->set('_serialize', ['purchaseReturn']);
    }

    /**
     * Delete method
     *
     * @param string|null $id Purchase Return id.
     * @return \Cake\Http\Response|null Redirects to index.
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function delete($id = null)
    {
        $this->request->allowMethod(['post', 'delete']);
        $purchaseReturn = $this->PurchaseReturns->get($id);
        if ($this->PurchaseReturns->delete($purchaseReturn)) {
            $this->Flash->success(__('The purchase return has been deleted.'));
        } else {
            $this->Flash->error(__('The purchase return could not be deleted. Please, try again.'));
        }

        return $this->redirect(['action' => 'index']);
    }
	
	public function cancel($id = null)
    {
		// $this->request->allowMethod(['post', 'delete']);
        $purchaseReturn = $this->PurchaseReturns->get($id);
		$company_id=$this->Auth->User('session_company_id');
		//pr($salesInvoice);exit;
		$purchaseReturn->status='cancel';
        if ($this->PurchaseReturns->save($purchaseReturn)) {
			
				$deleteRefDetails = $this->PurchaseReturns->ReferenceDetails->query();
				$deleteRef = $deleteRefDetails->delete()
					->where(['ReferenceDetails.purchase_return_id' => $purchaseReturn->id])
					->execute();
				$deleteAccountEntries = $this->PurchaseReturns->AccountingEntries->query();
				$result = $deleteAccountEntries->delete()
				->where(['AccountingEntries.purchase_return_id' => $purchaseReturn->id])
				->execute();
			
			$deleteItemLedger = $this->PurchaseReturns->ItemLedgers->query();
				$deleteResult = $deleteItemLedger->delete()
					->where(['ItemLedgers.purchase_return_id' => $purchaseReturn->id])
					->execute();
				
            $this->Flash->success(__('The Purchase Return has been cancelled.'));
        } else {
            $this->Flash->error(__('The Purchase Return could not be cancelled. Please, try again.'));
        }

        return $this->redirect(['action' => 'index']);
    }
	
	public function reportFilter()
    {
		$this->viewBuilder()->layout('index_layout');
		$company_id=$this->Auth->User('session_company_id');
		@$partyParentGroups = $this->PurchaseReturns->PurchaseInvoices->PurchaseInvoiceRows->Ledgers->AccountingGroups->find()
						->where(['AccountingGroups.company_id'=>$company_id, 'AccountingGroups.purchase_invoice_party'=>'1']);
		$partyGroups=[];
		
		foreach($partyParentGroups as $partyParentGroup)
		{
			$accountingGroups = $this->PurchaseReturns->PurchaseInvoices->PurchaseInvoiceRows->Ledgers->AccountingGroups
			->find('children', ['for' => $partyParentGroup->id])->toArray();
			$partyGroups[]=$partyParentGroup->id;
			foreach($accountingGroups as $accountingGroup){
				$partyGroups[]=$accountingGroup->id;
			}
		}
	
		if($partyGroups)
		{  
			$Partyledgers = $this->PurchaseReturns->PurchaseInvoices->PurchaseInvoiceRows->Ledgers->find()
							->where(['Ledgers.accounting_group_id IN' =>$partyGroups,'Ledgers.company_id'=>$company_id])->contain(['Suppliers']);
							
        }
		
		$partyOptions=[];
		foreach($Partyledgers as $Partyledger){
		$partyOptions[]=['text' =>str_pad(@$Partyledger->supplier->id, 4, '0', STR_PAD_LEFT).' - '.$Partyledger->name, 'value' => $Partyledger->id ];
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
		$where['PurchaseReturns.transaction_date >=']= $from_date;
		}
		if(!empty($to)){
			$to_date=date('Y-m-d', strtotime($to));
			$where['PurchaseReturns.transaction_date <='] = $to_date;
		}
		$party_ids=$this->request->query('supplier_ledger_id');
		
	
		
		if(!empty($party_ids)){
		$where['PurchaseReturns.supplier_ledger_id IN'] = $party_ids;
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
		$where1['PurchaseReturns.voucher_no IN'] = $invoice_ids;
		$where1['PurchaseReturns.company_id'] = $company_id;
		}
		if(!empty($where)){
		$purchaseReturns = $this->PurchaseReturns->find()->where(['PurchaseReturns.company_id'=>$company_id])->where($where)->orWhere($where1)
		->contain(['Companies','PurchaseReturnRows'=>['Items'=>['FirstGstFigures']],'PurchaseInvoices'=>['SupplierLedgers'=>['Suppliers']]])
        ->order(['PurchaseReturns.voucher_no' => 'ASC']);
		}
		else{
		$purchaseReturns = $this->PurchaseReturns->find()->where(['PurchaseReturns.company_id'=>$company_id])->where($where1)
		->contain(['Companies','PurchaseReturnRows'=>['Items'=>['FirstGstFigures']],'PurchaseInvoices'=>['SupplierLedgers'=>['Suppliers']]])
        ->order(['PurchaseReturns.voucher_no' => 'ASC']);
		}
		//pr($purchaseReturns->toArray());
		//exit;
		$this->set(compact('purchaseReturns', 'from', 'to','party_ids','invoice_no','url','status'));
        $this->set('_serialize', ['purchaseReturns']);
    } 
}
