<?php
namespace App\Controller;

use App\Controller\AppController;

/**
 * AccountingEntries Controller
 *
 * @property \App\Model\Table\AccountingEntriesTable $AccountingEntries
 *
 * @method \App\Model\Entity\AccountingEntry[] paginate($object = null, array $settings = [])
 */
class AccountingEntriesController extends AppController
{

    /**
     * Index method
     *
     * @return \Cake\Http\Response|void
     */
    public function index()
    {
        $this->paginate = [
            'contain' => ['Ledgers', 'Companies', 'PurchaseVouchers', 'SalesInvoices', 'SaleReturns', 'SalesVouchers', 'JournalVouchers']
        ];
        $accountingEntries = $this->paginate($this->AccountingEntries);

        $this->set(compact('accountingEntries'));
        $this->set('_serialize', ['accountingEntries']);
    }
	
	public function RatioReport()
    {
		$this->viewBuilder()->layout('index_layout');
        $company_id=$this->Auth->User('session_company_id');
		$to_date=$this->request->query('to_date');
		if($to_date){
		$to_date= date("Y-m-d",strtotime($to_date));
		}else{
		$to_date= date("Y-m-d");
		}
		$AccountingGroups=$this->AccountingEntries->Ledgers->AccountingGroups->find()
		->where(['AccountingGroups.nature_of_group_id IN'=>[3,4],'AccountingGroups.company_id'=>$company_id]);
		$Groups=[];
		foreach($AccountingGroups as $AccountingGroup){
			$Groups[$AccountingGroup->id]['ids'][]=$AccountingGroup->id;
			$Groups[$AccountingGroup->id]['name']=$AccountingGroup->name;
			$Groups[$AccountingGroup->id]['nature']=$AccountingGroup->nature_of_group_id;
			$accountingChildGroups = $this->AccountingEntries->Ledgers->AccountingGroups->find('children', ['for' => $AccountingGroup->id]);
			foreach($accountingChildGroups as $accountingChildGroup){
				$Groups[$AccountingGroup->id]['ids'][]=$accountingChildGroup->id;
			}
		}
		$AllGroups=[];
		foreach($Groups as $mainGroups){
			foreach($mainGroups['ids'] as $subGroup){
				$AllGroups[]=$subGroup;
			}
		}
		
		$query=$this->AccountingEntries->find();
		$query->select(['ledger_id','totalDebit' => $query->func()->sum('AccountingEntries.debit'),'totalCredit' => $query->func()->sum('AccountingEntries.credit')])
				->group('AccountingEntries.ledger_id')
				->where(['AccountingEntries.company_id'=>$company_id, 'AccountingEntries.transaction_date <='=>$to_date])
				->contain(['Ledgers'=>function($q){
					return $q->select(['Ledgers.accounting_group_id','Ledgers.id']);
				}]);
		$query->matching('Ledgers', function ($q) use($AllGroups){
			return $q->where(['Ledgers.accounting_group_id IN' => $AllGroups]);
		});
		$balanceOfLedgers=$query;
		$groupForPrint=[];
		foreach($balanceOfLedgers as $balanceOfLedger){
			foreach($Groups as $primaryGroup=>$Group){
				if(in_array($balanceOfLedger->ledger->accounting_group_id,$Group['ids'])){
					@$groupForPrint[$primaryGroup]['balance']+=$balanceOfLedger->totalDebit-$balanceOfLedger->totalCredit;
				}else{
					@$groupForPrint[$primaryGroup]['balance']+=0;
				}
				@$groupForPrint[$primaryGroup]['name']=$Group['name'];
				@$groupForPrint[$primaryGroup]['nature']=$Group['nature'];
			}
		}
		$openingValue= $this->StockValuationWithDate($to_date);
		$closingValue= $this->StockValuationWithDate2($to_date);
		$this->set(compact('from_date','to_date', 'groupForPrint', 'closingValue', 'openingValue'));
		
    }

	public function ProfitLossStatement()
    {
		$this->viewBuilder()->layout('index_layout');
        $company_id=$this->Auth->User('session_company_id');
		$from_date=$this->request->query('from_date');
		$to_date=$this->request->query('to_date');
		$from_date = date("Y-m-d",strtotime($from_date));
		$to_date= date("Y-m-d",strtotime($to_date));
		
		$AccountingGroups=$this->AccountingEntries->Ledgers->AccountingGroups->find()
		->where(['AccountingGroups.nature_of_group_id IN'=>[3,4],'AccountingGroups.company_id'=>$company_id]);
		$Groups=[];
		foreach($AccountingGroups as $AccountingGroup){
			$Groups[$AccountingGroup->id]['ids'][]=$AccountingGroup->id;
			$Groups[$AccountingGroup->id]['name']=$AccountingGroup->name;
			$Groups[$AccountingGroup->id]['nature']=$AccountingGroup->nature_of_group_id;
			$accountingChildGroups = $this->AccountingEntries->Ledgers->AccountingGroups->find('children', ['for' => $AccountingGroup->id]);
			foreach($accountingChildGroups as $accountingChildGroup){
				$Groups[$AccountingGroup->id]['ids'][]=$accountingChildGroup->id;
			}
		}
		$AllGroups=[];
		foreach($Groups as $mainGroups){
			foreach($mainGroups['ids'] as $subGroup){
				$AllGroups[]=$subGroup;
			}
		}
		
		$query=$this->AccountingEntries->find();
		$query->select(['ledger_id','totalDebit' => $query->func()->sum('AccountingEntries.debit'),'totalCredit' => $query->func()->sum('AccountingEntries.credit')])
				->group('AccountingEntries.ledger_id')
				->where(['AccountingEntries.company_id'=>$company_id,'AccountingEntries.transaction_date >='=>$from_date, 'AccountingEntries.transaction_date <='=>$to_date])
				->contain(['Ledgers'=>function($q){
					return $q->select(['Ledgers.accounting_group_id','Ledgers.id']);
				}]);
		$query->matching('Ledgers', function ($q) use($AllGroups){
			return $q->where(['Ledgers.accounting_group_id IN' => $AllGroups]);
		});
		$balanceOfLedgers=$query;
		$groupForPrint=[]; $d=[]; $c=[]; 
		foreach($balanceOfLedgers as $balanceOfLedger){
			foreach($Groups as $primaryGroup=>$Group){
				if(in_array($balanceOfLedger->ledger->accounting_group_id,$Group['ids'])){
					@$groupForPrint[$primaryGroup]['balance']+=$balanceOfLedger->totalDebit-abs($balanceOfLedger->totalCredit);
					////$d[$primaryGroup]=$balanceOfLedger->totalDebit;
					//$c[$primaryGroup]=$balanceOfLedger->totalCredit;
					
				}else{
					@$groupForPrint[$primaryGroup]['balance']+=0;
				}
				@$groupForPrint[$primaryGroup]['name']=$Group['name'];
				@$groupForPrint[$primaryGroup]['nature']=$Group['nature'];
			}
		}
		//pr($c); 
		//pr($groupForPrint); exit;
		$openingValue= $this->StockValuationWithDate($from_date);
		$closingValue= $this->StockValuationWithDate2($to_date);
		$this->set(compact('from_date','to_date', 'groupForPrint', 'closingValue', 'openingValue'));
		
    }

