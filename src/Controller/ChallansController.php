<?php
namespace App\Controller;

use App\Controller\AppController;

/**
 * Challans Controller
 *
 * @property \App\Model\Table\ChallansTable $Challans
 *
 * @method \App\Model\Entity\Challan[] paginate($object = null, array $settings = [])
 */
class ChallansController extends AppController
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
		/* if(!empty($status))
		{
			$where = $status;
		}
		else
		{
			$where = '';
		}
		 */
		
		
		$this->paginate = [
            'contain' => ['Companies', 'PartyLedgers', 'SalesLedgers'],
			'limit' => 100
        ];
		$item_id = $this->request->query('item_id');
		$where1=[];
		$where=[];
		if(!empty($item_id)){
			$where1['ChallanRows.item_id']=$item_id;
		}
		$where['Challans.company_id']=$company_id;
		$where['Challans.financial_year_id']=$financialYear_id;
		
		$challans = $this->paginate($this->Challans->find()
		->where($where)
		->contain(['ChallanRows'])
		->matching(
				'ChallanRows.Items', function ($q) use($where1) {
					return $q->where($where1);
				})
		->where([
		'OR' => [
            'Challans.voucher_no' => $search,
            // ...
            'PartyLedgers.name LIKE' => '%'.$search.'%',
			//.....
			'SalesLedgers.name LIKE' => '%'.$search.'%',
			//...
			'Challans.transaction_date ' => date('Y-m-d',strtotime($search)),
			//...
			'Challans.amount_after_tax' => $search
        ]])->group(['Challans.id'])->order(['voucher_no' => 'DESC'])); 
		$stockItems=$this->Challans->ChallanRows->Items->find('list')->where(['Items.company_id'=>$company_id]);
        $this->set(compact('challans','search','status','location_id','stockItems','item_id'));
        $this->set('_serialize', ['challans']);
    }
	
	
	public function ConvertedIntoInvoice($id=null){
		
		$challanDatas = $this->Challans->get($id);
		
		 
		    $transaction_date=date('Y-m-d', strtotime($challanDatas->transaction_date));
			$due_days=$challanDatas->due_days; 
           
            $salesInvoice->transaction_date=$transaction_date;
            $salesInvoice->financial_year_id=$financialYear_id;
			$Voucher_no = $this->SalesInvoices->find()->select(['voucher_no'])->where(['SalesInvoices.company_id'=>$company_id,'SalesInvoices.financial_year_id'=>$financialYear_id])->order(['voucher_no' => 'DESC'])->first();
			if($Voucher_no){
				$voucher_no=$Voucher_no->voucher_no+1;
			}else{
				$voucher_no=1;
			} 		
			$salesInvoice->voucher_no=$voucher_no;
			$salesInvoice->financial_year_id =$financialYear_id;
			if($salesInvoice->cash_or_credit=='cash'){
				$salesInvoice->customer_id=0;
			}
			
			if($salesInvoice->invoice_receipt_type=='cash' && $salesInvoice->invoiceReceiptTd==1){
					$salesInvoice->receipt_amount=$salesInvoice->amount_after_tax;
			}else{
				$salesInvoice->receipt_amount=0;
			}
			
		   if ($this->SalesInvoices->save($salesInvoice)) {
				
				if($salesInvoice->invoice_receipt_type=='cash' && $salesInvoice->invoiceReceiptTd==1)
				{
						$receiptVoucherNo = $this->SalesInvoices->Receipts->find()->select(['voucher_no'])->where(['Receipts.company_id'=>$company_id,'Receipts.financial_year_id'=>$salesInvoice->financial_year_id])->order(['voucher_no' => 'DESC'])->first();
						if($receiptVoucherNo)
						{
							$receipt_voucher_no=$receiptVoucherNo->voucher_no+1;
						}
						else
						{
							$receipt_voucher_no=1;
						}
						
						$receiptData = $this->SalesInvoices->Receipts->query();
								$receiptData->insert(['financial_year_id','voucher_no', 'company_id','transaction_date','amount','sales_invoice_id'])
										->values([
										'financial_year_id' => $salesInvoice->financial_year_id,
										'voucher_no' => $receipt_voucher_no,
										'company_id' => $salesInvoice->company_id,
										'transaction_date' => $salesInvoice->transaction_date,
										'amount' => $$salesInvoice->amount_after_tax,
										'sales_invoice_id' => $salesInvoice->id])
					  ->execute();
					  $receiptId = $this->SalesInvoices->Receipts->find()->select(['id'])->where(['Receipts.company_id'=>$company_id,'Receipts.sales_invoice_id'=>$salesInvoice->id])->first();
					 
						$receiptLedgerId = $this->SalesInvoices->SalesInvoiceRows->Ledgers->find()
						->where(['Ledgers.cash' =>'1','Ledgers.company_id'=>$company_id])->first();
						$refLedgerId = $this->SalesInvoices->SalesInvoiceRows->Ledgers->find()
						->where(['Ledgers.id' =>$salesInvoice->party_ledger_id,'Ledgers.company_id'=>$company_id])->first();
					  
					  $receiptRowData1 = $this->SalesInvoices->Receipts->ReceiptRows->query();
								$receiptRowData1->insert(['receipt_id','company_id','cr_dr', 'ledger_id', 'credit'])
										->values([
										'receipt_id' => $receiptId->id,
										'company_id' => $salesInvoice->company_id,
										'cr_dr' => 'Cr',
										'ledger_id' => $salesInvoice->party_ledger_id,
										'credit' => $salesInvoice->amount_after_tax])
					  ->execute();
					   $receiptRowData2 = $this->SalesInvoices->Receipts->ReceiptRows->query();
								$receiptRowData2->insert(['receipt_id','company_id','cr_dr', 'ledger_id', 'debit'])
										->values([
										'receipt_id' => $receiptId->id,
										'company_id' => $salesInvoice->company_id,
										'cr_dr' => 'Dr',
										'ledger_id' => $receiptLedgerId->id,
										'debit' => $salesInvoice->amount_after_tax])
					  ->execute();
					  
					  
					  
					   $receiptRowCrId = $this->SalesInvoices->Receipts->ReceiptRows->find()->select(['id'])->where(['ReceiptRows.company_id'=>$company_id,'ReceiptRows.receipt_id'=>$receiptId->id, 'ReceiptRows.cr_dr'=>'Cr'])->first();
					   $receiptRowDrId = $this->SalesInvoices->Receipts->ReceiptRows->find()->select(['id'])->where(['ReceiptRows.company_id'=>$company_id,'ReceiptRows.receipt_id'=>$receiptId->id, 'ReceiptRows.cr_dr'=>'Dr'])->first();
					  
					  if($refLedgerId->bill_to_bill_accounting=='yes')
						{
						        $refData1 = $this->SalesInvoices->Receipts->ReceiptRows->ReferenceDetails->query();
								$refData1->insert(['company_id','ledger_id','type', 'ref_name', 'debit', 'sales_invoice_id','due_days','transaction_date'])
										->values([
										'company_id' => $salesInvoice->company_id,
										'ledger_id' => $salesInvoice->party_ledger_id,
										'type' => 'New Ref',
										'ref_name' => $voucher_no,
										'debit' => $salesInvoice->amount_after_tax,
										'sales_invoice_id' => $salesInvoice->id,
										'due_days' => $due_days,
										'transaction_date' => $salesInvoice->transaction_date
										])
					  ->execute();	
					  
								$refData2 = $this->SalesInvoices->Receipts->ReceiptRows->ReferenceDetails->query();
								$refData2->insert(['company_id','ledger_id','type', 'ref_name', 'credit','receipt_id','receipt_row_id','transaction_date'])
										->values([
										'company_id' => $salesInvoice->company_id,
										'ledger_id' => $salesInvoice->party_ledger_id,
										'type' => 'Against',
										'ref_name' => $voucher_no,
										'credit' => $salesInvoice->amount_after_tax,
										'receipt_id' => $receiptId->id,
										'receipt_row_id' => $receiptRowCrId->id,
										'transaction_date' => $salesInvoice->transaction_date
										])
					  ->execute();
						}
					 
					//Accounting Entries for Receipt Start//
					$accountEntry = $this->SalesInvoices->Receipts->AccountingEntries->newEntity();
					$accountEntry->ledger_id                  = $salesInvoice->party_ledger_id;
					$accountEntry->debit                      = 0;
					$accountEntry->credit                     = $salesInvoice->amount_after_tax;
					$accountEntry->transaction_date           = $salesInvoice->transaction_date;
					$accountEntry->company_id                 = $company_id;
					$accountEntry->receipt_id                 = $receiptId->id;
					$accountEntry->receipt_row_id             = 0;
					$this->SalesInvoices->Receipts->AccountingEntries->save($accountEntry);
					
					$accountEntry = $this->SalesInvoices->Receipts->AccountingEntries->newEntity();
					$accountEntry->ledger_id                  = $receiptLedgerId->id;
					$accountEntry->debit                      = $salesInvoice->amount_after_tax;
					$accountEntry->credit                     = 0;
					$accountEntry->transaction_date           = $salesInvoice->transaction_date;
					$accountEntry->company_id                 = $company_id;
					$accountEntry->receipt_id                 = $receiptId->id;
					$accountEntry->receipt_row_id             = 0;
					$this->SalesInvoices->Receipts->AccountingEntries->save($accountEntry);
					//Accounting Entries for Receipt End//
					
				}
				else 
				if($salesInvoice->invoice_receipt_type=='credit' && $salesInvoice->invoiceReceiptTd==1)
				{
						$refLedgerId = $this->SalesInvoices->SalesInvoiceRows->Ledgers->find()
						->where(['Ledgers.id' =>$salesInvoice->party_ledger_id,'Ledgers.company_id'=>$company_id])->first();
					  
					  if($refLedgerId->bill_to_bill_accounting=='yes')
						{
						        $refData1 = $this->SalesInvoices->Receipts->ReceiptRows->ReferenceDetails->query();
								$refData1->insert(['company_id','ledger_id','type', 'ref_name', 'debit', 'sales_invoice_id','due_days','transaction_date'])
										->values([
										'company_id' => $salesInvoice->company_id,
										'ledger_id' => $salesInvoice->party_ledger_id,
										'type' => 'New Ref',
										'ref_name' => $voucher_no,
										'debit' => $salesInvoice->amount_after_tax,
										'sales_invoice_id' => $salesInvoice->id,
										'due_days'=>$due_days,
										'transaction_date' => $salesInvoice->transaction_date
										])
					  ->execute();
						}
				}
				

		       foreach($salesInvoice->sales_invoice_rows as $sales_invoice_row)
			   {
			   $exactRate=$sales_invoice_row->taxable_value/$sales_invoice_row->quantity;
					 $stockData = $this->SalesInvoices->ItemLedgers->query();
						$stockData->insert(['item_id', 'transaction_date','quantity', 'rate', 'amount', 'status', 'company_id', 'sales_invoice_id', 'sales_invoice_row_id', 'location_id'])
								->values([
								'item_id' => $sales_invoice_row->item_id,
								'transaction_date' => $salesInvoice->transaction_date,
								'quantity' => $sales_invoice_row->quantity,
								'rate' => $exactRate,
								'amount' => $sales_invoice_row->taxable_value,
								'status' => 'out',
								'company_id' => $salesInvoice->company_id,
								'sales_invoice_id' => $salesInvoice->id,
								'sales_invoice_row_id' => $sales_invoice_row->id,
								'location_id'=>$salesInvoice->location_id
								])
						->execute();
			   }
						$partyData = $this->SalesInvoices->AccountingEntries->query();
						$partyData->insert(['ledger_id', 'debit','credit', 'transaction_date', 'company_id', 'sales_invoice_id'])
						->values([
						'ledger_id' => $salesInvoice->party_ledger_id,
						'debit' => $salesInvoice->amount_after_tax,
						'credit' => '',
						'transaction_date' => $salesInvoice->transaction_date,
						'company_id' => $salesInvoice->company_id,
						'sales_invoice_id' => $salesInvoice->id
						])
						->execute();
						$accountData = $this->SalesInvoices->AccountingEntries->query();
						$accountData->insert(['ledger_id', 'debit','credit', 'transaction_date', 'company_id', 'sales_invoice_id'])
								->values([
								'ledger_id' => $salesInvoice->sales_ledger_id,
								'debit' => '',
								'credit' => $salesInvoice->amount_before_tax,
								'transaction_date' => $salesInvoice->transaction_date,
								'company_id' => $salesInvoice->company_id,
								'sales_invoice_id' => $salesInvoice->id
								])
						->execute();
						if(str_replace('-',' ',$salesInvoice->round_off)>0)
						{
							$roundData = $this->SalesInvoices->AccountingEntries->query();
							if($salesInvoice->isRoundofType=='0')
							{
							$debit=0;
							$credit=str_replace('-',' ',$salesInvoice->round_off);
							}
							else if($salesInvoice->isRoundofType=='1')
							{
							$credit=0;
							$debit=str_replace('-',' ',$salesInvoice->round_off);
							}
						$roundData->insert(['ledger_id', 'debit','credit', 'transaction_date', 'company_id', 'sales_invoice_id'])
								->values([
								'ledger_id' => $roundOffId->id,
								'debit' => $debit,
								'credit' => $credit,
								'transaction_date' => $salesInvoice->transaction_date,
								'company_id' => $salesInvoice->company_id,
								'sales_invoice_id' => $salesInvoice->id
								])
						->execute();
						}
								
           if($salesInvoice->is_interstate=='0'){
		   for(@$i=0; $i<2; $i++){
			   foreach($salesInvoice->sales_invoice_rows as $sales_invoice_row)
			   {
			     $gstVal=$sales_invoice_row->gst_value/2;
			   if($i==0){
			   $gstLedgers = $this->SalesInvoices->SalesInvoiceRows->Ledgers->find()
							->where(['Ledgers.gst_figure_id' =>$sales_invoice_row->gst_figure_id,'Ledgers.company_id'=>$company_id, 'Ledgers.input_output'=>'output', 'Ledgers.gst_type'=>'CGST'])->first();
			   $ledgerId=$gstLedgers->id;
			   }
			   if($i==1){ 
			   $gstLedgers = $this->SalesInvoices->SalesInvoiceRows->Ledgers->find()
							->where(['Ledgers.gst_figure_id' =>$sales_invoice_row->gst_figure_id,'Ledgers.company_id'=>$company_id, 'Ledgers.input_output'=>'output', 'Ledgers.gst_type'=>'SGST'])->first();
			   $ledgerId=$gstLedgers->id;
			   }
			   $accountData = $this->SalesInvoices->AccountingEntries->query();
						$accountData->insert(['ledger_id', 'debit','credit', 'transaction_date', 'company_id', 'sales_invoice_id'])
								->values([
								'ledger_id' => $ledgerId,
								'debit' => '',
								'credit' => $gstVal,
								'transaction_date' => $salesInvoice->transaction_date,
								'company_id' => $salesInvoice->company_id,
								'sales_invoice_id' => $salesInvoice->id
								])
						->execute();
			   }
			 }
			}
			else if($salesInvoice->is_interstate=='1'){
				foreach($salesInvoice->sales_invoice_rows as $sales_invoice_row)
			   {
			   @$gstVal=$sales_invoice_row->gst_value;
			   $gstLedgers = $this->SalesInvoices->SalesInvoiceRows->Ledgers->find()
							->where(['Ledgers.gst_figure_id' =>$sales_invoice_row->gst_figure_id,'Ledgers.company_id'=>$company_id, 'Ledgers.input_output'=>'output', 'Ledgers.gst_type'=>'IGST'])->first();
			   $ledgerId=$gstLedgers->id;
			   $accountData = $this->SalesInvoices->AccountingEntries->query();
						$accountData->insert(['ledger_id', 'debit','credit', 'transaction_date', 'company_id', 'sales_invoice_id'])
								->values([
								'ledger_id' => $ledgerId,
								'debit' => '',
								'credit' => $gstVal,
								'transaction_date' => $salesInvoice->transaction_date,
								'company_id' => $salesInvoice->company_id,
								'sales_invoice_id' => $salesInvoice->id
								])
						->execute();
			   }
		   }
		    $this->Flash->success(__('The sales invoice has been saved.'));
            return $this->redirect(['action' => 'salesInvoiceBill/'.$salesInvoice->id]);
		 }
		 
	}

    /**
     * View method
     *
     * @param string|null $id Challan id.
     * @return \Cake\Http\Response|void
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function view($id = null)
    {
        $challan = $this->Challans->get($id, [
            'contain' => ['FinancialYears', 'Companies', 'Customers', 'SalesLedgers', 'PartyLedgers', 'Locations', 'ChallanRows']
        ]);

        $this->set('challan', $challan);
        $this->set('_serialize', ['challan']);
    }

    /**
     * Add method
     *
     * @return \Cake\Http\Response|null Redirects on successful add, renders view otherwise.
     */
	 
	 public function add()
    {
		$this->viewBuilder()->layout('index_layout');
        $salesInvoice = $this->Challans->newEntity();
		$Customers = $this->Challans->Customers->newEntity();
		$company_id=$this->Auth->User('session_company_id');
		$location_id=$this->Auth->User('session_location_id');
		$stateDetails=$this->Auth->User('session_company');
		$financialYear_id=$this->Auth->User('financialYear_id');
		$state_id=$stateDetails->state_id;
		$due_days=0;
		
		$FinancialYearData=$this->Challans->Companies->FinancialYears->get($financialYear_id);
		
		$Voucher_no = $this->Challans->find()->select(['voucher_no'])->where(['Challans.company_id'=>$company_id,'Challans.financial_year_id'=>$financialYear_id])->order(['voucher_no' => 'DESC'])->first();
		
		if($Voucher_no)
		{
			$voucher_no=$Voucher_no->voucher_no+1;
		}
		else
		{
			$voucher_no=1;
		} 	
		//pr($Voucher_no ); exit;
        if ($this->request->is('post')) {
		    $transaction_date=date('Y-m-d', strtotime($this->request->data['transaction_date']));
			$due_days=$this->request->data['due_days']; 
            $salesInvoice = $this->Challans->patchEntity($salesInvoice, $this->request->getData());
            $salesInvoice->transaction_date=$transaction_date;
            $salesInvoice->financial_year_id=$financialYear_id;
			$Voucher_no = $this->Challans->find()->select(['voucher_no'])->where(['Challans.company_id'=>$company_id,'Challans.financial_year_id'=>$financialYear_id])->order(['voucher_no' => 'DESC'])->first();
			if($Voucher_no){
				$voucher_no=$Voucher_no->voucher_no+1;
			}else{
				$voucher_no=1;
			} 		
			$salesInvoice->voucher_no=$voucher_no;
			$salesInvoice->financial_year_id =$financialYear_id;
			$salesInvoice->status ="Pending";
			if($salesInvoice->cash_or_credit=='cash'){
				$salesInvoice->customer_id=0;
			}
			
			if($salesInvoice->invoice_receipt_type=='cash' && $salesInvoice->invoiceReceiptTd==1){
					$salesInvoice->receipt_amount=$salesInvoice->amount_after_tax;
			}else{
				$salesInvoice->receipt_amount=0;
			}
			//pr($salesInvoice);exit;
		   if ($this->Challans->save($salesInvoice)) {
				foreach($salesInvoice->challan_rows as $challan_row)
			   {
			   $exactRate=$challan_row->taxable_value/$challan_row->quantity;
					 $stockData = $this->Challans->ItemLedgers->query();
						$stockData->insert(['item_id', 'transaction_date','quantity', 'rate', 'amount', 'status', 'company_id', 'challan_id', 'challan_row_id', 'location_id'])
								->values([
								'item_id' => $challan_row->item_id,
								'transaction_date' => $salesInvoice->transaction_date,
								'quantity' => $challan_row->quantity,
								'rate' => $exactRate,
								'amount' => $challan_row->taxable_value,
								'status' => 'out',
								'company_id' => $salesInvoice->company_id,
								'challan_id' => $salesInvoice->id,
								'challan_row_id' => $challan_row->id,
								'location_id'=>$salesInvoice->location_id
								])
						->execute();
			   }
			   
				 $this->Flash->success(__('The challan has been saved.'));
				return $this->redirect(['action' => 'challanBill/'.$salesInvoice->id]);
			}else{
				$this->Flash->error(__('The challan could not be saved. Please, try again.'));
			}	
		 }
		  //$this->Flash->error(__('The challan could not be saved. Please, try again.'));
		
		
		$customers = $this->Challans->Customers->find()->where(['company_id'=>$company_id]);
		$customerOptions=[];
		foreach($customers as $customer){
			$customerOptions[]=['text' =>$customer->name, 'value' => $customer->id ,'customer_state_id'=>$customer->state_id];
		}
		
		
		$items = $this->Challans->ChallanRows->Items->find()
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
						
						return $query->where(['ItemLedgers.company_id' => $company_id, 'ItemLedgers.location_id' => $location_id]);
					},'FirstGstFigures', 'SecondGstFigures', 'Units']);
					
				$itemOptions=[];
			foreach($items as $d)
			{
				foreach($d->item_ledgers as $dd)
				{ //
					if($dd->total_in > $dd->total_out)
					{ 
					$itemOptions[]=['text'=>$d->item_code.' '.$d->name, 'value'=>$dd->item_id,'item_code'=>$d->item_code, 'first_gst_figure_id'=>$d->first_gst_figure_id, 'gst_amount'=>floatval($d->gst_amount), 'sales_rate'=>$d->sales_rate, 'second_gst_figure_id'=>$d->second_gst_figure_id, 'FirstGstFigure'=>$d->FirstGstFigures->tax_percentage, 'SecondGstFigure'=>$d->SecondGstFigures->tax_percentage];
					}
				}
			}
		
        $partyParentGroups = $this->Challans->ChallanRows->Ledgers->AccountingGroups->find()
						->where(['AccountingGroups.company_id'=>$company_id, 'AccountingGroups.sale_invoice_party'=>'1']);
		$partyGroups=[];
		
		foreach($partyParentGroups as $partyParentGroup)
		{
			$accountingGroups = $this->Challans->ChallanRows->Ledgers->AccountingGroups
			->find('children', ['for' => $partyParentGroup->id])->toArray();
			$partyGroups[]=$partyParentGroup->id;
			foreach($accountingGroups as $accountingGroup){
				$partyGroups[]=$accountingGroup->id;
			}
		}
	
		if($partyGroups)
		{  
			$Partyledgers = $this->Challans->ChallanRows->Ledgers->find()
							->where(['Ledgers.accounting_group_id IN' =>$partyGroups,'Ledgers.company_id'=>$company_id])
							->contain(['Customers']);
        }
		
		
		$partyOptions=[];
		foreach($Partyledgers as $Partyledger){
		
		$receiptAccountLedgers = $this->Challans->ChallanRows->Ledgers->AccountingGroups->find()
		->where(['AccountingGroups.id'=>$Partyledger->accounting_group_id,'AccountingGroups.customer'=>1])
		->orWhere(['AccountingGroups.id'=>$Partyledger->accounting_group_id,'AccountingGroups.supplier'=>1])->first();
		
		if($receiptAccountLedgers)
		{
			$receiptAccountLedgersName='1';
		}
		else{
			$receiptAccountLedgersName='0';
		}
			$partyOptions[]=['text' =>str_pad(@$Partyledger->customer->customer_id, 4, '0', STR_PAD_LEFT).' - '.$Partyledger->name, 'value' => $Partyledger->id ,'party_state_id'=>@$Partyledger->customer->state_id, 'partyexist'=>$receiptAccountLedgersName, 'billToBillAccounting'=>$Partyledger->bill_to_bill_accounting,'default_days'=>$Partyledger->default_credit_days];
		}
		
		$accountLedgers = $this->Challans->ChallanRows->Ledgers->AccountingGroups->find()->where(['AccountingGroups.sale_invoice_sales_account'=>1,'AccountingGroups.company_id'=>$company_id])->first();

		$accountingGroups2 = $this->Challans->ChallanRows->Ledgers->AccountingGroups
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
			$Accountledgers = $this->Challans->ChallanRows->Ledgers->find('list')->where(['Ledgers.accounting_group_id IN' =>$account_ids]);
        }
		
		        $states = $this->Challans->ChallanRows->Ledgers->Customers->States->find('list',
													['keyField' => function ($row) {
														return $row['id'];
													},
													'valueField' => function ($row) 
													{
														if($row['state_code']<=9)
														{
															return str_pad($this->_properties['state_code'], 1, '0', STR_PAD_LEFT).$row['state_code'].'-'. $row['name'] ;
														}
														else
														{
															return $row['state_code'].'-'. $row['name'] ;
														}
													}]);
													
		$cities = $this->Challans->ChallanRows->Ledgers->Customers->Cities->  find('list',
													['keyField' => function ($row) {
														return $row['id'];
													},
													'valueField' => function ($row) 
													{
														if($row['city_code']<=9)
														{
															return str_pad($this->_properties['city_code'], 1, '0', STR_PAD_LEFT).$row['city_code'].'-'. $row['name'] ;
														}
														else
														{
															return $row['city_code'].'-'. $row['name'] ;
														}
													}]);
		
						
		$gstFigures = $this->Challans->GstFigures->find('list')
						->where(['company_id'=>$company_id]);
						
		$CashPartyLedgers = $this->Challans->ChallanRows->Ledgers->find()
							->where(['Ledgers.cash ' =>1,'Ledgers.company_id'=>$company_id])->first();
		$this->set(compact('salesInvoice', 'companies', 'customerOptions', 'gstFigures', 'voucher_no','company_id','itemOptions','state_id', 'partyOptions', 'Accountledgers', 'location_id', 'CashPartyLedgers','FinancialYearData','states','cities'));
        $this->set('_serialize', ['salesInvoice']);
    } 
	 
	 
	public function challanBill($id=null)
    {
		
	    $this->viewBuilder()->layout('');
		$company_id=$this->Auth->User('session_company_id');
		$stateDetails=$this->Auth->User('session_company');
		$state_id=$stateDetails->state_id;
		$invoiceBills= $this->Challans->find()
		->where(['Challans.id'=>$id])
		->contain(['Companies'=>['States'],'ChallanRows'=>['Items'=>['Sizes','Shades','Units'], 'GstFigures']]);
		
		$unit_ids=[];
		//pr($units->toArray());
		//exit;
	    foreach($invoiceBills->toArray() as $data){
			
		foreach($data->challan_rows as $sales_invoice_row){
		if(!in_array($sales_invoice_row->item->unit_id,$unit_ids)){
			$unit_ids[]=$sales_invoice_row->item->unit_id;
			}
		$item_id=$sales_invoice_row->item_id;
		//$accountingEntries= $this->Challans->AccountingEntries->find()
		//->where(['AccountingEntries.sales_invoice_id'=>$data->id]);
		//$sales_invoice_row->accountEntries=$accountingEntries->toArray();
		
			$partyDetail= $this->Challans->ChallanRows->Ledgers->find()
			->where(['id'=>$data->party_ledger_id])->first();
		    $partyCustomerid=$partyDetail->customer_id;
			if($partyCustomerid>0)
			{
				$partyDetails= $this->Challans->Customers->find()
				->where(['Customers.id'=>$partyCustomerid])
				->contain(['States', 'Cities'])->first();
				$data->partyDetails=$partyDetails;
			}
			else
			{
				$partyDetails=(object)['name'=>'Cash Customer', 'state_id'=>$state_id];
				$data->partyDetails=$partyDetails;
			}
			
			if(@$data->company->state_id==@$data->partyDetails->state_id){
				$taxable_type='CGST/SGST';
			}else{
				$taxable_type='IGST';
			}
			
		}
		}
		//pr($unit_ids);exit;
		$query = $this->Challans->ChallanRows->find();
		
		$totalTaxableAmt = $query->newExpr()
			->addCase(
				$query->newExpr()->add(['challan_id']),
				$query->newExpr()->add(['taxable_value']),
				'integer'
			);
		$totalgstAmt = $query->newExpr()
			->addCase(
				$query->newExpr()->add(['challan_id']),
				$query->newExpr()->add(['gst_value']),
				'integer'
			);
		$query->select([
			'total_taxable_amount' => $query->func()->sum($totalTaxableAmt),
			'total_gst_amount' => $query->func()->sum($totalgstAmt),'challan_id','item_id'
		])
		->where(['ChallanRows.challan_id' => $id])
		->group('gst_figure_id')
		->autoFields(true)
		->contain(['GstFigures']);
        $sale_invoice_rows = ($query);
		
		//pr($invoiceBills->toArray());exit;
		
		
		$this->set(compact('invoiceBills','taxable_type','sale_invoice_rows','partyCustomerid','units'));
        $this->set('_serialize', ['invoiceBills']);
    } 
	 
    public function add_()
    {
        $challan = $this->Challans->newEntity();
        if ($this->request->is('post')) {
            $challan = $this->Challans->patchEntity($challan, $this->request->getData());
            if ($this->Challans->save($challan)) {
                $this->Flash->success(__('The challan has been saved.'));

                return $this->redirect(['action' => 'index']);
            }
            $this->Flash->error(__('The challan could not be saved. Please, try again.'));
        }
        $financialYears = $this->Challans->FinancialYears->find('list', ['limit' => 200]);
        $companies = $this->Challans->Companies->find('list', ['limit' => 200]);
        $customers = $this->Challans->Customers->find('list', ['limit' => 200]);
        $salesLedgers = $this->Challans->SalesLedgers->find('list', ['limit' => 200]);
        $partyLedgers = $this->Challans->PartyLedgers->find('list', ['limit' => 200]);
        $locations = $this->Challans->Locations->find('list', ['limit' => 200]);
        $this->set(compact('challan', 'financialYears', 'companies', 'customers', 'salesLedgers', 'partyLedgers', 'locations'));
        $this->set('_serialize', ['challan']);
    }

    /**
     * Edit method
     *
     * @param string|null $id Challan id.
     * @return \Cake\Http\Response|null Redirects on successful edit, renders view otherwise.
     * @throws \Cake\Network\Exception\NotFoundException When record not found.
     */
    public function edit($id = null)
    {
        $challan = $this->Challans->get($id, [
            'contain' => []
        ]);
        if ($this->request->is(['patch', 'post', 'put'])) {
            $challan = $this->Challans->patchEntity($challan, $this->request->getData());
            if ($this->Challans->save($challan)) {
                $this->Flash->success(__('The challan has been saved.'));

                return $this->redirect(['action' => 'index']);
            }
            $this->Flash->error(__('The challan could not be saved. Please, try again.'));
        }
        $financialYears = $this->Challans->FinancialYears->find('list', ['limit' => 200]);
        $companies = $this->Challans->Companies->find('list', ['limit' => 200]);
        $customers = $this->Challans->Customers->find('list', ['limit' => 200]);
        $salesLedgers = $this->Challans->SalesLedgers->find('list', ['limit' => 200]);
        $partyLedgers = $this->Challans->PartyLedgers->find('list', ['limit' => 200]);
        $locations = $this->Challans->Locations->find('list', ['limit' => 200]);
        $this->set(compact('challan', 'financialYears', 'companies', 'customers', 'salesLedgers', 'partyLedgers', 'locations'));
        $this->set('_serialize', ['challan']);
    }

    /**
     * Delete method
     *
     * @param string|null $id Challan id.
     * @return \Cake\Http\Response|null Redirects to index.
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function delete($id = null)
    {
        $this->request->allowMethod(['post', 'delete']);
        $challan = $this->Challans->get($id);
        if ($this->Challans->delete($challan)) {
            $this->Flash->success(__('The challan has been deleted.'));
        } else {
            $this->Flash->error(__('The challan could not be deleted. Please, try again.'));
        }

        return $this->redirect(['action' => 'index']);
    }
}
