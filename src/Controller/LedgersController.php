<?php
namespace App\Controller;

use App\Controller\AppController;

/**
 * Ledgers Controller
 *
 * @property \App\Model\Table\LedgersTable $Ledgers
 *
 * @method \App\Model\Entity\Ledger[] paginate($object = null, array $settings = [])
 */
class LedgersController extends AppController
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
            'contain' => ['AccountingGroups', 'Companies'],
			'limit' => 100
        ];
        $ledgers = $this->paginate($this->Ledgers->find()->where(['Ledgers.company_id'=>$company_id])->where([
		'OR' => [
            'Ledgers.name LIKE' => '%'.$search.'%',
			//...
			'AccountingGroups.name LIKE' => '%'.$search.'%'
		]]));

        //pr($ledgers->toArray());exit;
        $this->set(compact('ledgers','search'));
        $this->set('_serialize', ['ledgers']);
    }

    /**
     * View method
     *
     * @param string|null $id Ledger id.
     * @return \Cake\Http\Response|void
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function view($id = null)
    {
        $ledger = $this->Ledgers->get($id, [
            'contain' => ['AccountingGroups', 'Suppliers', 'Customers', 'AccountingEntries']
        ]);

        $this->set('ledger', $ledger);
        $this->set('_serialize', ['ledger']);
    }

    /**
     * Add method
     *
     * @return \Cake\Http\Response|null Redirects on successful add, renders view otherwise.
     */
    public function add()
    {
		$this->viewBuilder()->layout('index_layout');
        $ledger = $this->Ledgers->newEntity();
		$company_id=$this->Auth->User('session_company_id');
        if ($this->request->is('post')) 
		{
            $ledger = $this->Ledgers->patchEntity($ledger, $this->request->getData());
			$ledger->company_id = $company_id;
            if ($this->Ledgers->save($ledger)) 
			{
				//Create Accounting Entry//
				$transaction_date=$this->Auth->User('session_company')->books_beginning_from;
				$AccountingEntry = $this->Ledgers->AccountingEntries->newEntity();
				$AccountingEntry->ledger_id = $ledger->id;
				if($ledger->debit_credit=="Dr")
				{
					$AccountingEntry->debit = $ledger->opening_balance_value;
				}
				if($ledger->debit_credit=="Cr")
				{
					$AccountingEntry->credit = $ledger->opening_balance_value;
				}
				$AccountingEntry->transaction_date      = date("Y-m-d",strtotime($transaction_date));
				$AccountingEntry->company_id            = $company_id;
				$AccountingEntry->is_opening_balance    = 'yes';
				$this->Ledgers->AccountingEntries->save($AccountingEntry);
				
                $this->Flash->success(__('The ledger has been saved.'));

                return $this->redirect(['action' => 'add']);
            }
            $this->Flash->error(__('The ledger could not be saved. Please, try again.'));
        }
		$SundryDebtor = $this->Ledgers->AccountingGroups->find('all')->where(['customer'=>1,'company_id'=>$company_id])->first();
		$accountingGroupdebitors = $this->Ledgers->AccountingGroups
							->find('children', ['for' => $SundryDebtor->id])
							->find('all');
		$debtorArray=[];
		foreach($accountingGroupdebitors as $accountingGroupdebitor)
		{ 
			$debtorArray[]= $accountingGroupdebitor->id;
		}
		$datadebtor[]=$SundryDebtor->id;
		$SundryCredior = $this->Ledgers->AccountingGroups->find('all')->where(['supplier'=>1,'company_id'=>$company_id])->first();
		$accountingGroupcreditors = $this->Ledgers->AccountingGroups
							->find('children', ['for' => $SundryCredior->id])
							->find('all');
		$creditorArray=[];
		foreach($accountingGroupcreditors as $accountingGroupcreditor)
		{ 
			$creditorArray[]= $accountingGroupcreditor->id;
		}
		$datacreditor[]=$SundryCredior->id;
		$alldebtors=array_merge($datadebtor,$debtorArray,$datacreditor,$creditorArray);
		$accountingGroups = $this->Ledgers->AccountingGroups->find('list')->where(['id NOT IN'=>$alldebtors,'company_id'=>$company_id]);
		
			
        $suppliers = $this->Ledgers->Suppliers->find('list')->where(['company_id'=>$company_id]);
        $customers = $this->Ledgers->Customers->find('list')->where(['company_id'=>$company_id]);
        $this->set(compact('ledger', 'accountingGroups',  'suppliers', 'customers'));
        $this->set('_serialize', ['ledger']);
    }

    /**
     * Edit method
     *
     * @param string|null $id Ledger id.
     * @return \Cake\Http\Response|null Redirects on successful edit, renders view otherwise.
     * @throws \Cake\Network\Exception\NotFoundException When record not found.
     */
    public function edit($id = null)
    {
		$this->viewBuilder()->layout('index_layout');
        $ledger = $this->Ledgers->get($id, [
            'contain' => ['AccountingEntries'=>function($q){
				return $q->where(['AccountingEntries.is_opening_balance'=>'yes']);
			}],
        ]);
		
		$company_id=$this->Auth->User('session_company_id');
        if ($this->request->is(['patch', 'post', 'put'])) 
		{
            $ledger = $this->Ledgers->patchEntity($ledger, $this->request->getData());
            if ($this->Ledgers->save($ledger)) 
			{
				//Accounting Entry
				$query_delete = $this->Ledgers->AccountingEntries->query();
				$query_delete->delete()
				->where(['ledger_id' => $ledger->id,'company_id'=>$company_id,'is_opening_balance'=>'yes'])
				->execute();
				
				$transaction_date=$this->Auth->User('session_company')->books_beginning_from;
				$AccountingEntry = $this->Ledgers->AccountingEntries->newEntity();
				$AccountingEntry->ledger_id = $ledger->id;
				if($ledger->debit_credit=="Dr")
				{
					$AccountingEntry->debit = $ledger->opening_balance_value;
				}
				if($ledger->debit_credit=="Cr")
				{
					$AccountingEntry->credit = $ledger->opening_balance_value;
				}
				$AccountingEntry->transaction_date      = date("Y-m-d",strtotime($transaction_date));
				$AccountingEntry->company_id            = $company_id;
				$AccountingEntry->is_opening_balance    = 'yes';
				$this->Ledgers->AccountingEntries->save($AccountingEntry);
				
                $this->Flash->success(__('The ledger has been saved.'));

                return $this->redirect(['action' => 'index']);
            }
            $this->Flash->error(__('The ledger could not be saved. Please, try again.'));
        }
        $SundryDebtor = $this->Ledgers->AccountingGroups->find('all')->where(['customer'=>1,'company_id'=>$company_id])->first();
		$accountingGroupdebitors = $this->Ledgers->AccountingGroups
							->find('children', ['for' => $SundryDebtor->id])
							->find('all');
		$debtorArray=[];
		foreach($accountingGroupdebitors as $accountingGroupdebitor)
		{ 
			$debtorArray[]= $accountingGroupdebitor->id;
		}
		$datadebtor[]=$SundryDebtor->id;
		$SundryCredior = $this->Ledgers->AccountingGroups->find('all')->where(['supplier'=>1,'company_id'=>$company_id])->first();
		$accountingGroupcreditors = $this->Ledgers->AccountingGroups
							->find('children', ['for' => $SundryCredior->id])
							->find('all');
		$creditorArray=[];
		foreach($accountingGroupcreditors as $accountingGroupcreditor)
		{ 
			$creditorArray[]= $accountingGroupcreditor->id;
		}
		$datacreditor[]=$SundryCredior->id;
		$alldebtors=array_merge($datadebtor,$debtorArray,$datacreditor,$creditorArray);
		$accountingGroups = $this->Ledgers->AccountingGroups->find('list')->where(['id NOT IN'=>$alldebtors,'company_id'=>$company_id]);
		$suppliers = $this->Ledgers->Suppliers->find('list')->where(['company_id'=>$company_id]);
        $customers = $this->Ledgers->Customers->find('list')->where(['company_id'=>$company_id]);
        $this->set(compact('ledger', 'accountingGroups', 'suppliers', 'customers'));
        $this->set('_serialize', ['ledger']);
    }

    /**
     * Delete method
     *
     * @param string|null $id Ledger id.
     * @return \Cake\Http\Response|null Redirects to index.
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function delete($id = null)
    {
       
		$this->request->allowMethod(['post', 'delete']);
        $ledger = $this->Ledgers->get($id);
        if ($this->Ledgers->delete($ledger)) 
		{
            $this->Flash->success(__('The ledger has been deleted.'));
        } 
		else
		{
            $this->Flash->error(__('The ledger could not be deleted. Please, try again.'));
        }

        return $this->redirect(['action' => 'index']);
    }
	
	public function trialBalance($id = null)
    {
		
		$this->viewBuilder()->layout('index_layout');
		$ledger    = $this->Ledgers->newEntity();
		$status=$this->request->query('status'); 
		if(!empty($status)){ 
			$this->viewBuilder()->layout('');	
		}else{ 
			$this->viewBuilder()->layout('index_layout');
		}
		$url=$this->request->here();
		$url=parse_url($url,PHP_URL_QUERY);
		$company_id=$this->Auth->User('session_company_id');
		
		$from_date = $this->request->query('from_date');
		 $to_date   = $this->request->query('to_date');
		//exit;
		if(!empty($from_date) || !empty($to_date))
		{
			$from_date = date("Y-m-d",strtotime($from_date));
			$to_date   = date("Y-m-d",strtotime($to_date));
		}
		else
		{ 
			 $from_date = date("Y-m-d",strtotime($this->coreVariable['fyValidFrom']));
			 $toDate    = $this->Ledgers->AccountingEntries->find()->order(['AccountingEntries.transaction_date'=>'DESC'])->First();
			@$to_date   = date("Y-m-d",strtotime($toDate->transaction_date));
		}
		
		$AccountingGroups=$this->Ledgers->AccountingEntries->Ledgers->AccountingGroups->find()->where(['AccountingGroups.nature_of_group_id IN'=>[1,2,3,4],'AccountingGroups.company_id'=>$company_id]);
		
		$Groups=[]; $ledgerData=[];
		foreach($AccountingGroups as $AccountingGroup){
			$Groups[$AccountingGroup->id]['ids'][]=$AccountingGroup->id;
			$Groups[$AccountingGroup->id]['name']=$AccountingGroup->name;
			$Groups[$AccountingGroup->id]['nature']=$AccountingGroup->nature_of_group_id;
			$accountingChildGroups = $this->Ledgers->AccountingEntries->Ledgers->AccountingGroups->find('children', ['for' => $AccountingGroup->id]);
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
		}
		$query=$this->Ledgers->AccountingEntries->find();
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
					@$ClosingBalanceForPrint[$primaryGroup]['balance']+=$balanceOfLedger->totalDebit-abs($balanceOfLedger->totalCredit);
				}else{
					@$ClosingBalanceForPrint[$primaryGroup]['balance']+=0;
				}
				@$ClosingBalanceForPrint[$primaryGroup]['name']=$Group['name'];
				@$ClosingBalanceForPrint[$primaryGroup]['nature']=$Group['nature'];
			}
		} 
		
		$query1=$this->Ledgers->AccountingEntries->find();
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

		$query2=$this->Ledgers->AccountingEntries->find();
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
					@$OpeningBalanceForPrint[$primaryGroup]['balance']+=0;
				}
			}
		} 
		//$closingValue= $this->StockValuationWithDate2($to_date);
		$openingValue= $this->StockValuationWithDate($from_date);
		$companies=$this->Ledgers->Companies->find()->contain(['States'])->where(['Companies.id'=>$company_id])->first();
		$this->set(compact('companies','ledger','from_date','to_date','TrialBalances','totalDebit','status','url','ClosingBalanceForPrint','OpeningBalanceForPrint','TransactionsCr','TransactionsDr','openingValue'));
        $this->set('_serialize', ['ledger']);
    }
	
	public function trialBalance1($id = null)
    {
		$this->viewBuilder()->layout('index_layout');
		$ledger    = $this->Ledgers->newEntity();
		$company_id=$this->Auth->User('session_company_id');
		
		$from_date = $this->request->query('from_date');
		$to_date   = $this->request->query('to_date');
		if(!empty($from_date) || !empty($to_date))
		{
			$from_date = date("Y-m-d",strtotime($from_date));
			$to_date   = date("Y-m-d",strtotime($to_date));
		}
		
		$query = $this->Ledgers->AccountingEntries->find();
			$CaseDebitOpeningBalance = $query->newExpr()
				->addCase(
					$query->newExpr()->add(['transaction_date <'=>$from_date]),
					$query->newExpr()->add(['debit']),
					'decimal'
				);
			$CaseCreditOpeningBalance = $query->newExpr()
				->addCase(
					$query->newExpr()->add(['transaction_date <'=>$from_date]),
					$query->newExpr()->add(['credit']),
					'decimal'
				);
			$CaseDebitTransaction = $query->newExpr()
				->addCase(
					$query->newExpr()->add(['transaction_date >='=>$from_date,'transaction_date <='=>$to_date]),
					$query->newExpr()->add(['debit']),
					'decimal'
				);
			$CaseCreditTransaction = $query->newExpr()
				->addCase(
					$query->newExpr()->add(['transaction_date >='=>$from_date,'transaction_date <='=>$to_date]),
					$query->newExpr()->add(['credit']),
					'decimal'
				);
			$query->select([
				'debit_opening_balance' => $query->func()->sum($CaseDebitOpeningBalance),
				'credit_opening_balance' => $query->func()->sum($CaseCreditOpeningBalance),
				'debit_transaction' => $query->func()->sum($CaseDebitTransaction),
				'credit_transaction' => $query->func()->sum($CaseCreditTransaction),'id','ledger_id'
			])
			->where(['AccountingEntries.company_id'=>$company_id])
			->group('ParentAccountingGroups.id')
			->autoFields(true)
			->contain(['Ledgers'=>['AccountingGroups'=> function($q){
								return $q->find('children', ['for' => 1])->find('threaded')->contain(['ParentAccountingGroups']);
							}]]);
			$TrialBalances = ($query);
			
		$this->set(compact('ledger','from_date','to_date','TrialBalances'));
        $this->set('_serialize', ['ledger']);
	}
	
	public function accountLedger($id = null)
    {
		$this->viewBuilder()->layout('index_layout');
			
		$status=$this->request->query('status'); 
		if(!empty($status)){ 
			$this->viewBuilder()->layout('');	
		}else{ 
			$this->viewBuilder()->layout('index_layout');
		}
		$accountLedger     = $this->Ledgers->newEntity();
	
		$url=$this->request->here();
		$url=parse_url($url,PHP_URL_QUERY);
		$company_id        = $this->Auth->User('session_company_id');
		$ledger_id         = $this->request->query('ledger_id');
		$from_date         = $this->request->query('from_date');
		$to_date           = $this->request->query('to_date');
		$where =[];
		if(!empty($from_date))
		{
			$from_date = date("Y-m-d",strtotime($from_date));
			$where['AccountingEntries.transaction_date >=']=$from_date;
		}
		if(!empty($to_date))
		{
			$to_date   = date("Y-m-d",strtotime($to_date));
			$where['AccountingEntries.transaction_date <=']=$to_date;
		}
		if(!empty($ledger_id))
		{
			$where['AccountingEntries.ledger_id']=$ledger_id;
		}

		if(!empty($ledger_id)){
		$query = $this->Ledgers->AccountingEntries->find();
		$CaseCreditOpeningBalance = $query->newExpr()
					->addCase(
						$query->newExpr()->add(['ledger_id']),
						$query->newExpr()->add(['credit']),
						'decimal'
					);
		$CaseDebitOpeningBalance = $query->newExpr()
					->addCase(
						$query->newExpr()->add(['ledger_id']),
						$query->newExpr()->add(['debit']),
						'decimal'
					);
		$query->select([
				'debit_opening_balance' => $query->func()->sum($CaseDebitOpeningBalance),
				'credit_opening_balance' => $query->func()->sum($CaseCreditOpeningBalance),
				'id','ledger_id'
			])
			->where(['AccountingEntries.company_id'=>$company_id,'AccountingEntries.transaction_date <'=>$from_date,'AccountingEntries.ledger_id'=>$ledger_id])
			->group('ledger_id')
			->autoFields(true);
		$AccountLedgersOpeningBalance=($query);
		$total_debit=0;
		$total_credit=0;
		foreach($AccountLedgersOpeningBalance as $AccountLedgersOpeningBalance){
			$total_debit=$AccountLedgersOpeningBalance->debit_opening_balance;
			$total_credit=$AccountLedgersOpeningBalance->credit_opening_balance;
		}
		@$opening_balance=$total_debit-$total_credit;
			if($opening_balance>0){
			@$opening_balance_type='Dr';	
			}
			else if($opening_balance<0){
			$opening_balance=abs($opening_balance);
			@$opening_balance_type='Cr';	
			}
			else{
			@$opening_balance_type='';	
			}
		$opening_balance=round($opening_balance,2);
		 $AccountingLedgers=$this->Ledgers->AccountingEntries->find()->select(['total_credit_sum'=>'SUM(AccountingEntries.credit)','total_debit_sum'=>'SUM(AccountingEntries.debit)'])->where(['AccountingEntries.company_id'=>$company_id])->contain(['Ledgers','PurchaseVouchers','SalesInvoices','SaleReturns','Payments','SalesVouchers','Receipts','JournalVouchers','ContraVouchers','CreditNotes','DebitNotes','JournalVouchers','PurchaseInvoices','PurchaseReturns'])->where($where)->group(['AccountingEntries.ledger_id','AccountingEntries.purchase_voucher_id','AccountingEntries.sales_invoice_id','AccountingEntries.sale_return_id','AccountingEntries.purchase_invoice_id','AccountingEntries.purchase_return_id','AccountingEntries.receipt_id','AccountingEntries.payment_id','AccountingEntries.credit_note_id','AccountingEntries.debit_note_id','AccountingEntries.sales_voucher_id','AccountingEntries.journal_voucher_id','AccountingEntries.contra_voucher_id'])
		->autoFields(true); 
		}
		/*$AccountingLedgers=$this->Ledgers->AccountingEntries->find()->where(['AccountingEntries.company_id'=>$company_id])->contain(['Ledgers','PurchaseVouchers','SalesInvoices','SaleReturns','Payments','SalesVouchers','Receipts','JournalVouchers','ContraVouchers','CreditNotes','DebitNotes','JournalVouchers','PurchaseInvoices','PurchaseReturns'])->where($where)
		->autoFields(true);
		} */
		//pr($AccountingLedgers->toArray());exit;
		$ledgers = $this->Ledgers->find('list')->where(['company_id'=>$company_id]);
		$companies=$this->Ledgers->Companies->find()->contain(['States'])->where(['Companies.id'=>$company_id])->first();
		$this->set(compact('companies','accountLedger','ledgers','opening_balance_type','opening_balance','openingBalance_credit1','closingBalance_credit1','AccountingLedgers','from_date','to_date','voucher_type','voucher_no','ledger_id','url','status'));
        $this->set('_serialize', ['ledger']);
    }
	public function dayBook($id = null)
    {
		$company_id=$this->Auth->User('session_company_id');
		$this->viewBuilder()->layout('index_layout');
		$status=$this->request->query('status'); 
		if(!empty($status)){ 
			$this->viewBuilder()->layout('');	
		}else{ 
			$this->viewBuilder()->layout('index_layout');
		}
		$url=$this->request->here();
		$url=parse_url($url,PHP_URL_QUERY);
		$currentDate=date('Y-m-d');
		$from_date         = $this->request->query('from_date');
		$to_date           = $this->request->query('to_date');
		$where =[];
		if(!empty($from_date))
		{
			$from_date = date("Y-m-d",strtotime($from_date));
			$where['AccountingEntries.transaction_date >=']=$from_date;
		}
		if(!empty($to_date))
		{
			$to_date   = date("Y-m-d",strtotime($to_date));
			$where['AccountingEntries.transaction_date <=']=$to_date;
		}
		//$day_book=[];
		//salesIncoice
		@$salesInvoiceLedgers=$this->Ledgers->AccountingEntries->find()
		->where(['AccountingEntries.company_id'=>$company_id,'AccountingEntries.transaction_date >='=>$from_date, 'AccountingEntries.transaction_date <='=>$to_date,'AccountingEntries.sales_invoice_id >'=>0,'AccountingEntries.debit >'=>0])->contain(['Ledgers','SalesInvoices'])
		->group('AccountingEntries.sales_invoice_id')
		->autoFields(true);
		
		foreach($salesInvoiceLedgers->toArray() as $data)
		{
		$data->voucher_id=$data->sales_invoice_id;
		$data->voucher_no=$data->sales_invoice->voucher_no;
		$data->voucher_type='Sales Invoices';
		$data->hlink='SalesInvoices';
		$data->haction='Edit';
		}
		//purchaseInvoice
		@$purchaseInvoiceLedgers=$this->Ledgers->AccountingEntries->find()
		->where(['AccountingEntries.company_id'=>$company_id,'AccountingEntries.transaction_date >='=>$from_date, 'AccountingEntries.transaction_date <='=>$to_date,'AccountingEntries.purchase_invoice_id >'=>0,'AccountingEntries.credit >'=>0])->contain(['Ledgers','PurchaseInvoices'])
		->group('AccountingEntries.purchase_invoice_id')
		->autoFields(true);
		foreach($purchaseInvoiceLedgers->toArray() as $data)
		{
		$data->voucher_id=$data->purchase_invoice_id;
		$data->voucher_no=$data->purchase_invoice->voucher_no;
		$data->voucher_type='Purchase Invoices';
		$data->hlink='PurchaseInvoices';
		$data->haction='Edit';
		}
		//payment
		@$paymentLedgers=$this->Ledgers->AccountingEntries->find()
		->where(['AccountingEntries.company_id'=>$company_id,'AccountingEntries.transaction_date >='=>$from_date, 'AccountingEntries.transaction_date <='=>$to_date,'AccountingEntries.payment_id >'=>0,'AccountingEntries.debit >'=>0])->contain(['Ledgers','Payments'])
		->group('AccountingEntries.payment_id')
		->autoFields(true);
		
		foreach($paymentLedgers->toArray() as $data)
		{
		$data->voucher_id=$data->payment_id;
		$data->voucher_no=$data->payment->voucher_no;
		$data->voucher_type='Payment';
		$data->hlink='Payments';
		$data->haction='Edit';
		}
		//receipt
		@$receiptLedgers=$this->Ledgers->AccountingEntries->find()
		->where(['AccountingEntries.company_id'=>$company_id,'AccountingEntries.transaction_date >='=>$from_date, 'AccountingEntries.transaction_date <='=>$to_date,'AccountingEntries.receipt_id >'=>0,'AccountingEntries.credit >'=>0])->contain(['Ledgers','Receipts'])
		->group('AccountingEntries.receipt_id')
		->autoFields(true);
		foreach($receiptLedgers->toArray() as $data)
		{
		$data->voucher_id=$data->receipt_id;
		$data->voucher_no=$data->receipt->voucher_no;
		$data->voucher_type='Receipt';
		$data->hlink='Receipts';
		$data->haction='Edit';
		}
		//creditnote
		@$creditNoteLedgers=$this->Ledgers->AccountingEntries->find()
		->where(['AccountingEntries.company_id'=>$company_id,'AccountingEntries.transaction_date >='=>$from_date, 'AccountingEntries.transaction_date <='=>$to_date,'AccountingEntries.credit_note_id >'=>0,'AccountingEntries.credit >'=>0])->contain(['Ledgers','CreditNotes'])
		->group('AccountingEntries.credit_note_id')
		->autoFields(true);
		foreach($creditNoteLedgers->toArray() as $data)
		{
		$data->voucher_id=$data->credit_note_id;
		$data->voucher_no=$data->credit_note->voucher_no;
		$data->voucher_type='Credit Note';
		$data->hlink='CreditNotes';
		$data->haction='Edit';
		}
		//debitnote
		@$debitNoteLedgers=$this->Ledgers->AccountingEntries->find()
		->where(['AccountingEntries.company_id'=>$company_id,'AccountingEntries.transaction_date >='=>$from_date, 'AccountingEntries.transaction_date <='=>$to_date,'AccountingEntries.debit_note_id >'=>0,'AccountingEntries.debit >'=>0])->contain(['Ledgers','DebitNotes'])
		->group('AccountingEntries.debit_note_id')
		->autoFields(true);
		foreach($debitNoteLedgers->toArray() as $data)
		{
		$data->voucher_id=$data->debit_note_id;
		$data->voucher_no=$data->debit_note->voucher_no;
		$data->voucher_type='Debit Note';
		$data->hlink='DebitNotes';
		$data->haction='Edit';
		}
		
		//journalvoucher
		@$journalVoucherLedgers=$this->Ledgers->AccountingEntries->find()
		->where(['AccountingEntries.company_id'=>$company_id,'AccountingEntries.transaction_date >='=>$from_date, 'AccountingEntries.transaction_date <='=>$to_date,'AccountingEntries.journal_voucher_id >'=>0])->contain(['Ledgers','JournalVouchers'])
		->group('AccountingEntries.journal_voucher_id')
		->autoFields(true);
		foreach($journalVoucherLedgers->toArray() as $data)
		{
		$data->voucher_id=$data->journal_voucher_id;
		$data->voucher_no=$data->journal_voucher->voucher_no;
		$data->voucher_type='Journal Voucher';
		$data->hlink='JournalVouchers';
		$data->haction='Edit';
		}
		
		//salesVoucher
		@$salesVoucherLedgers=$this->Ledgers->AccountingEntries->find()
		->where(['AccountingEntries.company_id'=>$company_id,'AccountingEntries.transaction_date >='=>$from_date, 'AccountingEntries.transaction_date <='=>$to_date,'AccountingEntries.sales_voucher_id >'=>0,'AccountingEntries.debit >'=>0])->contain(['Ledgers','SalesVouchers'])
		->group('AccountingEntries.sales_voucher_id')
		->autoFields(true);
		foreach($salesVoucherLedgers->toArray() as $data)
		{
		$data->voucher_id=$data->sales_voucher_id;
		$data->voucher_no=$data->sales_voucher->voucher_no;
		$data->voucher_type='Sales Voucher';
		$data->hlink='SalesVouchers';
		$data->haction='Edit';
		}
		//purchaseVoucher
		@$purchaseVoucherLedgers=$this->Ledgers->AccountingEntries->find()
		->where(['AccountingEntries.company_id'=>$company_id,'AccountingEntries.transaction_date >='=>$from_date, 'AccountingEntries.transaction_date <='=>$to_date,'AccountingEntries.purchase_voucher_id >'=>0,'AccountingEntries.credit >'=>0])->contain(['Ledgers','PurchaseVouchers'])
		->group('AccountingEntries.purchase_voucher_id')
		->autoFields(true);
		foreach($purchaseVoucherLedgers->toArray() as $data)
		{
		$data->voucher_id=$data->purchase_voucher_id;
		$data->voucher_no=$data->purchase_voucher->voucher_no;
		$data->voucher_type='Purchase Voucher';
		$data->hlink='PurchaseVouchers';
		$data->haction='Edit';
		}
		
		//salesreturn
		@$saleReturnLedgers=$this->Ledgers->AccountingEntries->find()
		->where(['AccountingEntries.company_id'=>$company_id,'AccountingEntries.transaction_date >='=>$from_date, 'AccountingEntries.transaction_date <='=>$to_date,'AccountingEntries.sale_return_id >'=>0,'AccountingEntries.credit >'=>0])->contain(['Ledgers','SaleReturns'])
		->group('AccountingEntries.sale_return_id')
		->autoFields(true);
		foreach($saleReturnLedgers->toArray() as $data)
		{
		$data->voucher_id=$data->sale_return_id;
		$data->voucher_no=$data->sale_return->voucher_no;
		$data->voucher_type='Sales Returns';
		$data->hlink='SaleReturns';
		$data->haction='View';
		}
		
		//purchasereturn
		@$purchaseReturnLedgers=$this->Ledgers->AccountingEntries->find()
		->where(['AccountingEntries.company_id'=>$company_id,'AccountingEntries.transaction_date >='=>$from_date, 'AccountingEntries.transaction_date <='=>$to_date,'AccountingEntries.purchase_return_id >'=>0,'AccountingEntries.debit >'=>0])->contain(['Ledgers','PurchaseReturns'])
		->group('AccountingEntries.purchase_return_id')
		->autoFields(true);
		foreach($purchaseReturnLedgers->toArray() as $data)
		{
		$data->voucher_id=$data->purchase_return_id;
		$data->voucher_no=$data->purchase_return->voucher_no;
		$data->voucher_type='Purchase Returns';
		$data->hlink='PurchaseReturns';
		$data->haction='View';
		}
		//contra voucher
		@$contraLedgers=$this->Ledgers->AccountingEntries->find()
		->where(['AccountingEntries.company_id'=>$company_id,'AccountingEntries.transaction_date >='=>$from_date, 'AccountingEntries.transaction_date <='=>$to_date,'AccountingEntries.contra_voucher_id >'=>0,'AccountingEntries.debit >'=>0])->contain(['Ledgers','ContraVouchers'])
		->group('AccountingEntries.contra_voucher_id')
		->autoFields(true);
		foreach($contraLedgers->toArray() as $data)
		{
		$data->voucher_id=$data->contra_voucher_id;
		$data->voucher_no=$data->contra_voucher->voucher_no;
		$data->voucher_type='Contra Voucher';
		$data->hlink='ContraVouchers';
		$data->haction='Edit';
		}
		$i=0;
		$ledger_data=[];
		foreach($salesInvoiceLedgers as $salesInvoiceLedger)
		{
			$data_date=strtotime($salesInvoiceLedger->transaction_date);
			$ledger_data[$data_date][$i]=$salesInvoiceLedger;
			$i++;
		}
		foreach($purchaseInvoiceLedgers as $purchaseInvoiceLedger)
		{
			$data_date=strtotime($purchaseInvoiceLedger->transaction_date);
			$ledger_data[$data_date][$i]=$purchaseInvoiceLedger;
			$i++;
		}
		foreach($paymentLedgers as $paymentLedger)
		{
			$data_date=strtotime($paymentLedger->transaction_date);
			$ledger_data[$data_date][$i]=$paymentLedger;
			$i++;
		}
		foreach($receiptLedgers as $receiptLedger)
		{
			$data_date=strtotime($receiptLedger->transaction_date);
			$ledger_data[$data_date][$i]=$receiptLedger;
			$i++;
		}
		foreach($creditNoteLedgers as $creditNoteLedger)
		{
			$data_date=strtotime($creditNoteLedger->transaction_date);
			$ledger_data[$data_date][$i]=$creditNoteLedger;
			$i++;
		}
		foreach($debitNoteLedgers as $debitNoteLedger)
		{
			$data_date=strtotime($debitNoteLedger->transaction_date);
			$ledger_data[$data_date][$i]=$debitNoteLedger;
			$i++;
		}
		foreach($journalVoucherLedgers as $journalVoucherLedger)
		{
			$data_date=strtotime($journalVoucherLedger->transaction_date);
			$ledger_data[$data_date][$i]=$journalVoucherLedger;
			$i++;
		}
		foreach($saleReturnLedgers as $saleReturnLedger)
		{
			$data_date=strtotime($saleReturnLedger->transaction_date);
			$ledger_data[$data_date][$i]=$saleReturnLedger;
			$i++;
		}
		foreach($salesVoucherLedgers as $salesVoucherLedger)
		{
			$data_date=strtotime($salesVoucherLedger->transaction_date);
			$ledger_data[$data_date][$i]=$salesVoucherLedger;
			$i++;
		}
		foreach($purchaseVoucherLedgers as $purchaseVoucherLedger)
		{
			$data_date=strtotime($purchaseVoucherLedger->transaction_date);
			$ledger_data[$data_date][$i]=$purchaseVoucherLedger;
			$i++;
		}
		foreach($purchaseReturnLedgers as $purchaseReturnLedger)
		{
			$data_date=strtotime($purchaseReturnLedger->transaction_date);
			$ledger_data[$data_date][$i]=$purchaseReturnLedger;
			$i++;
		}
		foreach($contraLedgers as $contraLedger)
		{
			$data_date=strtotime($contraLedger->transaction_date);
			$ledger_data[$data_date][$i]=$contraLedger;
			$i++;
		}
		ksort($ledger_data);
		/*$day_book=array_merge([$salesInvoiceLedgers->toArray(),$purchaseInvoiceLedgers->toArray(),$paymentLedgers->toArray(),$receiptLedgers->toArray(),$creditNoteLedgers->toArray(),$debitNoteLedgers->toArray(),$journalVoucherLedgers->toArray(),$saleReturnLedgers->toArray(),$salesVoucherLedgers->toArray(),$purchaseVoucherLedgers->toArray(),$purchaseReturnLedgers->toArray(),$contraLedgers->toArray()]);*/
		//pr($ledger_data);exit;
		
		$companies=$this->Ledgers->Companies->find()->contain(['States'])->where(['Companies.id'=>$company_id])->first();
		$this->set(compact('companies','ledger_data','from_date','to_date','url','status'));
        $this->set('_serialize', ['day_book']);
    }
	
	
	public function overDueReport()
	{
		$company_id=$this->Auth->User('session_company_id');
		$this->viewBuilder()->layout('index_layout');
		$status=$this->request->query('status'); 
		if(!empty($status)){ 
			$this->viewBuilder()->layout('');	
		}else{ 
			$this->viewBuilder()->layout('index_layout');
		}
		$url=$this->request->here();
		$url=parse_url($url,PHP_URL_QUERY);
		$run_time_date = $this->request->query('run_time_date');
		
		if(!empty($run_time_date)) {
		$run_time_date = date("Y-m-d",strtotime($run_time_date)); }
	
		$parentSundryDebtors = $this->Ledgers->AccountingGroups->find()
				->where(['AccountingGroups.company_id'=>$company_id, 'AccountingGroups.customer'=>'1'])->orWhere(['AccountingGroups.company_id'=>$company_id, 'AccountingGroups.supplier'=>'1']);
		
		$childSundryDebtors=[];
		
		foreach($parentSundryDebtors as $parentSundryDebtor)
		{
			$accountingGroups = $this->Ledgers->AccountingGroups->find('children', ['for' => $parentSundryDebtor->id]);
			$childSundryDebtors[]=$parentSundryDebtor->id;
			foreach($accountingGroups as $accountingGroup){
				$childSundryDebtors[]=$accountingGroup->id;
			}			
		}
		
		$ledgerAccounts = $this->Ledgers->find()->where(['accounting_group_id IN'=>$childSundryDebtors]);
		
		$ledgerAccountids = [];

		foreach($ledgerAccounts as $ledgerAccount)
		{
			$ledgerAccountids[]=$ledgerAccount->id;
		}
		$reference_details = $this->Ledgers->ReferenceDetails->find()->contain(['Ledgers'])->where(['ReferenceDetails.company_id'=>$company_id, 'ReferenceDetails.type != ' => 'On Account']);
		$reference_details->select(['total_debit' => $reference_details->func()->sum('ReferenceDetails.debit'),'total_credit' => $reference_details->func()->sum('ReferenceDetails.credit')])
		->where(['ReferenceDetails.ledger_id IN '=> $ledgerAccountids,'ReferenceDetails.transaction_date <=' => $run_time_date])
		->group(['ReferenceDetails.ref_name','ReferenceDetails.ledger_id'])
		
		->autoFields(true);		 
		$companies=$this->Ledgers->ReferenceDetails->Companies->find()->contain(['States'])->where(['Companies.id'=>$company_id])->first();
		//pr($reference_details->toArray()); exit;
		$this->set(compact('companies','reference_details','run_time_date','url','status'));
        $this->set('_serialize', ['reference_details']);
		
	/* 	$reference_details = $this->Ledgers->ReferenceDetails->find()
		->contain(['SalesInvoices'])
		->where(['ReferenceDetails.ledger_id IN'=>$ledgerAccountids]); */
		
		/*
		$reference_details=$reference_details->leftJoinWith('SalesInvoices', function ($q) use($run_time_date){
		return $q->orWhere(['SalesInvoices.transaction_date <=' => $run_time_date]);
		});
		
		$reference_details->leftJoinWith('Receipts', function ($q) use($run_time_date){
		return $q->orWhere(['Receipts.transaction_date <=' => $run_time_date]);
		});
		
		$reference_details->leftJoinWith('Payments', function ($q) use($run_time_date){
		return $q->orWhere(['Payments.transaction_date <=' => $run_time_date]);
		});
		*/
		
		
	}
	
	
	public function overDueReportPayable()
	{
		$company_id=$this->Auth->User('session_company_id');
		$this->viewBuilder()->layout('index_layout');
		$run_time_date = $this->request->query('run_time_date');
		$status=$this->request->query('status'); 
		if(!empty($status)){ 
			$this->viewBuilder()->layout('');	
		}else{ 
			$this->viewBuilder()->layout('index_layout');
		}
		$url=$this->request->here();
		$url=parse_url($url,PHP_URL_QUERY);
		if(!empty($run_time_date)) {
		$run_time_date = date("Y-m-d",strtotime($run_time_date)); }
	
		$parentSundryCreditors = $this->Ledgers->AccountingGroups->find()
				->where(['AccountingGroups.company_id'=>$company_id, 'AccountingGroups.customer'=>'1'])->orWhere(['AccountingGroups.company_id'=>$company_id, 'AccountingGroups.supplier'=>'1']);
	
		$childSundryCreditors=[];
		
		foreach($parentSundryCreditors as $parentSundryCreditor)
		{
			$accountingGroups = $this->Ledgers->AccountingGroups->find('children', ['for' => $parentSundryCreditor->id]);
			$childSundryCreditors[]=$parentSundryCreditor->id;
			foreach($accountingGroups as $accountingGroup){
				$childSundryCreditors[]=$accountingGroup->id;
			}			
		}
		
		$ledgerAccounts = $this->Ledgers->find()->where(['accounting_group_id IN'=>$childSundryCreditors]);
		
		$ledgerAccountids = [];

		foreach($ledgerAccounts as $ledgerAccount)
		{
			$ledgerAccountids[]=$ledgerAccount->id;
		} 
		$reference_details = $this->Ledgers->ReferenceDetails->find()->contain(['Ledgers'])->where(['ReferenceDetails.company_id'=>$company_id, 'ReferenceDetails.type != ' => 'On Account']);
		
		$reference_details->select(['total_debit' => $reference_details->func()->sum('ReferenceDetails.debit'),'total_credit' => $reference_details->func()->sum('ReferenceDetails.credit')])
		->where(['ReferenceDetails.ledger_id IN '=> $ledgerAccountids,'ReferenceDetails.transaction_date <=' => $run_time_date])
		->group(['ReferenceDetails.ref_name','ReferenceDetails.ledger_id'])
		->autoFields(true);		 
		$companies=$this->Ledgers->ReferenceDetails->Companies->find()->contain(['States'])->where(['Companies.id'=>$company_id])->first();
		
		$this->set(compact('reference_details','run_time_date','url','status','companies'));
        $this->set('_serialize', ['reference_details']);
		
	}
	
	
	
	
	
}