	public function TradingReport()
    {
		$this->viewBuilder()->layout('index_layout');
        $company_id=$this->Auth->User('session_company_id');
		$from_date=$this->request->query('from_date');
		$to_date=$this->request->query('to_date');
		$from_date = date("Y-m-d",strtotime($from_date));
		$to_date= date("Y-m-d",strtotime($to_date));
		
		$AccountingGroups=$this->AccountingEntries->Ledgers->AccountingGroups->find()
		->where(['AccountingGroups.nature_of_group_id IN'=>[3,4],'AccountingGroups.company_id'=>$company_id]);
		$Groups=[];
		foreach($AccountingGroups as $AccountingGroup){
			$Groups[$AccountingGroup->id]['ids'][]=$AccountingGroup->id;
			$Groups[$AccountingGroup->id]['name']=$AccountingGroup->name;
			$Groups[$AccountingGroup->id]['nature']=$AccountingGroup->nature_of_group_id;
			$accountingChildGroups = $this->AccountingEntries->Ledgers->AccountingGroups->find('children', ['for' => $AccountingGroup->id]);
			foreach($accountingChildGroups as $accountingChildGroup){
				$Groups[$AccountingGroup->id]['ids'][]=$accountingChildGroup->id;
			}
		}
		$AllGroups=[];
		foreach($Groups as $mainGroups){
			foreach($mainGroups['ids'] as $subGroup){
				$AllGroups[]=$subGroup;
			}
		}
		
		$query=$this->AccountingEntries->find();
		$query->select(['ledger_id','totalDebit' => $query->func()->sum('AccountingEntries.debit'),'totalCredit' => $query->func()->sum('AccountingEntries.credit')])
				->group('AccountingEntries.ledger_id')
				->where(['AccountingEntries.company_id'=>$company_id,'AccountingEntries.transaction_date >='=>$from_date, 'AccountingEntries.transaction_date <='=>$to_date])
				->contain(['Ledgers'=>function($q){
					return $q->select(['Ledgers.accounting_group_id','Ledgers.id']);
				}]);
		$query->matching('Ledgers', function ($q) use($AllGroups){
			return $q->where(['Ledgers.accounting_group_id IN' => $AllGroups]);
		});
		$balanceOfLedgers=$query;
		$groupForPrint=[];
		foreach($balanceOfLedgers as $balanceOfLedger){
			foreach($Groups as $primaryGroup=>$Group){
				if(in_array($balanceOfLedger->ledger->accounting_group_id,$Group['ids'])){
					@$groupForPrint[$primaryGroup]['balance']+=$balanceOfLedger->totalDebit-abs($balanceOfLedger->totalCredit);
				}else{
					@$groupForPrint[$primaryGroup]['balance']+=0;
				}
				@$groupForPrint[$primaryGroup]['name']=$Group['name'];
				@$groupForPrint[$primaryGroup]['nature']=$Group['nature'];
			}
		}
		//pr($groupForPrint); exit;
		$openingValue= $this->StockValuationWithDate($from_date);
		$closingValue= $this->StockValuationWithDate2($to_date);
		$this->set(compact('from_date','to_date', 'groupForPrint', 'closingValue', 'openingValue'));
		
    }
	
	public function BalanceSheet()
    {
		$this->viewBuilder()->layout('index_layout');
        $company_id=$this->Auth->User('session_company_id');
		$from_date=$this->request->query('from_date');
		$to_date=$this->request->query('to_date');
		$from_date = date("Y-m-d",strtotime($from_date));
		$to_date= date("Y-m-d",strtotime($to_date));
		
		$AccountingGroups=$this->AccountingEntries->Ledgers->AccountingGroups->find()->where(['AccountingGroups.nature_of_group_id IN'=>[1,2],'AccountingGroups.company_id'=>$company_id]);
		$Groups=[];
		foreach($AccountingGroups as $AccountingGroup){
			$Groups[$AccountingGroup->id]['ids'][]=$AccountingGroup->id;
			$Groups[$AccountingGroup->id]['name']=$AccountingGroup->name;
			$Groups[$AccountingGroup->id]['nature']=$AccountingGroup->nature_of_group_id;
			$accountingChildGroups = $this->AccountingEntries->Ledgers->AccountingGroups->find('children', ['for' => $AccountingGroup->id]);
			foreach($accountingChildGroups as $accountingChildGroup){
				$Groups[$AccountingGroup->id]['ids'][]=$accountingChildGroup->id;
			}
		}
		$AllGroups=[];
		foreach($Groups as $mainGroups){
			foreach($mainGroups['ids'] as $subGroup){
				$AllGroups[]=$subGroup;
			}
		}
		
		$query=$this->AccountingEntries->find();
		$query->select(['ledger_id','totalDebit' => $query->func()->sum('AccountingEntries.debit'),'totalCredit' => $query->func()->sum('AccountingEntries.credit')])
				->group('AccountingEntries.ledger_id')
				->where(['AccountingEntries.company_id'=>$company_id])
				->contain(['Ledgers'=>function($q){
					return $q->select(['Ledgers.accounting_group_id','Ledgers.id']);
				}]);
		$query->matching('Ledgers', function ($q) use($AllGroups){
			return $q->where(['Ledgers.accounting_group_id IN' => $AllGroups]);
		});
		$balanceOfLedgers=$query;
		
		$groupForPrint=[];
		foreach($balanceOfLedgers as $balanceOfLedger){
			foreach($Groups as $primaryGroup=>$Group){
				if(in_array($balanceOfLedger->ledger->accounting_group_id,$Group['ids'])){
					@$groupForPrint[$primaryGroup]['balance']+=$balanceOfLedger->totalDebit-abs($balanceOfLedger->totalCredit);
				}else{
					@$groupForPrint[$primaryGroup]['balance']+=0;
				}
				@$groupForPrint[$primaryGroup]['name']=$Group['name'];
				@$groupForPrint[$primaryGroup]['nature']=$Group['nature'];
			}
		}
		//pr($groupForPrint); exit;
		$GrossProfit= $this->GrossProfit($from_date,$to_date);
		$closingValue= $this->StockValuationWithDate2($to_date);
		$differenceInOpeningBalance= $this->differenceInOpeningBalance();
		$this->set(compact('from_date','to_date', 'groupForPrint', 'GrossProfit', 'closingValue', 'differenceInOpeningBalance'));
		
    }
	
	public function bankReconciliation()
    {
		$this->viewBuilder()->layout('index_layout');
        $company_id=$this->Auth->User('session_company_id');
		$from_date=$this->request->query('from_date');
		$to_date=$this->request->query('to_date');
		$ledger_id=$this->request->query('ledger_id');
		if($from_date){
			$from_date = date("Y-m-d",strtotime($from_date));
		}else{
			$from_date = date("Y-m-01");
		}
		
		if($to_date){
			$to_date= date("Y-m-d",strtotime($to_date));
		}else{
			$to_date = date("Y-m-d");
		}
		
		if($ledger_id){
			$AccountingEntries=$this->AccountingEntries->find()->contain(['PurchaseVouchers'=>['PurchaseVoucherRows'=>['Ledgers']],'Payments'=>['PaymentRows'=>['Ledgers']],'SalesVouchers'=>['SalesVoucherRows'=>['Ledgers']],'Receipts'=>['ReceiptRows'=>['Ledgers']],'ContraVouchers'=>['ContraVoucherRows'=>['Ledgers']],'CreditNotes'=>['CreditNoteRows'=>['Ledgers']],'DebitNotes'=>['DebitNoteRows'=>['Ledgers']]])->where(['AccountingEntries.transaction_date <='=>$to_date,'AccountingEntries.ledger_id'=>$ledger_id,'AccountingEntries.reconciliation_date'=>'0000-00-00','AccountingEntries.company_id'=>$company_id]);
		
			$query=$this->AccountingEntries->find();
			$query->select(['ledger_id','totalDebit' => $query->func()->sum('AccountingEntries.debit'),'totalCredit' => $query->func()->sum('AccountingEntries.credit')])
				->group('AccountingEntries.ledger_id')
				->where(['AccountingEntries.company_id'=>$company_id,'AccountingEntries.transaction_date <='=>$to_date,'AccountingEntries.reconciliation_date !='=>'0000-00-00','AccountingEntries.ledger_id'=>$ledger_id])->orWhere(['AccountingEntries.company_id'=>$company_id,'AccountingEntries.transaction_date <='=>$to_date,'AccountingEntries.reconciliation_date'=>'0000-00-00','AccountingEntries.ledger_id'=>$ledger_id,'AccountingEntries.is_opening_balance'=>'yes']);
				$BankEnteries=$query->first();
				$bank_credit=0; $bank_debit=0;
				@$bank_remaining=$BankEnteries->totalDebit-$BankEnteries->totalCredit;
					if($BankEnteries->totalDebit > $BankEnteries->totalCredit){
						@$bank_debit=$BankEnteries->totalDebit-$BankEnteries->totalCredit;}
						if($BankEnteries->totalDebit < $BankEnteries->totalCredit){
						@$bank_credit=$BankEnteries->totalCredit-$BankEnteries->totalDebit;
						}
						else if($BankEnteries->totalDebit == $BankEnteries->totalCredit){
						@$bank_credit='';
						@$bank_debit='';
						}
					
		foreach($AccountingEntries as $data){
			if(!empty($data->payment_id)){
				$data->hlink='Payment';
				$payment_rows1=$this->AccountingEntries->Payments->PaymentRows->find()->contain(['Ledgers'])->where(['PaymentRows.payment_id'=>$data->payment_id,'PaymentRows.ledger_id !='=>$ledger_id])->first();
				$data->ledger_name=$payment_rows1->ledger->name;
				$payment_rows2=$this->AccountingEntries->Payments->PaymentRows->find()->contain(['Ledgers'])->where(['PaymentRows.payment_id'=>$data->payment_id,'PaymentRows.ledger_id'=>$ledger_id])->first();
				$data->transaction_type=$payment_rows2->mode_of_payment;
				$data->cheque_no=$payment_rows2->cheque_no;
				$data->cheque_date=date("d-m-Y",strtotime($payment_rows2->cheque_date));
				
			}
			else if(!empty($data->receipt_id)){
				$data->hlink='Receipts';
				$receipt_rows1=$this->AccountingEntries->Receipts->ReceiptRows->find()->contain(['Ledgers'])->where(['ReceiptRows.receipt_id'=>$data->receipt_id,'ReceiptRows.ledger_id !='=>$ledger_id])->first();
				$data->ledger_name=$receipt_rows1->ledger->name;
				$receipt_rows2=$this->AccountingEntries->Receipts->ReceiptRows->find()->contain(['Ledgers'])->where(['ReceiptRows.receipt_id'=>$data->receipt_id,'ReceiptRows.ledger_id'=>$ledger_id])->first();
				$data->transaction_type=$receipt_rows2->mode_of_payment;
				$data->cheque_no=$receipt_rows2->cheque_no;
				$data->cheque_date=date("d-m-Y",strtotime($receipt_rows2->cheque_date));
			}
			else if(!empty($data->credit_note_id)){
				$data->hlink='Credit Notes';
				$credit_note_rows1=$this->AccountingEntries->CreditNotes->CreditNoteRows->find()->contain(['Ledgers'])->where(['CreditNoteRows.credit_note_id'=>$data->credit_note_id,'CreditNoteRows.ledger_id !='=>$ledger_id])->first();
				$data->ledger_name=$credit_note_rows1->ledger->name;
				$credit_note_rows2=$this->AccountingEntries->CreditNotes->CreditNoteRows->find()->contain(['Ledgers'])->where(['CreditNoteRows.credit_note_id'=>$data->credit_note_id,'CreditNoteRows.ledger_id'=>$ledger_id])->first();
				$data->transaction_type=$credit_note_rows2->mode_of_payment;
				$data->cheque_no=$credit_note_rows2->cheque_no;
				$data->cheque_date=date("d-m-Y",strtotime($credit_note_rows2->cheque_date));
			}
			else if(!empty($data->debit_note_id)){
				$data->hlink='Debit Notes';
				$debit_note_rows1=$this->AccountingEntries->DebitNotes->DebitNoteRows->find()->contain(['Ledgers'])->where(['DebitNoteRows.debit_note_id'=>$data->debit_note_id,'DebitNoteRows.ledger_id !='=>$ledger_id])->first();
				$data->ledger_name=$debit_note_rows1->ledger->name;
				$debit_note_rows2=$this->AccountingEntries->DebitNotes->DebitNoteRows->find()->contain(['Ledgers'])->where(['DebitNoteRows.debit_note_id'=>$data->debit_note_id,'DebitNoteRows.ledger_id'=>$ledger_id])->first();
				$data->transaction_type=$debit_note_rows2->mode_of_payment;
				$data->cheque_no=$debit_note_rows2->cheque_no;
				$data->cheque_date=date("d-m-Y",strtotime($debit_note_rows2->cheque_date));
			}
			else if(!empty($data->contra_voucher_id)){
				$data->hlink='Contra Voucher';
				$contra_rows1=$this->AccountingEntries->ContraVouchers->ContraVoucherRows->find()->contain(['Ledgers'])->where(['ContraVoucherRows.contra_voucher_id'=>$data->contra_voucher_id,'ContraVoucherRows.ledger_id !='=>$ledger_id])->first();
				$data->ledger_name=$contra_rows1->ledger->name;
				$contra_rows2=$this->AccountingEntries->ContraVouchers->ContraVoucherRows->find()->contain(['Ledgers'])->where(['ContraVoucherRows.contra_voucher_id'=>$data->contra_voucher_id,'ContraVoucherRows.ledger_id'=>$ledger_id])->first();
				$data->transaction_type=$contra_rows2->mode_of_payment;
				$data->cheque_no=$contra_rows2->cheque_no;
				$data->cheque_date=date("d-m-Y",strtotime($contra_rows2->cheque_date));
			}
			else if(!empty($data->purchase_voucher_id)){
				$data->hlink='Purchase Voucher';
				$purchase_voucher_rows1=$this->AccountingEntries->PurchaseVouchers->PurchaseVoucherRows->find()->contain(['Ledgers'])->where(['PurchaseVoucherRows.purchase_voucher_id'=>$data->purchase_voucher_id,'PurchaseVoucherRows.ledger_id !='=>$ledger_id])->first();
				$data->ledger_name=$purchase_voucher_rows1->ledger->name;
				$purchase_voucher_rows2=$this->AccountingEntries->PurchaseVouchers->PurchaseVoucherRows->find()->contain(['Ledgers'])->where(['PurchaseVoucherRows.purchase_voucher_id'=>$data->purchase_voucher_id,'PurchaseVoucherRows.ledger_id'=>$ledger_id])->first();
				$data->transaction_type=$purchase_voucher_rows2->mode_of_payment;
				$data->cheque_no=$purchase_voucher_rows2->cheque_no;
				$data->cheque_date=date("d-m-Y",strtotime($purchase_voucher_rows2->cheque_date));
			}
			else if(!empty($data->sales_voucher_id)){
				$data->hlink='Sales Voucher';
				$sales_voucher_rows1=$this->AccountingEntries->SalesVouchers->SalesVoucherRows->find()->contain(['Ledgers'])->where(['SalesVoucherRows.sales_voucher_id'=>$data->sales_voucher_id,'SalesVoucherRows.ledger_id !='=>$ledger_id])->first();
				$data->ledger_name=$sales_voucher_rows1->ledger->name;
				$sales_voucher_rows2=$this->AccountingEntries->SalesVouchers->SalesVoucherRows->find()->contain(['Ledgers'])->where(['SalesVoucherRows.sales_voucher_id'=>$data->sales_voucher_id,'SalesVoucherRows.ledger_id'=>$ledger_id])->first();
				$data->transaction_type=$sales_voucher_rows2->mode_of_payment;
				$data->cheque_no=$sales_voucher_rows2->cheque_no;
				$data->cheque_date=date("d-m-Y",strtotime($sales_voucher_rows2->cheque_date));
			}
		}
		}
		//pr($AccountingEntries->toArray());
		//exit;
	
		$bankParentGroups = $this->AccountingEntries->Ledgers->AccountingGroups->find()
						->where(['AccountingGroups.company_id'=>$company_id, 'AccountingGroups.bank'=>'1']);
						
		$bankGroups=[];
		
		foreach($bankParentGroups as $bankParentGroup)
		{
			$accountingGroups = $this->AccountingEntries->Ledgers->AccountingGroups
			->find('children', ['for' => $bankParentGroup->id])->toArray();
			$bankGroups[]=$bankParentGroup->id;
			foreach($accountingGroups as $accountingGroup){
				$bankGroups[]=$accountingGroup->id;
			}
		}
		if($bankGroups)
		{  
			$Bankledgers = $this->AccountingEntries->Ledgers->find()
							->where(['Ledgers.accounting_group_id IN' =>$bankGroups,'Ledgers.company_id'=>$company_id]);
        }
		$bankOptions=[];
		foreach($Bankledgers as $Bankledger){
		$bankOptions[]=['text' =>@$Bankledger->name, 'value' => $Bankledger->id];
		}
		$this->set(compact('from_date','to_date','ledger_id','bankOptions','AccountingEntries','bank_debit','bank_credit','bank_remaining'));
	}
	
	public function bankReconciliationView()
    {
		$this->viewBuilder()->layout('index_layout');
		$status=$this->request->query('status'); 
		if(!empty($status)){ 
			$this->viewBuilder()->layout('');	
		}else{ 
			$this->viewBuilder()->layout('index_layout');
		}
		$url=$this->request->here();
		$url=parse_url($url,PHP_URL_QUERY);
        $company_id=$this->Auth->User('session_company_id');
		$from_date=$this->request->query('from_date');
		$to_date=$this->request->query('to_date');
		$ledger_id=$this->request->query('ledger_id');
		if($from_date){
			$from_date = date("Y-m-d",strtotime($from_date));
		}else{
			$from_date="";
		}
		
		if($to_date){
			$to_date= date("Y-m-d",strtotime($to_date));
		}else{
			$to_date="";
		}
		if($ledger_id){
			$AccountingEntries=$this->AccountingEntries->find()->contain(['PurchaseVouchers'=>['PurchaseVoucherRows'=>['Ledgers']],'Payments'=>['PaymentRows'=>['Ledgers']],'SalesVouchers'=>['SalesVoucherRows'=>['Ledgers']],'Receipts'=>['ReceiptRows'=>['Ledgers']],'ContraVouchers'=>['ContraVoucherRows'=>['Ledgers']],'CreditNotes'=>['CreditNoteRows'=>['Ledgers']],'DebitNotes'=>['DebitNoteRows'=>['Ledgers']]])->where(['AccountingEntries.transaction_date >='=>$from_date,'AccountingEntries.transaction_date <='=>$to_date,'AccountingEntries.ledger_id'=>$ledger_id,'AccountingEntries.reconciliation_date !=' =>'0000-00-00','AccountingEntries.company_id'=>$company_id]);
		
			
		foreach($AccountingEntries as $data){
			if(!empty($data->payment_id)){
				$data->hlink='Payment';
				$payment_rows1=$this->AccountingEntries->Payments->PaymentRows->find()->contain(['Ledgers'])->where(['PaymentRows.payment_id'=>$data->payment_id,'PaymentRows.ledger_id !='=>$ledger_id])->first();
				$data->ledger_name=$payment_rows1->ledger->name;
				$payment_rows2=$this->AccountingEntries->Payments->PaymentRows->find()->contain(['Ledgers'])->where(['PaymentRows.payment_id'=>$data->payment_id,'PaymentRows.ledger_id'=>$ledger_id])->first();
				$data->transaction_type=$payment_rows2->mode_of_payment;
				$data->cheque_no=$payment_rows2->cheque_no;
				$data->cheque_date=date("d-m-Y",strtotime($payment_rows2->cheque_date));
				
			}
			else if(!empty($data->receipt_id)){
				$data->hlink='Receipts';
				$receipt_rows1=$this->AccountingEntries->Receipts->ReceiptRows->find()->contain(['Ledgers'])->where(['ReceiptRows.receipt_id'=>$data->receipt_id,'ReceiptRows.ledger_id !='=>$ledger_id])->first();
				$data->ledger_name=$receipt_rows1->ledger->name;
				$receipt_rows2=$this->AccountingEntries->Receipts->ReceiptRows->find()->contain(['Ledgers'])->where(['ReceiptRows.receipt_id'=>$data->receipt_id,'ReceiptRows.ledger_id'=>$ledger_id])->first();
				$data->transaction_type=$receipt_rows2->mode_of_payment;
				$data->cheque_no=$receipt_rows2->cheque_no;
				$data->cheque_date=date("d-m-Y",strtotime($receipt_rows2->cheque_date));
			}
			else if(!empty($data->credit_note_id)){
				$data->hlink='Credit Notes';
				$credit_note_rows1=$this->AccountingEntries->CreditNotes->CreditNoteRows->find()->contain(['Ledgers'])->where(['CreditNoteRows.credit_note_id'=>$data->credit_note_id,'CreditNoteRows.ledger_id !='=>$ledger_id])->first();
				$data->ledger_name=$credit_note_rows1->ledger->name;
				$credit_note_rows2=$this->AccountingEntries->CreditNotes->CreditNoteRows->find()->contain(['Ledgers'])->where(['CreditNoteRows.credit_note_id'=>$data->credit_note_id,'CreditNoteRows.ledger_id'=>$ledger_id])->first();
				$data->transaction_type=$credit_note_rows2->mode_of_payment;
				$data->cheque_no=$credit_note_rows2->cheque_no;
				$data->cheque_date=date("d-m-Y",strtotime($credit_note_rows2->cheque_date));
			}
			else if(!empty($data->debit_note_id)){
				$data->hlink='Debit Notes';
				$debit_note_rows1=$this->AccountingEntries->DebitNotes->DebitNoteRows->find()->contain(['Ledgers'])->where(['DebitNoteRows.debit_note_id'=>$data->debit_note_id,'DebitNoteRows.ledger_id !='=>$ledger_id])->first();
				$data->ledger_name=$debit_note_rows1->ledger->name;
				$debit_note_rows2=$this->AccountingEntries->DebitNotes->DebitNoteRows->find()->contain(['Ledgers'])->where(['DebitNoteRows.debit_note_id'=>$data->debit_note_id,'DebitNoteRows.ledger_id'=>$ledger_id])->first();
				$data->transaction_type=$debit_note_rows2->mode_of_payment;
				$data->cheque_no=$debit_note_rows2->cheque_no;
				$data->cheque_date=date("d-m-Y",strtotime($debit_note_rows2->cheque_date));
			}
			else if(!empty($data->contra_voucher_id)){
				$data->hlink='Contra Voucher';
				$contra_rows1=$this->AccountingEntries->ContraVouchers->ContraVoucherRows->find()->contain(['Ledgers'])->where(['ContraVoucherRows.contra_voucher_id'=>$data->contra_voucher_id,'ContraVoucherRows.ledger_id !='=>$ledger_id])->first();
				$data->ledger_name=$contra_rows1->ledger->name;
				$contra_rows2=$this->AccountingEntries->ContraVouchers->ContraVoucherRows->find()->contain(['Ledgers'])->where(['ContraVoucherRows.contra_voucher_id'=>$data->contra_voucher_id,'ContraVoucherRows.ledger_id'=>$ledger_id])->first();
				$data->transaction_type=$contra_rows2->mode_of_payment;
				$data->cheque_no=$contra_rows2->cheque_no;
				$data->cheque_date=date("d-m-Y",strtotime($contra_rows2->cheque_date));
			}
			else if(!empty($data->purchase_voucher_id)){
				$data->hlink='Purchase Voucher';
				$purchase_voucher_rows1=$this->AccountingEntries->PurchaseVouchers->PurchaseVoucherRows->find()->contain(['Ledgers'])->where(['PurchaseVoucherRows.purchase_voucher_id'=>$data->purchase_voucher_id,'PurchaseVoucherRows.ledger_id !='=>$ledger_id])->first();
				$data->ledger_name=$purchase_voucher_rows1->ledger->name;
				$purchase_voucher_rows2=$this->AccountingEntries->PurchaseVouchers->PurchaseVoucherRows->find()->contain(['Ledgers'])->where(['PurchaseVoucherRows.purchase_voucher_id'=>$data->purchase_voucher_id,'PurchaseVoucherRows.ledger_id'=>$ledger_id])->first();
				$data->transaction_type=$purchase_voucher_rows2->mode_of_payment;
				$data->cheque_no=$purchase_voucher_rows2->cheque_no;
				$data->cheque_date=date("d-m-Y",strtotime($purchase_voucher_rows2->cheque_date));
			}
			else if(!empty($data->sales_voucher_id)){
				$data->hlink='Sales Voucher';
				$sales_voucher_rows1=$this->AccountingEntries->SalesVouchers->SalesVoucherRows->find()->contain(['Ledgers'])->where(['SalesVoucherRows.sales_voucher_id'=>$data->sales_voucher_id,'SalesVoucherRows.ledger_id !='=>$ledger_id])->first();
				$data->ledger_name=$sales_voucher_rows1->ledger->name;
				$sales_voucher_rows2=$this->AccountingEntries->SalesVouchers->SalesVoucherRows->find()->contain(['Ledgers'])->where(['SalesVoucherRows.sales_voucher_id'=>$data->sales_voucher_id,'SalesVoucherRows.ledger_id'=>$ledger_id])->first();
				$data->transaction_type=$sales_voucher_rows2->mode_of_payment;
				$data->cheque_no=$sales_voucher_rows2->cheque_no;
				$data->cheque_date=date("d-m-Y",strtotime($sales_voucher_rows2->cheque_date));
			}
		}
		//pr($AccountingEntries->toArray());
		//exit;
	}
		$bankParentGroups = $this->AccountingEntries->Ledgers->AccountingGroups->find()
						->where(['AccountingGroups.company_id'=>$company_id, 'AccountingGroups.bank'=>'1']);
						
		$bankGroups=[];
		
		foreach($bankParentGroups as $bankParentGroup)
		{
			$accountingGroups = $this->AccountingEntries->Ledgers->AccountingGroups
			->find('children', ['for' => $bankParentGroup->id])->toArray();
			$bankGroups[]=$bankParentGroup->id;
			foreach($accountingGroups as $accountingGroup){
				$bankGroups[]=$accountingGroup->id;
			}
		}
		if($bankGroups)
		{  
			$Bankledgers = $this->AccountingEntries->Ledgers->find()
							->where(['Ledgers.accounting_group_id IN' =>$bankGroups,'Ledgers.company_id'=>$company_id]);
        }
		$bankOptions=[];
		foreach($Bankledgers as $Bankledger){
		$bankOptions[]=['text' =>@$Bankledger->name, 'value' => $Bankledger->id];
		}
		
		$companies=$this->AccountingEntries->Companies->find()->contain(['States'])->where(['Companies.id'=>$company_id])->first();
		$this->set(compact('companies','from_date','to_date','ledger_id','bankOptions','AccountingEntries','url','status'));
	}
    /**
     * View method
     *
     * @param string|null $id Accounting Entry id.
     * @return \Cake\Http\Response|void
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function view($id = null)
    {
        $accountingEntry = $this->AccountingEntries->get($id, [
            'contain' => ['Ledgers', 'Companies', 'PurchaseVouchers', 'SalesInvoices', 'SaleReturns', 'SalesVouchers', 'JournalVouchers']
        ]);

        $this->set('accountingEntry', $accountingEntry);
        $this->set('_serialize', ['accountingEntry']);
    }
	public function reconciliationDateUpdate($acc_entry_id=null,$reconciliation_date=null)
    {
		$this->viewBuilder()->layout('');
		//$ledger = $this->Ledgers->get($id);
		if($reconciliation_date=="yes"){
		$reconciliation_date="0000-00-00";
		}else{
		$reconciliation_date=date("Y-m-d",strtotime($reconciliation_date));
		}
		$query = $this->AccountingEntries->query();
		$query->update()
		->set(['reconciliation_date' => $reconciliation_date])
		->where(['id' => $acc_entry_id])
		->execute();
		exit;
    }
    /**
     * Add method
     *
     * @return \Cake\Http\Response|null Redirects on successful add, renders view otherwise.
     */
    public function add()
    {
        $accountingEntry = $this->AccountingEntries->newEntity();
        if ($this->request->is('post')) {
            $accountingEntry = $this->AccountingEntries->patchEntity($accountingEntry, $this->request->getData());
            if ($this->AccountingEntries->save($accountingEntry)) {
                $this->Flash->success(__('The accounting entry has been saved.'));

                return $this->redirect(['action' => 'index']);
            }
            $this->Flash->error(__('The accounting entry could not be saved. Please, try again.'));
        }
        $ledgers = $this->AccountingEntries->Ledgers->find('list', ['limit' => 200]);
        $companies = $this->AccountingEntries->Companies->find('list', ['limit' => 200]);
        $purchaseVouchers = $this->AccountingEntries->PurchaseVouchers->find('list', ['limit' => 200]);
        $salesInvoices = $this->AccountingEntries->SalesInvoices->find('list', ['limit' => 200]);
        $saleReturns = $this->AccountingEntries->SaleReturns->find('list', ['limit' => 200]);
        $salesVouchers = $this->AccountingEntries->SalesVouchers->find('list', ['limit' => 200]);
        $journalVouchers = $this->AccountingEntries->JournalVouchers->find('list', ['limit' => 200]);
        $this->set(compact('accountingEntry', 'ledgers', 'companies', 'purchaseVouchers', 'salesInvoices', 'saleReturns', 'salesVouchers', 'journalVouchers'));
        $this->set('_serialize', ['accountingEntry']);
    }

    /**
     * Edit method
     *
     * @param string|null $id Accounting Entry id.
     * @return \Cake\Http\Response|null Redirects on successful edit, renders view otherwise.
     * @throws \Cake\Network\Exception\NotFoundException When record not found.
     */
    public function edit($id = null)
    {
        $accountingEntry = $this->AccountingEntries->get($id, [
            'contain' => []
        ]);
        if ($this->request->is(['patch', 'post', 'put'])) {
            $accountingEntry = $this->AccountingEntries->patchEntity($accountingEntry, $this->request->getData());
            if ($this->AccountingEntries->save($accountingEntry)) {
                $this->Flash->success(__('The accounting entry has been saved.'));

                return $this->redirect(['action' => 'index']);
            }
            $this->Flash->error(__('The accounting entry could not be saved. Please, try again.'));
        }
        $ledgers = $this->AccountingEntries->Ledgers->find('list', ['limit' => 200]);
        $companies = $this->AccountingEntries->Companies->find('list', ['limit' => 200]);
        $purchaseVouchers = $this->AccountingEntries->PurchaseVouchers->find('list', ['limit' => 200]);
        $salesInvoices = $this->AccountingEntries->SalesInvoices->find('list', ['limit' => 200]);
        $saleReturns = $this->AccountingEntries->SaleReturns->find('list', ['limit' => 200]);
        $salesVouchers = $this->AccountingEntries->SalesVouchers->find('list', ['limit' => 200]);
        $journalVouchers = $this->AccountingEntries->JournalVouchers->find('list', ['limit' => 200]);
        $this->set(compact('accountingEntry', 'ledgers', 'companies', 'purchaseVouchers', 'salesInvoices', 'saleReturns', 'salesVouchers', 'journalVouchers'));
        $this->set('_serialize', ['accountingEntry']);
    }

    /**
     * Delete method
     *
     * @param string|null $id Accounting Entry id.
     * @return \Cake\Http\Response|null Redirects to index.
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function delete($id = null)
    {
        $this->request->allowMethod(['post', 'delete']);
        $accountingEntry = $this->AccountingEntries->get($id);
        if ($this->AccountingEntries->delete($accountingEntry)) {
            $this->Flash->success(__('The accounting entry has been deleted.'));
        } else {
            $this->Flash->error(__('The accounting entry could not be deleted. Please, try again.'));
        }

        return $this->redirect(['action' => 'index']);
    }

	public function firstSubGroupsPnl($group_id,$from_date,$to_date)
	{ 
		//$this->viewBuilder()->layout('index_layout');
        $company_id=$this->Auth->User('session_company_id');
		//$from_date=$this->request->query('from_date');
		//$to_date=$this->request->query('to_date'); pr($from_date); exit;
		$from_date = date("Y-m-d",strtotime($from_date));
		$to_date= date("Y-m-d",strtotime($to_date));
		$AccountLedgers = $this->AccountingEntries->Ledgers->exists(['accounting_group_id' => $group_id]);
		$status="Parent";
		if($AccountLedgers==1){
			$status="Child";
			$AccountingGroups=$this->AccountingEntries->Ledgers->find()->where(['Ledgers.accounting_group_id '=>$group_id,'Ledgers.company_id'=>$company_id]);
			$Groups=[]; $ledgerData=[];
			foreach($AccountingGroups as $AccountingGroup){
				$Groups[$AccountingGroup->id]['ids'][]=$AccountingGroup->id;
				$Groups[$AccountingGroup->id]['name']=$AccountingGroup->name;
				$ledgerData[$AccountingGroup->id]=$AccountingGroup->name;
				
			}
			$AllGroups=[];
			foreach($Groups as $mainGroups){
				foreach($mainGroups['ids'] as $subGroup){
					$AllGroups[]=$subGroup; 
				}
			} $groupForPrint=[];
			foreach($AllGroups as $AllGroup){ 
				
			$query=$this->AccountingEntries->find()->where(['AccountingEntries.ledger_id'=>$AllGroup]);  
					$query->select(['ledger_id','totalDebit' => $query->func()->sum('AccountingEntries.debit'),'totalCredit' => $query->func()->sum('AccountingEntries.credit')])
					->group('AccountingEntries.ledger_id')
					->where(['AccountingEntries.ledger_id'=>$AllGroup, 'AccountingEntries.transaction_date >='=>$from_date, 'AccountingEntries.transaction_date <='=>$to_date,'AccountingEntries.company_id'=>$company_id])->first(); 
					@$groupForPrint[$AllGroup]['balance']+=@$query->first()['totalDebit']-@$query->first()['totalCredit'];
			}
			 //pr($ledgerData); exit;
		
	}else{
		$AccountingGroups=$this->AccountingEntries->Ledgers->AccountingGroups->find()->where(['AccountingGroups.parent_id '=>$group_id,'AccountingGroups.company_id'=>$company_id]);
		$Groups=[];
		foreach($AccountingGroups as $AccountingGroup){
			$Groups[$AccountingGroup->id]['ids'][]=$AccountingGroup->id;
			$Groups[$AccountingGroup->id]['name']=$AccountingGroup->name;
			$Groups[$AccountingGroup->id]['nature']=$AccountingGroup->nature_of_group_id;
			$accountingChildGroups = $this->AccountingEntries->Ledgers->AccountingGroups->find('children', ['for' => $AccountingGroup->id]);
			foreach($accountingChildGroups as $accountingChildGroup){
				$Groups[$AccountingGroup->id]['ids'][]=$accountingChildGroup->id;
			}
		}
		$AllGroups=[];
		foreach($Groups as $mainGroups){
			foreach($mainGroups['ids'] as $subGroup){
				$AllGroups[]=$subGroup;
			}
		}
		
		$query=$this->AccountingEntries->find();
		$query->select(['ledger_id','totalDebit' => $query->func()->sum('AccountingEntries.debit'),'totalCredit' => $query->func()->sum('AccountingEntries.credit')])
				->group('AccountingEntries.ledger_id')
				->where(['AccountingEntries.company_id'=>$company_id])
				->contain(['Ledgers'=>function($q){
					return $q->select(['Ledgers.accounting_group_id','Ledgers.id']);
				}]);
		$query->matching('Ledgers', function ($q) use($AllGroups){
			return $q->where(['Ledgers.accounting_group_id IN' => $AllGroups]);
		});
		$balanceOfLedgers=$query;
		

		//pr($balanceOfLedgers->toArray()); exit;
		$groupForPrint=[];
		foreach($balanceOfLedgers as $balanceOfLedger){
			foreach($Groups as $primaryGroup=>$Group){
				if(in_array($balanceOfLedger->ledger->accounting_group_id,$Group['ids'])){
					@$groupForPrint[$primaryGroup]['balance']+=$balanceOfLedger->totalDebit-$balanceOfLedger->totalCredit;
				}else{
					@$groupForPrint[$primaryGroup]['balance']+=0;
				}
				@$groupForPrint[$primaryGroup]['name']=$Group['name'];
				@$groupForPrint[$primaryGroup]['nature']=$Group['nature'];
			}
		}
}

		$this->set(compact('from_date','to_date', 'groupForPrint', 'closingValue', 'openingValue','status','ledgerData'));
	}

	public function firstSubGroupsTb($group_id,$from_date,$to_date)
	{ 
        $company_id=$this->Auth->User('session_company_id');
		$from_date = date("Y-m-d",strtotime($from_date));
		$to_date= date("Y-m-d",strtotime($to_date));
		$AccountLedgers = $this->AccountingEntries->Ledgers->exists(['accounting_group_id' => $group_id]);
		$status="Parent";
		if($AccountLedgers==1){
			$status="Child";
			$AccountingGroups=$this->AccountingEntries->Ledgers->find()->where(['Ledgers.accounting_group_id '=>$group_id,'Ledgers.company_id'=>$company_id]);
			$Groups=[]; $ledgerData=[];
			foreach($AccountingGroups as $AccountingGroup){
				$Groups[$AccountingGroup->id]['ids'][]=$AccountingGroup->id;
				$Groups[$AccountingGroup->id]['name']=$AccountingGroup->name;
				$ledgerData[$AccountingGroup->id]=$AccountingGroup->name;
				
			} 	
//pr($ledgerData);
			$AllGroups=[];
			foreach($Groups as $mainGroups){
				foreach($mainGroups['ids'] as $subGroup){
					$AllGroups[]=$subGroup; 
				}
			} $ClosingBalanceForPrint=[]; $OpeningBalanceForPrint=[]; $TransactionsDr=[]; $TransactionsCr=[]; 
			foreach($AllGroups as $AllGroup){ 
				
				$query=$this->AccountingEntries->find()->where(['AccountingEntries.ledger_id'=>$AllGroup]);  
					$query->select(['ledger_id','totalDebit' => $query->func()->sum('AccountingEntries.debit'),'totalCredit' => $query->func()->sum('AccountingEntries.credit')])
					->group('AccountingEntries.ledger_id')
					->where(['AccountingEntries.ledger_id'=>$AllGroup, 'AccountingEntries.transaction_date <='=>$to_date,'AccountingEntries.company_id'=>$company_id])->first(); 
					//if($AllGroup==130){pr($query->first());exit; exit;}
					@$ClosingBalanceForPrint[$AllGroup]['balance']+=@$query->first()['totalDebit']-@$query->first()['totalCredit'];
					@$ClosingBalanceForPrint[$AllGroup]['name']=$Group['name'];
				//pr($Group);exit;
				$query1=$this->AccountingEntries->find()->where(['AccountingEntries.ledger_id'=>$AllGroup]);   
				$query1->select(['ledger_id','totalDebit' => $query1->func()->sum('AccountingEntries.debit'),'totalCredit' => $query1->func()->sum('AccountingEntries.credit')])
					->group('AccountingEntries.ledger_id')
					->where(['AccountingEntries.ledger_id'=>$AllGroup, 'AccountingEntries.transaction_date <='=>$from_date,'AccountingEntries.company_id'=>$company_id])->first();  //
				@$OpeningBalanceForPrint[$AllGroup]['balance']+=@$query1->first()['totalDebit']-@$query1->first()['totalCredit'];
				//if($AllGroup==130){pr($query1->first());exit; exit;}
				//pr($OpeningBalanceForPrint);
			
				$query2=$this->AccountingEntries->find()->where(['AccountingEntries.ledger_id'=>$AllGroup]);  
				$query2->select(['ledger_id','totalDebit' => $query2->func()->sum('AccountingEntries.debit'),'totalCredit' => $query2->func()->sum('AccountingEntries.credit')])
				->group('AccountingEntries.ledger_id')
					->where(['AccountingEntries.ledger_id'=>$AllGroup, 'AccountingEntries.transaction_date <='=>$to_date,'AccountingEntries.company_id'=>$company_id])->first(); 
					@$TransactionsDr[@$AllGroup]['balance']+=@$query2->first()['totalDebit'];
					@$TransactionsCr[@$AllGroup]['balance']+=@$query2->first()['totalCredit'];
				//$balanceOfLedgers=$query2;
				
			// pr($TransactionsDr); exit;
		}//exit;
	}else{
		$AccountingGroups=$this->AccountingEntries->Ledgers->AccountingGroups->find()->where(['AccountingGroups.parent_id'=>$group_id,'AccountingGroups.company_id'=>$company_id]);
		
		$Groups=[]; $ledgerData=[];
		foreach($AccountingGroups as $AccountingGroup){
			$Groups[$AccountingGroup->id]['ids'][]=$AccountingGroup->id;
			$Groups[$AccountingGroup->id]['name']=$AccountingGroup->name;
			$Groups[$AccountingGroup->id]['nature']=$AccountingGroup->nature_of_group_id;
			$accountingChildGroups = $this->AccountingEntries->Ledgers->AccountingGroups->find('children', ['for' => $AccountingGroup->id]);
			foreach($accountingChildGroups as $accountingChildGroup){
				$Groups[$AccountingGroup->id]['ids'][]=$accountingChildGroup->id;
				//$ledgerData[$AccountingGroup->id]=$AccountingGroup->name;
			}
		}
		$AllGroups=[];
		foreach($Groups as $mainGroups){
			foreach($mainGroups['ids'] as $subGroup){
				$AllGroups[]=$subGroup;
			}
		} //pr($AllGroups); exit;
		$query=$this->AccountingEntries->find();
		$query->select(['ledger_id','totalDebit' => $query->func()->sum('AccountingEntries.debit'),'totalCredit' => $query->func()->sum('AccountingEntries.credit')])
				->group('AccountingEntries.ledger_id')
				->where(['AccountingEntries.company_id'=>$company_id, 'AccountingEntries.transaction_date <='=>$to_date])
				->contain(['Ledgers'=>function($q){
					return $q->select(['Ledgers.accounting_group_id','Ledgers.id']);
				}]);
		$query->matching('Ledgers', function ($q) use($AllGroups){
			return $q->where(['Ledgers.accounting_group_id IN' => $AllGroups]);
		});
		$balanceOfLedgers=$query;
		$ClosingBalanceForPrint=[];
		foreach($balanceOfLedgers as $balanceOfLedger){
			foreach($Groups as $primaryGroup=>$Group){
				if(in_array($balanceOfLedger->ledger->accounting_group_id,$Group['ids'])){
					@$ClosingBalanceForPrint[$primaryGroup]['balance']+=$balanceOfLedger->totalDebit-$balanceOfLedger->totalCredit;
				}else{
					@$ClosingBalanceForPrint[$primaryGroup]['balance']+=0;
				}
				@$ClosingBalanceForPrint[$primaryGroup]['name']=$Group['name'];
				@$ClosingBalanceForPrint[$primaryGroup]['nature']=$Group['nature'];
			}
		} 
		
		$query1=$this->AccountingEntries->find();
		$query1->select(['ledger_id','totalDebit' => $query1->func()->sum('AccountingEntries.debit'),'totalCredit' => $query1->func()->sum('AccountingEntries.credit')])
				->group('AccountingEntries.ledger_id')
				->where(['AccountingEntries.company_id'=>$company_id, 'AccountingEntries.transaction_date <='=>$from_date])
				->contain(['Ledgers'=>function($q){
					return $q->select(['Ledgers.accounting_group_id','Ledgers.id']);
				}]);
		$query1->matching('Ledgers', function ($q) use($AllGroups){
			return $q->where(['Ledgers.accounting_group_id IN' => $AllGroups]);
		});
		$balanceOfLedgers=$query1;
		$OpeningBalanceForPrint=[];
		foreach($balanceOfLedgers as $balanceOfLedger){
			foreach($Groups as $primaryGroup=>$Group){
				if(in_array($balanceOfLedger->ledger->accounting_group_id,$Group['ids'])){
					@$OpeningBalanceForPrint[$primaryGroup]['balance']+=$balanceOfLedger->totalDebit-$balanceOfLedger->totalCredit;
				}else{
					@$OpeningBalanceForPrint[$primaryGroup]['balance']+=0;
				}
			}
		} 
	//	pr($OpeningBalanceForPrint); exit;
		$query2=$this->AccountingEntries->find();
		$query2->select(['ledger_id','totalDebit' => $query2->func()->sum('AccountingEntries.debit'),'totalCredit' => $query2->func()->sum('AccountingEntries.credit')])
				->group('AccountingEntries.ledger_id')
				->where(['AccountingEntries.company_id'=>$company_id, 'AccountingEntries.transaction_date >'=>$from_date, 'AccountingEntries.transaction_date <='=>$to_date])
				->contain(['Ledgers'=>function($q){
					return $q->select(['Ledgers.accounting_group_id','Ledgers.id']);
				}]);
		$query2->matching('Ledgers', function ($q) use($AllGroups){
			return $q->where(['Ledgers.accounting_group_id IN' => $AllGroups]);
		});
		$balanceOfLedgers=$query2;
		$TransactionsDr=[];
		$TransactionsCr=[];
		foreach($balanceOfLedgers as $balanceOfLedger){
			foreach($Groups as $primaryGroup=>$Group){
				if(in_array($balanceOfLedger->ledger->accounting_group_id,$Group['ids'])){
					@$TransactionsDr[$primaryGroup]['balance']+=$balanceOfLedger->totalDebit;
					@$TransactionsCr[$primaryGroup]['balance']+=$balanceOfLedger->totalCredit;
				}else{
					@$TransactionsCr[$primaryGroup]['balance']+=0;
				}
			}
		}
	} //pr($OpeningBalanceForPrint);exit;

		$this->set(compact('from_date','to_date', 'status','url','ClosingBalanceForPrint','OpeningBalanceForPrint','TransactionsCr','TransactionsDr','ledgerData'));
	}
	
	public function gstReport()
    {
		$this->viewBuilder()->layout('index_layout');
        $company_id=$this->Auth->User('session_company_id');
		$from_date=$this->request->query('from_date');
		$to_date=$this->request->query('to_date');
		$from_date = date("Y-m-d",strtotime($from_date));
		$to_date= date("Y-m-d",strtotime($to_date));
		//$from_date   ="2018-04-01";
		//$to_date   ="2019-04-31";
	
		if(($from_date=='1970-01-01') || ($to_date=='1970-01-01'))
		{
			$from_date = date("Y-m-01");
			$to_date   = date("Y-m-d");
		}else{
			$from_date = date("Y-m-d",strtotime($from_date));
			$to_date= date("Y-m-d",strtotime($to_date));
		}
		
		// OutPut GST Code
		$AccountingGroupOutputGst=$this->AccountingEntries->Ledgers->AccountingGroups->find()
		->where(['AccountingGroups.name'=>'OutPut GST','AccountingGroups.company_id'=>$company_id])->first();
		//pr($AccountingGroupOutputGst);
		$query=$this->AccountingEntries->find();
		$query->select(['ledger_id','totalDebit' => $query->func()->sum('AccountingEntries.debit'),'totalCredit' => $query->func()->sum('AccountingEntries.credit')])
				->group('AccountingEntries.ledger_id')
				->where(['AccountingEntries.company_id'=>$company_id,'AccountingEntries.transaction_date >='=>$from_date, 'AccountingEntries.transaction_date <='=>$to_date])
				->contain(['Ledgers'=>function($q){
					return $q->select(['Ledgers.accounting_group_id','Ledgers.id','Ledgers.gst_figure_id']);
				}]);
		
		$query->matching('Ledgers', function ($q) use($AccountingGroupOutputGst){
			return $q->where(['Ledgers.accounting_group_id IN' => $AccountingGroupOutputGst->id,'gst_type !='=>'IGST']);
		});
		//pr($query->toArray()); exit;
		$balanceOfLedgers=$query;
		$outputgst=[];
		foreach($balanceOfLedgers as $balanceOfLedger){ 
			if($balanceOfLedger->totalCredit > 0){ 
				@$outputgst[@$balanceOfLedger->ledger->gst_figure_id]+=@$balanceOfLedger->totalCredit;
			}
		} 
		
		$query=$this->AccountingEntries->SalesInvoices->find()->where(['SalesInvoices.company_id'=>$company_id,'SalesInvoices.transaction_date >='=>$from_date, 'SalesInvoices.transaction_date <='=>$to_date]);
		$query->contain(['SalesInvoiceRows'=>function($q){
					return $q->select(['sales_invoice_id','gst_figure_id','totalTaxable' => $q->func()->sum('SalesInvoiceRows.taxable_value'),'gst_value' => $q->func()->sum('SalesInvoiceRows.gst_value')])
					->group('SalesInvoiceRows.gst_figure_id');
				}]);
		
		$taxable_gst_wise=[];
		foreach($query->toArray() as $data){
			$x=sizeof($data->sales_invoice_rows);
			if($x > 0){
				$taxable_gst_wise[$data->sales_invoice_rows[0]->gst_figure_id]=$data->sales_invoice_rows[0]->totalTaxable;
				
			}
		}
		
		//pr($taxable_gst_wise); exit;
		
		//OutPut IGST Code
		$AccountingGroupOutputGst=$this->AccountingEntries->Ledgers->AccountingGroups->find()
		->where(['AccountingGroups.name'=>'OutPut GST','AccountingGroups.company_id'=>$company_id])->first();
		$query=$this->AccountingEntries->find();
		$query->select(['ledger_id','totalDebit' => $query->func()->sum('AccountingEntries.debit'),'totalCredit' => $query->func()->sum('AccountingEntries.credit')])
				->group('AccountingEntries.ledger_id')
				->where(['AccountingEntries.company_id'=>$company_id,'AccountingEntries.transaction_date >='=>$from_date, 'AccountingEntries.transaction_date <='=>$to_date])
				->contain(['Ledgers'=>function($q){
					return $q->select(['Ledgers.accounting_group_id','Ledgers.id','Ledgers.gst_figure_id']);
				}]);
		$query->matching('Ledgers', function ($q) use($AccountingGroupOutputGst){
			return $q->where(['Ledgers.accounting_group_id IN' => $AccountingGroupOutputGst->id,'gst_type'=>'IGST']);
		});
		$balanceOfLedgers=$query;
		$outputIgst=[]; 
		foreach($balanceOfLedgers as $balanceOfLedger){ 
			if($balanceOfLedger->totalCredit > 0){
				@$outputIgst[@$balanceOfLedger->ledger->gst_figure_id]+=@$balanceOfLedger->totalCredit;
			}
		} 
		
		
		// InPut GST Code
		$AccountingGroupOutputGst=$this->AccountingEntries->Ledgers->AccountingGroups->find()
		->where(['AccountingGroups.name'=>'Input GST','AccountingGroups.company_id'=>$company_id])->first();
		
		$query=$this->AccountingEntries->find();
		$query->select(['ledger_id','totalDebit' => $query->func()->sum('AccountingEntries.debit'),'totalCredit' => $query->func()->sum('AccountingEntries.credit')])
				->group('AccountingEntries.ledger_id')
				->where(['AccountingEntries.company_id'=>$company_id,'AccountingEntries.transaction_date >='=>$from_date, 'AccountingEntries.transaction_date <='=>$to_date])
				->contain(['Ledgers'=>function($q){
					return $q->select(['Ledgers.accounting_group_id','Ledgers.id','Ledgers.gst_figure_id']);
				}]);
		$query->matching('Ledgers', function ($q) use($AccountingGroupOutputGst){
			return $q->where(['Ledgers.accounting_group_id IN' => $AccountingGroupOutputGst->id,'gst_type !='=>'IGST']);
		});
		$balanceOfLedgers=$query; //pr($balanceOfLedgers->toArray()); exit;
		$inputgst=[];
		foreach($balanceOfLedgers as $balanceOfLedger){ 
			if($balanceOfLedger->totalDebit > 0){
				@$inputgst[@$balanceOfLedger->ledger->gst_figure_id]+=@$balanceOfLedger->totalDebit;
			}
		} 
		
		
		//InPut IGST Code
		$AccountingGroupOutputGst=$this->AccountingEntries->Ledgers->AccountingGroups->find()
		->where(['AccountingGroups.name'=>'Input GST','AccountingGroups.company_id'=>$company_id])->first();
		$query=$this->AccountingEntries->find();
		$query->select(['ledger_id','totalDebit' => $query->func()->sum('AccountingEntries.debit'),'totalCredit' => $query->func()->sum('AccountingEntries.credit')])
				->group('AccountingEntries.ledger_id')
				->where(['AccountingEntries.company_id'=>$company_id,'AccountingEntries.transaction_date >='=>$from_date, 'AccountingEntries.transaction_date <='=>$to_date])
				->contain(['Ledgers'=>function($q){
					return $q->select(['Ledgers.accounting_group_id','Ledgers.id','Ledgers.gst_figure_id']);
				}]);
		$query->matching('Ledgers', function ($q) use($AccountingGroupOutputGst){
			return $q->where(['Ledgers.accounting_group_id IN' => $AccountingGroupOutputGst->id,'gst_type'=>'IGST']);
		});
		$balanceOfLedgers=$query;
		$inputIgst=[]; 
		foreach($balanceOfLedgers as $balanceOfLedger){ 
			if($balanceOfLedger->totalCredit > 0){
				@$inputIgst[@$balanceOfLedger->ledger->gst_figure_id]+=@$balanceOfLedger->totalCredit;
			}
		} 
		//pr($inputIgst); exit;
		
		
		
		$GstFigures=$this->AccountingEntries->Ledgers->GstFigures->find()->where(['company_id'=>$company_id]);
		$this->set(compact('GstFigures','outputgst','outputIgst','inputgst','inputIgst','from_date','to_date','taxable_gst_wise'));
	}
	public function gstReportNew()
    {
		$this->viewBuilder()->layout('index_layout');
		$url=$this->request->here();
		$url=parse_url($url,PHP_URL_QUERY);
        $company_id=$this->Auth->User('session_company_id');
		$from_date=$this->request->query('from_date');
		$status=$this->request->query('status');
		$to_date=$this->request->query('to_date');
		$from_date = date("Y-m-d",strtotime($from_date));
		$to_date= date("Y-m-d",strtotime($to_date));
		//$from_date   ="2018-04-01";
		//$to_date   ="2019-04-31";
	
		if(($from_date=='1970-01-01') || ($to_date=='1970-01-01'))
		{
			$from_date = date("Y-m-01");
			$to_date   = date("Y-m-d");
		}else{
			$from_date = date("Y-m-d",strtotime($from_date));
			$to_date= date("Y-m-d",strtotime($to_date));
		}
		
		// OutPut GST Code
		
		$SalesInvoices=$this->AccountingEntries->SalesInvoices->find()->where(['SalesInvoices.company_id'=>$company_id,'SalesInvoices.transaction_date >='=>$from_date, 'SalesInvoices.transaction_date <='=>$to_date])->contain(['Customers'=>function($q){
					return $q->select(['Customers.state_id'])->contain(['States']);
				}]);
		$StateWiseTaxableAmt=[];
		$StateWiseGst=[];
		$TotalTaxable=0;
		$TotalCGst=0;
		$TotalSGst=0;
		$TotalIGst=0;
		$StateName=[];
		foreach($SalesInvoices as $data){ 
			$TotalTaxable+=$data->amount_before_tax;
			$TotalCGst+=$data->total_cgst;
			$TotalSGst+=$data->total_sgst;
			$TotalIGst+=$data->total_igst;
			@$StateWiseTaxableAmt[$data->customer->state_id]+=@$data->amount_before_tax;
			@$StateWiseGst[$data->customer->state_id]+=@$data->total_igst;
			@$StateName[$data->customer->state_id]=@$data->customer->state->name;
		}
		
		// Input GST Code
		
		/* $PurchaseInvoices=$this->AccountingEntries->PurchaseInvoices->find()->where(['PurchaseInvoices.company_id'=>$company_id,'PurchaseInvoices.transaction_date >='=>$from_date, 'PurchaseInvoices.transaction_date <='=>$to_date]); */
		
		//
		/* $PurchaseInvoices = $this->AccountingEntries->PurchaseInvoices->find()
			->contain(['Companies', 'PartyLedgers'=>['Customers'=>function($q){
					return $q->select(['Customers.state_id','name','gstin'])->contain(['States']);
				}],'SalesLedgers', 
			'PurchaseInvoiceRows' => function($q) {
				return $q->select(['sales_invoice_id','gst_figure_id','gst_total' => $q->func()->sum('PurchaseInvoiceRows.gst_value'),'total_taxable_amt' => $q->func()->sum('PurchaseInvoiceRows.taxable_value'),'total_net_amt' => $q->func()->sum('PurchaseInvoiceRows.net_amount')])->contain(['GstFigures'])->group('PurchaseInvoiceRows.gst_figure_id')->group('PurchaseInvoiceRows.purchase_invoice_id')->autoFields(true);
			}])
			->where(['PurchaseInvoices.company_id'=>$company_id]);
		
		//
		pr($PurchaseInvoices->toArray()); exit; */
		
		//$States=$this->AccountingEntries->SalesInvoices->Customers->States->find();
		$GstFigures=$this->AccountingEntries->Ledgers->GstFigures->find()->where(['company_id'=>$company_id]);
		$this->set(compact('TotalTaxable','TotalCGst','TotalSGst','TotalIGst','StateWiseTaxableAmt','from_date','to_date','StateWiseGst','States','StateName','status','url'));
	}
	
	
}
