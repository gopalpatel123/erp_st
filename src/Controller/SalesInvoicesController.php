<?php
namespace App\Controller;

use App\Controller\AppController;
use Cake\View\Helper\NumberHelper;
ini_set('memory_limit', '-1');
/**
 * SalesInvoices Controller
 *
 * @property \App\Model\Table\SalesInvoicesTable $SalesInvoices
 *
 * @method \App\Model\Entity\SalesInvoice[] paginate($object = null, array $settings = [])
 */
class SalesInvoicesController extends AppController
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
		$voucher_no=$this->request->query('voucher_no');
		$From=$this->request->query('From');
		$To=$this->request->query('To');
			
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
			$where1['SalesInvoiceRows.item_id']=$item_id;
		}
		if(!empty($voucher_no))
			{
				$where['SalesInvoices.voucher_no']=$voucher_no;
			}
			
		
		if(!empty($From))
			{
				$From=date("Y-m-d",strtotime($From));
				//$where(['SalesInvoices.transaction_date >='=>$Form]);
				$where['SalesInvoices.transaction_date >='] = $From;				
			}
			
		if(!empty($To))
			{ 
				$To=date("Y-m-d",strtotime($To));
				$where['SalesInvoices.transaction_date <='] = $To;
				 
			}
			//pr($where); exit;
			//pr($SalesInvoices);exit;	
			
		
		$where['SalesInvoices.company_id']=$company_id;
		$where['SalesInvoices.financial_year_id']=$financialYear_id;
		
		
		$salesInvoices = $this->paginate($this->SalesInvoices->find()
		->where($where)
		->contain(['SalesInvoiceRows'])
		->matching(
				'SalesInvoiceRows.Items', function ($q) use($where1) {
					return $q->where($where1);
				})
		->where([
		'OR' => [
            'SalesInvoices.voucher_no' => $search,
            // ...
            'PartyLedgers.name LIKE' => '%'.$search.'%',
			//.....
			'SalesLedgers.name LIKE' => '%'.$search.'%',
			//...
			'SalesInvoices.transaction_date ' => date('Y-m-d',strtotime($search)),
			//...
			'SalesInvoices.amount_after_tax' => $search
        ]])->group(['SalesInvoices.id'])->order(['voucher_no' => 'DESC'])); 
		$stockItems=$this->SalesInvoices->SalesInvoiceRows->Items->find('list')->where(['Items.company_id'=>$company_id]);
		$this->set(compact('salesInvoices','search','status','location_id','stockItems','item_id','voucher_no'));
        $this->set('_serialize', ['salesInvoices']);
    } 

	public function invoiceReport($status = Null)
    {
		$status=$this->request->query('status');
		$url=$this->request->here();
		$url=parse_url($url,PHP_URL_QUERY);
		//$this->viewBuilder()->layout('index_layout');
		if(!empty($status)){ 
			$this->viewBuilder()->layout('excel_layout');	
		}else{ 
			$this->viewBuilder()->layout('index_layout');
		}
		$company_id=$this->Auth->User('session_company_id');
        $location_id=$this->Auth->User('session_location_id');
		$financialYear_id=$this->Auth->User('financialYear_id');
		$from=$this->request->query('from_date');
		$to=$this->request->query('to_date');
		$From=date('Y-m-d',strtotime($from));
		$To=date('Y-m-d',strtotime($to));
		//pr($To); exit;
		$search=$this->request->query('search');
		$salesInvoices = $this->SalesInvoices->find()->where(['SalesInvoices.company_id'=>$company_id,'SalesInvoices.financial_year_id'=>$financialYear_id,'transaction_date >='=>$From,'transaction_date <='=>$To]);
		//pr($salesInvoices->toArray()); exit;
		$companies=$this->SalesInvoices->Companies->find()->contain(['States'])->where(['Companies.id'=>$company_id])->first();
        $this->set(compact('salesInvoices','url','status','companies'));
        $this->set('_serialize', ['salesInvoices']);
    }
	
	public function reportFilter()
    {
		$this->viewBuilder()->layout('index_layout');
		$company_id=$this->Auth->User('session_company_id');
		
		@$partyParentGroups = $this->SalesInvoices->SalesInvoiceRows->Ledgers->AccountingGroups->find()
						->where(['AccountingGroups.company_id'=>$company_id, 'AccountingGroups.sale_invoice_party'=>'1']);
		$partyGroups=[];
		
		foreach($partyParentGroups as $partyParentGroup)
		{
			$accountingGroups = $this->SalesInvoices->SalesInvoiceRows->Ledgers->AccountingGroups
			->find('children', ['for' => $partyParentGroup->id])->toArray();
			$partyGroups[]=$partyParentGroup->id;
			foreach($accountingGroups as $accountingGroup){
				$partyGroups[]=$accountingGroup->id;
			}
		}
	
		if($partyGroups)
		{  
			$Partyledgers = $this->SalesInvoices->SalesInvoiceRows->Ledgers->find()
							->where(['Ledgers.accounting_group_id IN' =>$partyGroups,'Ledgers.company_id'=>$company_id])
							->contain(['Customers']);
        }
		
		
		$partyOptions=[];
		foreach($Partyledgers as $Partyledger){
		
		$receiptAccountLedgers = $this->SalesInvoices->SalesInvoiceRows->Ledgers->AccountingGroups->find()
		->where(['AccountingGroups.id'=>$Partyledger->accounting_group_id,'AccountingGroups.customer'=>1])
		->orWhere(['AccountingGroups.id'=>$Partyledger->accounting_group_id,'AccountingGroups.supplier'=>1])->first();
		
		if($receiptAccountLedgers)
		{
			$receiptAccountLedgersName='1';
		}
		else{
			$receiptAccountLedgersName='0';
		}
			$partyOptions[]=['text' =>str_pad(@$Partyledger->customer->customer_id, 4, '0', STR_PAD_LEFT).' - '.$Partyledger->name, 'value' => $Partyledger->id ,'party_state_id'=>@$Partyledger->customer->state_id, 'partyexist'=>$receiptAccountLedgersName, 'billToBillAccounting'=>$Partyledger->bill_to_bill_accounting];
		}
		
		$StockGroups = $this->SalesInvoices->SalesInvoiceRows->Items->StockGroups->find('list')
							->where(['StockGroups.parent_id IS NOT NULL','StockGroups.company_id'=>$company_id]);
		
		$this->set(compact('partyOptions','StockGroups'));
    }
	
	public function dayBookFilter()
    {
		$this->viewBuilder()->layout('index_layout');
		$company_id=$this->Auth->User('session_company_id');
		
		@$partyParentGroups = $this->SalesInvoices->SalesInvoiceRows->Ledgers->AccountingGroups->find()
						->where(['AccountingGroups.company_id'=>$company_id, 'AccountingGroups.sale_invoice_party'=>'1']);
		$partyGroups=[];
		
		foreach($partyParentGroups as $partyParentGroup)
		{
			$accountingGroups = $this->SalesInvoices->SalesInvoiceRows->Ledgers->AccountingGroups
			->find('children', ['for' => $partyParentGroup->id])->toArray();
			$partyGroups[]=$partyParentGroup->id;
			foreach($accountingGroups as $accountingGroup){
				$partyGroups[]=$accountingGroup->id;
			}
		}
	
		if($partyGroups)
		{  
			$Partyledgers = $this->SalesInvoices->SalesInvoiceRows->Ledgers->find()
							->where(['Ledgers.accounting_group_id IN' =>$partyGroups,'Ledgers.company_id'=>$company_id])
							->contain(['Customers']);
        }
		
		
		$partyOptions=[];
		foreach($Partyledgers as $Partyledger){
		
		$receiptAccountLedgers = $this->SalesInvoices->SalesInvoiceRows->Ledgers->AccountingGroups->find()
		->where(['AccountingGroups.id'=>$Partyledger->accounting_group_id,'AccountingGroups.customer'=>1])
		->orWhere(['AccountingGroups.id'=>$Partyledger->accounting_group_id,'AccountingGroups.supplier'=>1])->first();
		
		if($receiptAccountLedgers)
		{
			$receiptAccountLedgersName='1';
		}
		else{
			$receiptAccountLedgersName='0';
		}
			$partyOptions[]=['text' =>str_pad(@$Partyledger->customer->customer_id, 4, '0', STR_PAD_LEFT).' - '.$Partyledger->name, 'value' => $Partyledger->id ,'party_state_id'=>@$Partyledger->customer->state_id, 'partyexist'=>$receiptAccountLedgersName, 'billToBillAccounting'=>$Partyledger->bill_to_bill_accounting];
		}
		
		$StockGroups = $this->SalesInvoices->SalesInvoiceRows->Items->StockGroups->find('list')
							->where(['StockGroups.parent_id IS NOT NULL','StockGroups.company_id'=>$company_id]);
		
		$this->set(compact('partyOptions','StockGroups'));
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
		$where['SalesInvoices.transaction_date >=']= $from_date;
		}
		if(!empty($to)){
			$to_date=date('Y-m-d', strtotime($to));
			$where['SalesInvoices.transaction_date <='] = $to_date;
		}
		$party_ids=$this->request->query('party_ledger_id');
		if(!empty($party_ids)){
		$where['SalesInvoices.party_ledger_id IN'] = $party_ids;
		}
		
		$stock_group_id=$this->request->query('Stock_group_id');
		if(!empty($stock_group_id)){
		//$where['SalesInvoices.SalesInvoiceRows.Items.stock_group_id'] = $stock_group_id;
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
		$where1['SalesInvoices.voucher_no IN'] = $invoice_ids;
		$where1['SalesInvoices.company_id'] = $company_id;
		}
		if(!empty($where)){
			if($stock_group_id){
					$salesInvoices = $this->SalesInvoices->find()->where(['SalesInvoices.company_id'=>$company_id])->where($where)->orWhere($where1)
					->contain(['Companies', 'PartyLedgers'=>['Customers'], 'SalesLedgers', 'SalesInvoiceRows'=>['GstFigures','Items'=>function($e) use($stock_group_id){
							return $e->where(['Items.stock_group_id'=>$stock_group_id])
							->contain(['StockGroups'=>['ParentStockGroups'],'Sizes']);
							}]])
					->order(['voucher_no' => 'ASC']);
				}else{
					$salesInvoices = $this->SalesInvoices->find()->where(['SalesInvoices.company_id'=>$company_id])->where($where)->orWhere($where1)
					->contain(['Companies', 'PartyLedgers'=>['Customers'], 'SalesLedgers', 'SalesInvoiceRows'=>['GstFigures','Items'=>['StockGroups'=>['ParentStockGroups'],'Sizes']]])
					->order(['voucher_no' => 'ASC']);
				}
			}else{
			if($stock_group_id){
					$salesInvoices = $this->SalesInvoices->find()->where(['SalesInvoices.company_id'=>$company_id])->where($where1)
					->contain(['Companies', 'PartyLedgers'=>['Customers'], 'SalesLedgers', 'SalesInvoiceRows'=>
					['GstFigures','Items'=>function($e) use($stock_group_id){
							return $e->where(['Items.stock_group_id'=>$stock_group_id])
							->contain(['StockGroups'=>['ParentStockGroups'],'Sizes']);
							}]])
					->order(['voucher_no' => 'ASC']);
			}else{
					$salesInvoices = $this->SalesInvoices->find()->where(['SalesInvoices.company_id'=>$company_id])->where($where1)
					->contain(['Companies', 'PartyLedgers'=>['Customers'], 'SalesLedgers', 'SalesInvoiceRows'=>
					['GstFigures','Items'=>['StockGroups'=>['ParentStockGroups'],'Sizes']]])
					->order(['voucher_no' => 'ASC']);
			}
		
		}
		
		
		//pr($salesInvoices->toArray()); exit;
		$i=0; 
		foreach($salesInvoices as $salesInvoice)
		{ 
			$data_date=strtotime($salesInvoice->transaction_date);
			$SalesInvoices[$data_date][$i]=$salesInvoice;
			$i++;
		}
		ksort($SalesInvoices);
		//pr($SalesInvoices);
		//exit;
		
		$companies=$this->SalesInvoices->Companies->find()->contain(['States'])->where(['Companies.id'=>$company_id])->first();
		$this->set(compact('companies','SalesInvoices', 'from', 'to','party_ids','invoice_no','url','status'));
        $this->set('_serialize', ['salesInvoices']);
    }
	
		
	public function dayBook($id=null)
    {
		$status=$this->request->query('status'); 
		
		$this->viewBuilder()->layout('');

		
		$company_id=$this->Auth->User('session_company_id');
		$url=$this->request->here();
		$url=parse_url($url,PHP_URL_QUERY);
	    $from=$this->request->query('from_date');
		$to=$this->request->query('to_date');
		
		$where=[];
		$where1=[];
		if(!empty($from)){ 
			$from_date=date('Y-m-d', strtotime($from));
		$where['SalesInvoices.transaction_date >=']= $from_date;
		}
		if(!empty($to)){
			$to_date=date('Y-m-d', strtotime($to));
			$where['SalesInvoices.transaction_date <='] = $to_date;
		}
		$party_ids=$this->request->query('party_ledger_id');
		if(!empty($party_ids)){
		$where['SalesInvoices.party_ledger_id IN'] = $party_ids;
		}
		
		$stock_group_id=$this->request->query('Stock_group_id');
		if(!empty($stock_group_id)){
		//$where['SalesInvoices.SalesInvoiceRows.Items.stock_group_id'] = $stock_group_id;
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
		$where1['SalesInvoices.voucher_no IN'] = $invoice_ids;
		$where1['SalesInvoices.company_id'] = $company_id;
		}
		if(!empty($where)){
			if($stock_group_id){
					$salesInvoices = $this->SalesInvoices->find()->where(['SalesInvoices.company_id'=>$company_id])->where($where)->orWhere($where1)
					->contain(['Companies', 'PartyLedgers'=>['Customers'], 'SalesLedgers', 'SalesInvoiceRows'=>['GstFigures','Items'=>function($e) use($stock_group_id){
							return $e->where(['Items.stock_group_id'=>$stock_group_id])
							->contain(['StockGroups'=>['ParentStockGroups'],'Sizes']);
							}]])
					->order(['voucher_no' => 'ASC']);
				}else{
					$salesInvoices = $this->SalesInvoices->find()->where(['SalesInvoices.company_id'=>$company_id])->where($where)->orWhere($where1)
					->contain(['Companies', 'PartyLedgers'=>['Customers'], 'SalesLedgers', 'SalesInvoiceRows'=>['GstFigures','Items'=>['StockGroups'=>['ParentStockGroups'],'Sizes']]])
					->order(['voucher_no' => 'ASC']);
				}
			}else{
			if($stock_group_id){
					$salesInvoices = $this->SalesInvoices->find()->where(['SalesInvoices.company_id'=>$company_id])->where($where1)
					->contain(['Companies', 'PartyLedgers'=>['Customers'], 'SalesLedgers', 'SalesInvoiceRows'=>
					['GstFigures','Items'=>function($e) use($stock_group_id){
							return $e->where(['Items.stock_group_id'=>$stock_group_id])
							->contain(['StockGroups'=>['ParentStockGroups'],'Sizes']);
							}]])
					->order(['voucher_no' => 'ASC']);
			}else{
					$salesInvoices = $this->SalesInvoices->find()->where(['SalesInvoices.company_id'=>$company_id])->where($where1)
					->contain(['Companies', 'PartyLedgers'=>['Customers'], 'SalesLedgers', 'SalesInvoiceRows'=>
					['GstFigures','Items'=>['StockGroups'=>['ParentStockGroups'],'Sizes']]])
					->order(['voucher_no' => 'ASC']);
			}
		
		}
		
		
		//pr($salesInvoices->toArray()); exit;
		$i=0; 
		$saleType=[]; 
		$brandWise=[]; 
		foreach($salesInvoices as $salesInvoice){ 
			 if(@$salesInvoice->invoice_receipt_type=="credit_cash"){ 
				@$saleType['cash']+=@$salesInvoice->receipt_amount;
				@$saleType['credit']+=@$salesInvoice->amount_after_tax-@$salesInvoice->receipt_amount;
			}else{
				@$saleType[@$salesInvoice->invoice_receipt_type]+=@$salesInvoice->amount_after_tax;
			} 
			//@$saleType[@$salesInvoice->invoice_receipt_type]+=@$salesInvoice->amount_after_tax;
			
			foreach($salesInvoice->sales_invoice_rows as $sales_invoice_row){ 
				@$brandWise[$sales_invoice_row->item->stock_group_id]+=$sales_invoice_row->net_amount;
				//$i++;
			}
		}
		$AllStockGroup=$this->SalesInvoices->SalesInvoiceRows->Items->stockGroups->find('list')->toArray();
		
		//
		$AccountingGroups=$this->SalesInvoices->SalesInvoiceRows->Ledgers->AccountingGroups->find()
		->where(['AccountingGroups.nature_of_group_id IN'=>[4],'AccountingGroups.company_id'=>$company_id]);
		
		$Groups=[];
		foreach($AccountingGroups as $AccountingGroup){
			$Groups[$AccountingGroup->id]['ids'][]=$AccountingGroup->id;
			$Groups[$AccountingGroup->id]['name']=$AccountingGroup->name;
			$accountingChildGroups = $this->SalesInvoices->SalesInvoiceRows->Ledgers->AccountingGroups->find('children', ['for' => $AccountingGroup->id]);
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
		
		$query=$this->SalesInvoices->AccountingEntries->find();
		$query->select(['ledger_id','totalDebit' => $query->func()->sum('AccountingEntries.debit'),'totalCredit' => $query->func()->sum('AccountingEntries.credit')])
				//->group('AccountingEntries.ledger_id')
				->where(['AccountingEntries.company_id'=>$company_id,'AccountingEntries.transaction_date >='=>$from_date, 'AccountingEntries.transaction_date <='=>$to_date])
				->contain(['Ledgers'=>function($q){
					return $q->select(['Ledgers.accounting_group_id','Ledgers.id','Ledgers.name']);
				}]);
		$query->matching('Ledgers', function ($q) use($AllGroups){
			return $q->where(['Ledgers.accounting_group_id IN' => $AllGroups]);
		});
		
		$expenseData=$query->first();
		//pr($expenseData); exit;
		
		//pr($AllStockGroup);exit;
		
		$companies=$this->SalesInvoices->Companies->find()->contain(['States'])->where(['Companies.id'=>$company_id])->first();
		$this->set(compact('companies','SalesInvoices', 'from', 'to','party_ids','invoice_no','url','status','saleType','brandWise','AllStockGroup','expenseData','Groups'));
        $this->set('_serialize', ['salesInvoices']);
    }
	
    /**
     * View method
     *
     * @param string|null $id Sales Invoice id.
     * @return \Cake\Http\Response|void
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
	 
	 public function gstReport()
    {
		$status=$this->request->query('status'); 
		if(!empty($status)){ 
			$this->viewBuilder()->layout('excel_layout');	
		}else{ 
			$this->viewBuilder()->layout('index_layout');
		}
		$location_id=$this->Auth->User('session_location_id');
		$company_id=$this->Auth->User('session_company_id');
		$url=$this->request->here();
		$url=parse_url($url,PHP_URL_QUERY);
	    $from=$this->request->query('from_date');
		$to=$this->request->query('to_date');
		
		$where=[];
		
		if(!empty($from)){ 
			$from=date('Y-m-d', strtotime($from));
			$where['SalesInvoices.transaction_date >=']= $from;
		}else{
			$from=date('Y-m-d');
			$where['SalesInvoices.transaction_date >=']= $from;
		}
		if(!empty($to)){
			$to=date('Y-m-d', strtotime($to));
			$where['SalesInvoices.transaction_date <='] = $to;
		}else{
			$to=date('Y-m-d');
			$where['SalesInvoices.transaction_date <='] = $to;
		}
		
		//$where['SalesInvoices.location_id'] = $location_id;
		
		if(!empty($where)){
		$salesInvoicesDatas = $this->SalesInvoices->find()
			->contain(['Companies', 'PartyLedgers'=>['Customers'=>function($q){
					return $q->select(['Customers.state_id','name','gstin'])->contain(['States']);
				}],'SalesLedgers', 
			'SalesInvoiceRows' => function($q) {
				return $q->select(['sales_invoice_id','gst_figure_id','gst_total' => $q->func()->sum('SalesInvoiceRows.gst_value'),'total_taxable_amt' => $q->func()->sum('SalesInvoiceRows.taxable_value'),'total_net_amt' => $q->func()->sum('SalesInvoiceRows.net_amount')])->contain(['GstFigures'])->group('SalesInvoiceRows.gst_figure_id')->group('SalesInvoiceRows.sales_invoice_id')->autoFields(true);
			}])
			->where(['SalesInvoices.company_id'=>$company_id,'SalesInvoices.status !='=>'cancel'])
			->where($where)
			->order(['voucher_no' => 'ASC'])
			;
		}
		//pr($salesInvoicesDatas->toArray()); exit;
		
		$i=0; 
		$StateWiseTaxableAmt=[];
		$StateWiseGst=[];
		$TotalTaxable=0;
		$TotalCGst=0;
		$TotalSGst=0;
		$TotalIGst=0;
		$StateName=[];
		foreach($salesInvoicesDatas as $salesInvoice){
			foreach($salesInvoice->sales_invoice_rows as $data)
			{  
		 
			/* $TotalTaxable+=$salesInvoice->amount_before_tax;
			$TotalCGst+=$salesInvoice->total_cgst;
			$TotalSGst+=$salesInvoice->total_sgst;
			$TotalIGst+=$salesInvoice->total_igst; */
			@$StateWiseTaxableAmt[$salesInvoice->party_ledger->customer->state_id][$data->gst_figure_id]+=@$data->total_taxable_amt;
			@$StateWiseGst[$salesInvoice->party_ledger->customer->state_id][$data->gst_figure_id]+=@$data->gst_total;
			@$StateName[$salesInvoice->party_ledger->customer->state_id]=@$salesInvoice->party_ledger->customer->state->name;
		
			}
		}
		//pr($StateWiseTaxableAmt); exit; 
		//ksort($SalesInvoices);
		//pr($SalesInvoices);exit;
		
		//start state wise gst
		/* $SalesInvoices=$this->AccountingEntries->SalesInvoices->find()->where(['SalesInvoices.company_id'=>$company_id,'SalesInvoices.transaction_date >='=>$from_date, 'SalesInvoices.transaction_date <='=>$to_date])->contain(['Customers'=>function($q){
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
			
		}
		
		
		//$States=$this->AccountingEntries->SalesInvoices->Customers->States->find();
		$GstFigures=$this->AccountingEntries->Ledgers->GstFigures->find()->where(['company_id'=>$company_id]); */
		//end state wise gst
		$GstFigures=$this->SalesInvoices->PartyLedgers->GstFigures->find()->where(['company_id'=>$company_id]);
		$GstFiguresDatas=[];
		foreach($GstFigures as $data1){
			$GstFiguresDatas[$data1->id]=$data1->name;
		}
		//pr($salesInvoicesDatas->toArray()); exit;
		$companies=$this->SalesInvoices->Companies->find()->contain(['States'])->where(['Companies.id'=>$company_id])->first();
		$this->set(compact('companies','SalesInvoices', 'from', 'to','party_ids','invoice_no','url','status','salesInvoicesDatas','TotalTaxable','TotalCGst','TotalSGst','TotalIGst','StateWiseTaxableAmt','StateWiseGst','States','StateName','GstFiguresDatas'));
        $this->set('_serialize', ['salesInvoices']);
    }
    public function view($id = null)
    {
        $salesInvoice = $this->SalesInvoices->get($id, [
            'contain' => ['Companies', 'Customers', 'GstFigures', 'SalesInvoiceRows']
        ]);

        $this->set('salesInvoice', $salesInvoice);
        $this->set('_serialize', ['salesInvoice']);
    }

    /**
     * Add method
     *
     * @return \Cake\Http\Response|null Redirects on successful add, renders view otherwise.
     */
    public function add()
    {
		$this->viewBuilder()->layout('index_layout');
        $salesInvoice = $this->SalesInvoices->newEntity();
		$Customers = $this->SalesInvoices->Customers->newEntity();
		$company_id=$this->Auth->User('session_company_id');
		$location_id=$this->Auth->User('session_location_id');
		$stateDetails=$this->Auth->User('session_company');
		$financialYear_id=$this->Auth->User('financialYear_id');
		$state_id=$stateDetails->state_id;
		$due_days=0;
		$roundOffId = $this->SalesInvoices->SalesInvoiceRows->Ledgers->find()
		->where(['Ledgers.company_id'=>$company_id, 'Ledgers.round_off'=>1])->first();
		
		$FinancialYearData=$this->SalesInvoices->Companies->FinancialYears->get($financialYear_id);
		
		$Voucher_no = $this->SalesInvoices->find()->select(['voucher_no'])->where(['SalesInvoices.company_id'=>$company_id,'SalesInvoices.financial_year_id'=>$financialYear_id])->order(['voucher_no' => 'DESC'])->first();
		
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
            $salesInvoice = $this->SalesInvoices->patchEntity($salesInvoice, $this->request->getData());
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
			}else if($salesInvoice->invoice_receipt_type=='credit_cash'){
				$salesInvoice->receipt_amount=$salesInvoice->credit_cash_amt;
			}
			else{
				$salesInvoice->receipt_amount=0;
			}
			
			//pr($salesInvoice); exit;
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
				}else if($salesInvoice->invoice_receipt_type=='credit_cash' && $salesInvoice->credit_cash_amt > 0){
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
										'amount' => $salesInvoice->credit_cash_amt,
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
										'credit' => $salesInvoice->credit_cash_amt])
					  ->execute();
					   $receiptRowData2 = $this->SalesInvoices->Receipts->ReceiptRows->query();
								$receiptRowData2->insert(['receipt_id','company_id','cr_dr', 'ledger_id', 'debit'])
										->values([
										'receipt_id' => $receiptId->id,
										'company_id' => $salesInvoice->company_id,
										'cr_dr' => 'Dr',
										'ledger_id' => $receiptLedgerId->id,
										'debit' => $salesInvoice->credit_cash_amt])
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
										'debit' => $salesInvoice->credit_cash_amt,
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
										'credit' => $salesInvoice->credit_cash_amt,
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
					$accountEntry->credit                     = $salesInvoice->credit_cash_amt;
					$accountEntry->transaction_date           = $salesInvoice->transaction_date;
					$accountEntry->company_id                 = $company_id;
					$accountEntry->receipt_id                 = $receiptId->id;
					$accountEntry->receipt_row_id             = 0;
					$this->SalesInvoices->Receipts->AccountingEntries->save($accountEntry);
					
					$accountEntry = $this->SalesInvoices->Receipts->AccountingEntries->newEntity();
					$accountEntry->ledger_id                  = $receiptLedgerId->id;
					$accountEntry->debit                      = $salesInvoice->credit_cash_amt;
					$accountEntry->credit                     = 0;
					$accountEntry->transaction_date           = $salesInvoice->transaction_date;
					$accountEntry->company_id                 = $company_id;
					$accountEntry->receipt_id                 = $receiptId->id;
					$accountEntry->receipt_row_id             = 0;
					$this->SalesInvoices->Receipts->AccountingEntries->save($accountEntry);
					//Accounting Entries for Receipt End//
					
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
		 
		 $this->Flash->error(__('The sales invoice could not be saved. Please, try again.'));
		}
		$customers = $this->SalesInvoices->Customers->find()
					->where(['company_id'=>$company_id]);
		$customerOptions=[];
		foreach($customers as $customer){
			$customerOptions[]=['text' =>$customer->name, 'value' => $customer->id ,'customer_state_id'=>$customer->state_id];
		}
		
/* 		$items = $this->SalesInvoices->SalesInvoiceRows->Items->find()
					->where(['Items.company_id'=>$company_id])
					->contain(['FirstGstFigures', 'SecondGstFigures', 'Units']);
		$itemLedgers=[];
		foreach($items->toArray() as $data)
		{
			$itemId=$data->id;
			$query = $this->SalesInvoices->SalesInvoiceRows->Items->ItemLedgers->find()
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
		->contain(['Items'=>['FirstGstFigures', 'SecondGstFigures', 'Units']]);
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
				if($remaining>0)
				{
				$itemOptions[]=['text'=>$dd->item->item_code.' '.$dd->item->name, 'value'=>$dd->item_id,'item_code'=>$dd->item->item_code, 'first_gst_figure_id'=>$dd->item->first_gst_figure_id, 'gst_amount'=>floatval($dd->item->gst_amount), 'sales_rate'=>$dd->item->sales_rate, 'second_gst_figure_id'=>$dd->item->second_gst_figure_id, 'FirstGstFigure'=>$dd->item->FirstGstFigures->tax_percentage, 'SecondGstFigure'=>$dd->item->SecondGstFigures->tax_percentage];
				}
			}
		} */
		
		$items = $this->SalesInvoices->SalesInvoiceRows->Items->find()
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
		
        $partyParentGroups = $this->SalesInvoices->SalesInvoiceRows->Ledgers->AccountingGroups->find()
						->where(['AccountingGroups.company_id'=>$company_id, 'AccountingGroups.sale_invoice_party'=>'1']);
		$partyGroups=[];
		
		foreach($partyParentGroups as $partyParentGroup)
		{
			$accountingGroups = $this->SalesInvoices->SalesInvoiceRows->Ledgers->AccountingGroups
			->find('children', ['for' => $partyParentGroup->id])->toArray();
			$partyGroups[]=$partyParentGroup->id;
			foreach($accountingGroups as $accountingGroup){
				$partyGroups[]=$accountingGroup->id;
			}
		}
	
		if($partyGroups)
		{  
			$Partyledgers = $this->SalesInvoices->SalesInvoiceRows->Ledgers->find()
							->where(['Ledgers.accounting_group_id IN' =>$partyGroups,'Ledgers.company_id'=>$company_id])
							->contain(['Customers']);
        }
		
		
		$partyOptions=[];
		foreach($Partyledgers as $Partyledger){
		
		$receiptAccountLedgers = $this->SalesInvoices->SalesInvoiceRows->Ledgers->AccountingGroups->find()
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
		
		$accountLedgers = $this->SalesInvoices->SalesInvoiceRows->Ledgers->AccountingGroups->find()->where(['AccountingGroups.sale_invoice_sales_account'=>1,'AccountingGroups.company_id'=>$company_id])->first();

		$accountingGroups2 = $this->SalesInvoices->SalesInvoiceRows->Ledgers->AccountingGroups
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
			$Accountledgers = $this->SalesInvoices->SalesInvoiceRows->Ledgers->find('list')->where(['Ledgers.accounting_group_id IN' =>$account_ids]);
        }
		
		        $states = $this->SalesInvoices->SalesInvoiceRows->Ledgers->Customers->States->find('list',
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
													
		$cities = $this->SalesInvoices->SalesInvoiceRows->Ledgers->Customers->Cities->  find('list',
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
		
						
		$gstFigures = $this->SalesInvoices->GstFigures->find('list')
						->where(['company_id'=>$company_id]);
						
		$CashPartyLedgers = $this->SalesInvoices->SalesInvoiceRows->Ledgers->find()
							->where(['Ledgers.cash ' =>1,'Ledgers.company_id'=>$company_id])->first();
		$this->set(compact('salesInvoice', 'companies', 'customerOptions', 'gstFigures', 'voucher_no','company_id','itemOptions','state_id', 'partyOptions', 'Accountledgers', 'location_id', 'CashPartyLedgers','FinancialYearData','states','cities'));
        $this->set('_serialize', ['salesInvoice']);
    }	

public function edit($id = null)
    {
	$this->viewBuilder()->layout('index_layout');
        $salesInvoice = $this->SalesInvoices->get($id, [
            'contain' => (['SaleReturns'=>['SaleReturnRows' => function($q) {
				return $q->select(['sale_return_id','sales_invoice_row_id','item_id','total' => $q->func()->sum('SaleReturnRows.return_quantity')])->group('SaleReturnRows.sales_invoice_row_id');
			}],'SalesInvoiceRows'=>['Items', 'GstFigures']])
        ]);
		

		$sales_return_qty=[];
			foreach($salesInvoice->sale_returns as $sale_returns){ 
				foreach($sale_returns->sale_return_rows as $sale_return_row){ 
					$sales_return_qty[@$sale_return_row->sales_invoice_row_id]=@$sales_return_qty[$sale_return_row->sales_invoice_row_id]+$sale_return_row->total;
					
				}
			}

		$company_id=$this->Auth->User('session_company_id');
		$stateDetails=$this->Auth->User('session_company');
		$location_id=$this->Auth->User('session_location_id');
		$financialYear_id=$this->Auth->User('financialYear_id');
		$state_id=$stateDetails->state_id;
		$due_days=0;
		$roundOffId = $this->SalesInvoices->SalesInvoiceRows->Ledgers->find()
		->where(['Ledgers.company_id'=>$company_id, 'Ledgers.round_off'=>1])->first();
		$Voucher_no = $this->SalesInvoices->find()->select(['voucher_no'])->where(['SalesInvoices.company_id'=>$company_id,'SalesInvoices.financial_year_id'=>$financialYear_id])->order(['voucher_no' => 'DESC'])->first();
		if($Voucher_no)
		{
			$voucher_no=$Voucher_no->voucher_no+1;
		}
		else
		{
			$voucher_no=1;
		} 
		
        if ($this->request->is(['patch', 'post', 'put'])) {
		    $transaction_date=date('Y-m-d', strtotime($this->request->data['transaction_date']));
			
            $salesInvoice = $this->SalesInvoices->patchEntity($salesInvoice, $this->request->getData());
            $salesInvoice->transaction_date=$transaction_date;
			$due_days=$this->request->data['due_days'];
			
			if($salesInvoice->invoice_receipt_type=='cash' && $salesInvoice->invoiceReceiptTd==1)
				{
					$salesInvoice->receipt_amount=$salesInvoice->receipt_amount;
				}
				else{
				$salesInvoice->receipt_amount=0;
				}
			
		if ($this->SalesInvoices->save($salesInvoice)) {
			
			$receiptIdExist = $this->SalesInvoices->Receipts->find()
			->where(['Receipts.company_id'=>$company_id, 'Receipts.sales_invoice_id'=>$salesInvoice->id])
			->first();
			
			if($receiptIdExist)
			{
				if($salesInvoice->invoice_receipt_type=='cash' && $salesInvoice->invoiceReceiptTd==1)
					{
					$query_update = $this->SalesInvoices->Receipts->query();
						$query_update->update()
						->set(['company_id' => $salesInvoice->company_id,
										'transaction_date' => $salesInvoice->transaction_date,
										'sales_invoice_id' => $salesInvoice->id])
						->where(['Receipts.company_id'=>$company_id, 'Receipts.sales_invoice_id'=>$salesInvoice->id])
						->execute();
						
						$receiptId = $this->SalesInvoices->Receipts->find()->select(['id'])->where(['Receipts.company_id'=>$company_id,'Receipts.sales_invoice_id'=>$salesInvoice->id])->first();
					 
						$receiptLedgerId = $this->SalesInvoices->SalesInvoiceRows->Ledgers->find()
						->where(['Ledgers.cash' =>'1','Ledgers.company_id'=>$company_id])->first();
						 $refLedgerId = $this->SalesInvoices->SalesInvoiceRows->Ledgers->find()
					   ->where(['Ledgers.id' =>$salesInvoice->party_ledger_id,'Ledgers.company_id'=>$company_id])->first();
					   
						$query_update1 = $this->SalesInvoices->Receipts->ReceiptRows->query();
						$query_update1->update()
						->set(['receipt_id' => $receiptId->id,
										'company_id' => $salesInvoice->company_id,
										'cr_dr' => 'Cr',
										'ledger_id' => $salesInvoice->party_ledger_id,
										'credit' => $salesInvoice->receipt_amount])
						->where(['ReceiptRows.company_id'=>$company_id, 'ReceiptRows.receipt_id'=>$receiptId->id, 'ReceiptRows.cr_dr'=>'Cr'])
						->execute();
						
						$query_update2 = $this->SalesInvoices->Receipts->ReceiptRows->query();
						$query_update2->update()
						->set(['receipt_id' => $receiptId->id,
										'company_id' => $salesInvoice->company_id,
										'cr_dr' => 'Dr',
										'ledger_id' => $receiptLedgerId->id,
										'debit' => $salesInvoice->receipt_amount])
						->where(['ReceiptRows.company_id'=>$company_id, 'ReceiptRows.receipt_id'=>$receiptId->id, 'ReceiptRows.cr_dr'=>'Dr'])
						->execute();
						
						$this->SalesInvoices->Receipts->AccountingEntries->deleteAll(['ReferenceDetails.receipt_id'=>$receiptId->id]);
						
					$accountEntry = $this->SalesInvoices->Receipts->AccountingEntries->newEntity();
					$accountEntry->ledger_id                  = $salesInvoice->party_ledger_id;
					$accountEntry->debit                      = 0;
					$accountEntry->credit                     = $salesInvoice->receipt_amount;
					$accountEntry->transaction_date           = $salesInvoice->transaction_date;
					$accountEntry->company_id                 = $company_id;
					$accountEntry->receipt_id                 = $receiptId->id;
					$accountEntry->receipt_row_id             = 0;
					$this->SalesInvoices->Receipts->AccountingEntries->save($accountEntry);
					
					$accountEntry = $this->SalesInvoices->Receipts->AccountingEntries->newEntity();
					$accountEntry->ledger_id                  = $receiptLedgerId->id;
					$accountEntry->debit                      = $salesInvoice->receipt_amount;
					$accountEntry->credit                     = 0;
					$accountEntry->transaction_date           = $salesInvoice->transaction_date;
					$accountEntry->company_id                 = $company_id;
					$accountEntry->receipt_id                 = $receiptId->id;
					$accountEntry->receipt_row_id             = 0;
					$this->SalesInvoices->Receipts->AccountingEntries->save($accountEntry);
						
					   $receiptRowCrId = $this->SalesInvoices->Receipts->ReceiptRows->find()->select(['id'])->where(['ReceiptRows.company_id'=>$company_id,'ReceiptRows.receipt_id'=>$receiptId->id, 'ReceiptRows.cr_dr'=>'Cr'])->first();
					   $receiptRowDrId = $this->SalesInvoices->Receipts->ReceiptRows->find()->select(['id'])->where(['ReceiptRows.company_id'=>$company_id,'ReceiptRows.receipt_id'=>$receiptId->id, 'ReceiptRows.cr_dr'=>'Dr'])->first();
					  
					  
					  $refExist = $this->SalesInvoices->Receipts->ReceiptRows->ReferenceDetails->find()->select(['id'])->where(['ReferenceDetails.company_id'=>$company_id,'ReferenceDetails.sales_invoice_id'=>$salesInvoice->id]);
						
						if($refExist)
						{
						
					  
					  if($refLedgerId->bill_to_bill_accounting=='yes')
						{
							$refData1 = $this->SalesInvoices->Receipts->ReceiptRows->ReferenceDetails->query();
							$refData1->update()
							->set(['company_id' => $salesInvoice->company_id,
										'ledger_id' => $salesInvoice->party_ledger_id,
										'type' => 'New Ref',
										'debit' =>$salesInvoice->amount_after_tax,
										'sales_invoice_id' => $salesInvoice->id,
										'transaction_date'=>$salesInvoice->transaction_date,
										'due_days'=>$due_days
									])
						->where(['ReferenceDetails.company_id'=>$company_id, 'ReferenceDetails.sales_invoice_id'=>$salesInvoice->id])
						->execute();
					  
					  $refExist2nd = $this->SalesInvoices->Receipts->ReceiptRows->ReferenceDetails->find()->select(['id'])->where(['ReferenceDetails.company_id'=>$company_id,'ReferenceDetails.receipt_id'=>$receiptId->id]);
						
						if($refExist2nd)
						{
					 $refData2 = $this->SalesInvoices->Receipts->ReceiptRows->ReferenceDetails->query();
						$refData2->update()
						->set(['company_id' => $salesInvoice->company_id,
										'ledger_id' => $salesInvoice->party_ledger_id,
										'type' => 'Against',
										'credit' => $salesInvoice->receipt_amount,
										'receipt_id' => $receiptId->id,
										'receipt_row_id' => $receiptRowCrId->id,
										'transaction_date' => $salesInvoice->transaction_date
										])
						->where(['ReferenceDetails.company_id'=>$company_id, 'ReferenceDetails.receipt_id'=>$receiptId->id, 'ReferenceDetails.receipt_row_id'=>$receiptRowCrId->id])
						->execute();
						}
						else{
					
						$refData2 = $this->SalesInvoices->Receipts->ReceiptRows->ReferenceDetails->query();
								$refData2->insert(['company_id','ledger_id','type', 'ref_name', 'credit', 'receipt_id','receipt_row_id','transaction_date'])
										->values([
										'company_id' => $salesInvoice->company_id,
										'ledger_id' => $salesInvoice->party_ledger_id,
										'type' => 'Against',
										'ref_name' => $salesInvoice->voucher_no,
										'credit' => $salesInvoice->receipt_amount,
										'receipt_id' => $receiptId->id,
										'receipt_row_id' => $receiptRowCrId->id,
										'transaction_date' => $salesInvoice->transaction_date])
					  ->execute();
						}
						
						}
						else if($refLedgerId->bill_to_bill_accounting=='no')
						{
							$refData1 = $this->SalesInvoices->Receipts->ReceiptRows->ReferenceDetails->query();
							$refData1->update()
							->set(['company_id' => $salesInvoice->company_id,
										'ledger_id' => $salesInvoice->party_ledger_id,
										'type' => 'New Ref',
										'debit' => '0',
										'due_days'=>$due_days,
										'sales_invoice_id' => $salesInvoice->id])
						->where(['ReferenceDetails.company_id'=>$company_id, 'ReferenceDetails.sales_invoice_id'=>$salesInvoice->id])
						->execute();
					  
					 $refData2 = $this->SalesInvoices->Receipts->ReceiptRows->ReferenceDetails->query();
						$refData2->update()
						->set(['company_id' => $salesInvoice->company_id,
										'ledger_id' => $salesInvoice->party_ledger_id,
										'type' => 'Against',
										'credit' => '0',
										'receipt_id' => $receiptId->id,
										'receipt_row_id' => $receiptRowCrId->id])
						->where(['ReferenceDetails.company_id'=>$company_id, 'ReferenceDetails.receipt_id'=>$receiptId->id, 'ReferenceDetails.receipt_row_id'=>$receiptRowCrId->id])
						->execute();
						
						 }
						
						}
						else{
						
					  if($refLedgerId->bill_to_bill_accounting=='yes')
						      {
						        $refData1 = $this->SalesInvoices->Receipts->ReceiptRows->ReferenceDetails->query();
								$refData1->insert(['company_id','ledger_id','type', 'ref_name', 'debit','sales_invoice_id','due_days','transaction_date'])
										->values([
										'company_id' => $salesInvoice->company_id,
										'ledger_id' => $salesInvoice->party_ledger_id,
										'type' => 'New Ref',
										'ref_name' => $salesInvoice->voucher_no,
										'debit' => $salesInvoice->amount_after_tax,
										'sales_invoice_id' => $salesInvoice->id,
										'due_days'=>$due_days,
										'transaction_date' => $salesInvoice->transaction_date])
					  ->execute();
					  
								$refData2 = $this->SalesInvoices->Receipts->ReceiptRows->ReferenceDetails->query();
								$refData2->insert(['company_id','ledger_id','type', 'ref_name', 'credit', 'receipt_id','receipt_row_id','transaction_date'])
										->values([
										'company_id' => $salesInvoice->company_id,
										'ledger_id' => $salesInvoice->party_ledger_id,
										'type' => 'Against',
										'ref_name' => $salesInvoice->voucher_no,
										'credit' => $salesInvoice->receipt_amount,
										'receipt_id' => $receiptId->id,
										'receipt_row_id' => $receiptRowCrId->id,
										'transaction_date' => $salesInvoice->transaction_date])
					  ->execute();
					        }
						}
					}
					else
					{
					//echo 'ok';exit;
					$query_update = $this->SalesInvoices->Receipts->query();
						$query_update->update()
						->set(['company_id' => $salesInvoice->company_id,
										'transaction_date' => $salesInvoice->transaction_date,
										'sales_invoice_id' => $salesInvoice->id])
						->where(['Receipts.company_id'=>$company_id, 'Receipts.sales_invoice_id'=>$salesInvoice->id])
						->execute();
						
						$receiptId = $this->SalesInvoices->Receipts->find()->select(['id'])->where(['Receipts.company_id'=>$company_id,'Receipts.sales_invoice_id'=>$salesInvoice->id])->first();
						$refLedgerId = $this->SalesInvoices->SalesInvoiceRows->Ledgers->find()
					   ->where(['Ledgers.id' =>$salesInvoice->party_ledger_id,'Ledgers.company_id'=>$company_id])->first();
					 
						$receiptLedgerId = $this->SalesInvoices->SalesInvoiceRows->Ledgers->find()
						->where(['Ledgers.cash' =>'1','Ledgers.company_id'=>$company_id])->first();
						
						$query_update1 = $this->SalesInvoices->Receipts->ReceiptRows->query();
						$query_update1->update()
						->set(['receipt_id' => $receiptId->id,
										'company_id' => $salesInvoice->company_id,
										'cr_dr' => 'Cr',
										'ledger_id' => $salesInvoice->party_ledger_id,
										'credit' => 0])
						->where(['ReceiptRows.company_id'=>$company_id, 'ReceiptRows.receipt_id'=>$receiptId->id, 'ReceiptRows.cr_dr'=>'Cr'])
						->execute();
						
						$query_update2 = $this->SalesInvoices->Receipts->ReceiptRows->query();
						$query_update2->update()
						->set(['receipt_id' => $receiptId->id,
										'company_id' => $salesInvoice->company_id,
										'cr_dr' => 'Dr',
										'ledger_id' => $receiptLedgerId->id,
										'debit' => 0])
						->where(['ReceiptRows.company_id'=>$company_id, 'ReceiptRows.receipt_id'=>$receiptId->id, 'ReceiptRows.cr_dr'=>'Dr'])
						->execute();
						
						$this->SalesInvoices->Receipts->AccountingEntries->deleteAll(['ReferenceDetails.receipt_id'=>$receiptId->id]);
						
					$accountEntry = $this->SalesInvoices->Receipts->AccountingEntries->newEntity();
					$accountEntry->ledger_id                  = $salesInvoice->party_ledger_id;
					$accountEntry->debit                      = 0;
					$accountEntry->credit                     = 0;
					$accountEntry->transaction_date           = $salesInvoice->transaction_date;
					$accountEntry->company_id                 = $company_id;
					$accountEntry->receipt_id                 = $receiptId->id;
					$accountEntry->receipt_row_id             = 0;
					$this->SalesInvoices->Receipts->AccountingEntries->save($accountEntry);
					
					$accountEntry = $this->SalesInvoices->Receipts->AccountingEntries->newEntity();
					$accountEntry->ledger_id                  = $receiptLedgerId->id;
					$accountEntry->debit                      = 0;
					$accountEntry->credit                     = 0;
					$accountEntry->transaction_date           = $salesInvoice->transaction_date;
					$accountEntry->company_id                 = $company_id;
					$accountEntry->receipt_id                 = $receiptId->id;
					$accountEntry->receipt_row_id             = 0;
					$this->SalesInvoices->Receipts->AccountingEntries->save($accountEntry);
						
						
						
						$refExist = $this->SalesInvoices->Receipts->ReceiptRows->ReferenceDetails->find()->select(['id'])->where(['ReferenceDetails.company_id'=>$company_id,'ReferenceDetails.receipt_id'=>$receiptId->id]);
						
						if($refExist->toArray())
						{
						
						   $receiptRowCrId = $this->SalesInvoices->Receipts->ReceiptRows->find()->select(['id'])->where(['ReceiptRows.company_id'=>$company_id,'ReceiptRows.receipt_id'=>$receiptId->id, 'ReceiptRows.cr_dr'=>'Cr'])->first();
						   $receiptRowDrId = $this->SalesInvoices->Receipts->ReceiptRows->find()->select(['id'])->where(['ReceiptRows.company_id'=>$company_id,'ReceiptRows.receipt_id'=>$receiptId->id, 'ReceiptRows.cr_dr'=>'Dr'])->first();
						   
							if($refLedgerId->bill_to_bill_accounting=='yes')
								  {
								$refData1 = $this->SalesInvoices->Receipts->ReceiptRows->ReferenceDetails->query();
								$refData1->update()
								->set(['company_id' => $salesInvoice->company_id,
											'ledger_id' => $salesInvoice->party_ledger_id,
											'type' => 'New Ref',
											'debit' => $salesInvoice->amount_after_tax,
											'due_days'=>$due_days,
											'sales_invoice_id' => $salesInvoice->id])
								->where(['ReferenceDetails.company_id'=>$company_id, 'ReferenceDetails.sales_invoice_id'=>$salesInvoice->id])
								->execute();
							  
								 $refData2 = $this->SalesInvoices->Receipts->ReceiptRows->ReferenceDetails->query();
									$refData2->update()
									->set(['company_id' => $salesInvoice->company_id,
												'ledger_id' => $salesInvoice->party_ledger_id,
												'type' => 'Against',
												'credit' => '0',
												'receipt_id' => $receiptId->id,
												'receipt_row_id' => $receiptRowCrId->id])
								->where(['ReferenceDetails.company_id'=>$company_id, 'ReferenceDetails.receipt_id'=>$receiptId->id, 'ReferenceDetails.receipt_row_id'=>$receiptRowCrId->id])
								->execute();
								
								}
								else{
								   $refData1 = $this->SalesInvoices->Receipts->ReceiptRows->ReferenceDetails->query();
								$refData1->update()
								->set(['company_id' => $salesInvoice->company_id,
											'ledger_id' => $salesInvoice->party_ledger_id,
											'type' => 'New Ref',
											'debit' => 0,
											'due_days'=>$due_days,
											'sales_invoice_id' => $salesInvoice->id])
								->where(['ReferenceDetails.company_id'=>$company_id, 'ReferenceDetails.sales_invoice_id'=>$salesInvoice->id])
								->execute();
							  
								 $refData2 = $this->SalesInvoices->Receipts->ReceiptRows->ReferenceDetails->query();
									$refData2->update()
									->set(['company_id' => $salesInvoice->company_id,
												'ledger_id' => $salesInvoice->party_ledger_id,
												'type' => 'Against',
												'credit' => '0',
												'receipt_id' => $receiptId->id,
												'receipt_row_id' => $receiptRowCrId->id])
								->where(['ReferenceDetails.company_id'=>$company_id, 'ReferenceDetails.receipt_id'=>$receiptId->id, 'ReferenceDetails.receipt_row_id'=>$receiptRowCrId->id])
								->execute();
								}
						}
						else{
						
						
						 if($refLedgerId->bill_to_bill_accounting=='yes')
						      {
						        $refData1 = $this->SalesInvoices->Receipts->ReceiptRows->ReferenceDetails->query();
								$refData1->insert(['company_id','ledger_id','type', 'ref_name', 'debit', 'sales_invoice_id','due_days','transaction_date'])
										->values([
										'company_id' => $salesInvoice->company_id,
										'ledger_id' => $salesInvoice->party_ledger_id,
										'type' => 'New Ref',
										'ref_name' => $salesInvoice->voucher_no,
										'debit' => $salesInvoice->amount_after_tax,
										'sales_invoice_id' => $salesInvoice->id,
										'due_days'=>$due_days,
										'transaction_date' => $salesInvoice->transaction_date])
					  ->execute();
					        }
						
						}
					}
			}
			else{
			
			if($salesInvoice->invoice_receipt_type=='cash' && $salesInvoice->invoiceReceiptTd==1)
				{
				
				
				   $receiptVoucherNo = $this->SalesInvoices->Receipts->find()->select(['voucher_no'])->where(['Receipts.company_id'=>$company_id])->order(['voucher_no' => 'DESC'])->first();
						if($receiptVoucherNo)
						{
							$receipt_voucher_no=$receiptVoucherNo->voucher_no+1;
						}
						else
						{
							$receipt_voucher_no=1;
						}
						
						$receiptData = $this->SalesInvoices->Receipts->query();
								$receiptData->insert(['voucher_no', 'company_id','transaction_date','sales_invoice_id'])
										->values([
										'voucher_no' => $receipt_voucher_no,
										'company_id' => $salesInvoice->company_id,
										'transaction_date' => $salesInvoice->transaction_date,
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
										'credit' => $salesInvoice->receipt_amount])
					  ->execute();
					   $receiptRowData2 = $this->SalesInvoices->Receipts->ReceiptRows->query();
								$receiptRowData2->insert(['receipt_id','company_id','cr_dr', 'ledger_id', 'debit'])
										->values([
										'receipt_id' => $receiptId->id,
										'company_id' => $salesInvoice->company_id,
										'cr_dr' => 'Dr',
										'ledger_id' => $receiptLedgerId->id,
										'debit' => $salesInvoice->receipt_amount])
					  ->execute();
					  
					  $this->SalesInvoices->Receipts->AccountingEntries->deleteAll(['ReferenceDetails.receipt_id'=>$receiptId->id]);
						
					$accountEntry = $this->SalesInvoices->Receipts->AccountingEntries->newEntity();
					$accountEntry->ledger_id                  = $salesInvoice->party_ledger_id;
					$accountEntry->debit                      = 0;
					$accountEntry->credit                     = $salesInvoice->receipt_amount;
					$accountEntry->transaction_date           = $salesInvoice->transaction_date;
					$accountEntry->company_id                 = $company_id;
					$accountEntry->receipt_id                 = $receiptId->id;
					$accountEntry->receipt_row_id             = 0;
					$this->SalesInvoices->Receipts->AccountingEntries->save($accountEntry);
					
					$accountEntry = $this->SalesInvoices->Receipts->AccountingEntries->newEntity();
					$accountEntry->ledger_id                  = $receiptLedgerId->id;
					$accountEntry->debit                      = $salesInvoice->receipt_amount;
					$accountEntry->credit                     = 0;
					$accountEntry->transaction_date           = $salesInvoice->transaction_date;
					$accountEntry->company_id                 = $company_id;
					$accountEntry->receipt_id                 = $receiptId->id;
					$accountEntry->receipt_row_id             = 0;
					$this->SalesInvoices->Receipts->AccountingEntries->save($accountEntry);
					 
						$receiptRowCrId = $this->SalesInvoices->Receipts->ReceiptRows->find()->select(['id'])->where(['ReceiptRows.company_id'=>$company_id,'ReceiptRows.receipt_id'=>$receiptId->id, 'ReceiptRows.cr_dr'=>'Cr'])->first();
					   $receiptRowDrId = $this->SalesInvoices->Receipts->ReceiptRows->find()->select(['id'])->where(['ReceiptRows.company_id'=>$company_id,'ReceiptRows.receipt_id'=>$receiptId->id, 'ReceiptRows.cr_dr'=>'Dr'])->first();
					  
					  if($refLedgerId->bill_to_bill_accounting=='yes')
						{
							$refExist = $this->SalesInvoices->Receipts->ReceiptRows->ReferenceDetails->find()->select(['id'])->where(['ReferenceDetails.company_id'=>$company_id,'ReferenceDetails.sales_invoice_id'=>$salesInvoice->id]);
							if($refExist){
								 $deleteRef = $this->SalesInvoices->Receipts->ReceiptRows->ReferenceDetails->query();
								$deleteRefResult = $deleteRef->delete()
									->where(['sales_invoice_id' => $salesInvoice->id])
									->execute();
						        $refData1 = $this->SalesInvoices->Receipts->ReceiptRows->ReferenceDetails->query();
								$refData1->insert(['company_id','ledger_id','type', 'ref_name', 'debit', 'sales_invoice_id','due_days','transaction_date'])
										->values([
										'company_id' => $salesInvoice->company_id,
										'ledger_id' => $salesInvoice->party_ledger_id,
										'type' => 'New Ref',
										'ref_name' => $salesInvoice->voucher_no,
										'debit' => $salesInvoice->amount_after_tax,
										'sales_invoice_id' => $salesInvoice->id,
										'due_days'=>$due_days,
										'transaction_date' => $salesInvoice->transaction_date])
					  ->execute();
					  
								$refData2 = $this->SalesInvoices->Receipts->ReceiptRows->ReferenceDetails->query();
								$refData2->insert(['company_id','ledger_id','type', 'ref_name', 'credit','receipt_id','receipt_row_id','transaction_date'])
										->values([
										'company_id' => $salesInvoice->company_id,
										'ledger_id' => $salesInvoice->party_ledger_id,
										'type' => 'Against',
										'ref_name' => $salesInvoice->voucher_no,
										'credit' => $salesInvoice->receipt_amount,
										'receipt_id' => $receiptId->id,
										'receipt_row_id' => $receiptRowCrId->id,
										'transaction_date' => $salesInvoice->transaction_date])
					  ->execute();
						}
						}	
				    }
					
			else if($salesInvoice->invoice_receipt_type=='credit' && $salesInvoice->invoiceReceiptTd==1)
				{
				     $refExist = $this->SalesInvoices->Receipts->ReceiptRows->ReferenceDetails->find()->select(['id'])->where(['ReferenceDetails.company_id'=>$company_id,'ReferenceDetails.sales_invoice_id'=>$salesInvoice->id]);
						
						$refLedgerId = $this->SalesInvoices->SalesInvoiceRows->Ledgers->find()
						->where(['Ledgers.id' =>$salesInvoice->party_ledger_id,'Ledgers.company_id'=>$company_id])->first();
						
						if($refExist)
						{
						
							  if($refLedgerId->bill_to_bill_accounting=='yes')
								  {
								$refData1 = $this->SalesInvoices->Receipts->ReceiptRows->ReferenceDetails->query();
								$refData1->update()
								->set(['company_id' => $salesInvoice->company_id,
											'ledger_id' => $salesInvoice->party_ledger_id,
											'type' => 'New Ref',
											'debit' => $salesInvoice->amount_after_tax,
											'due_days'=>$due_days,
											'sales_invoice_id' => $salesInvoice->id])
								->where(['ReferenceDetails.company_id'=>$company_id, 'ReferenceDetails.sales_invoice_id'=>$salesInvoice->id])
								->execute();
								}
								else{
								   $refData1 = $this->SalesInvoices->Receipts->ReceiptRows->ReferenceDetails->query();
								$refData1->update()
								->set(['company_id' => $salesInvoice->company_id,
											'ledger_id' => $salesInvoice->party_ledger_id,
											'type' => 'New Ref',
											'debit' => 0,
											'due_days'=>$due_days,
											'sales_invoice_id' => $salesInvoice->id])
								->where(['ReferenceDetails.company_id'=>$company_id, 'ReferenceDetails.sales_invoice_id'=>$salesInvoice->id])
								->execute();
								}
						}
					else{
						
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
										'ref_name' => $salesInvoice->voucher_no,
										'debit' => $salesInvoice->amount_after_tax,
										'sales_invoice_id' => $salesInvoice->id,
										'due_days'=>$due_days,
										'transaction_date' => $salesInvoice->transaction_date])
					  ->execute();
						}
				    }
			
				}
			}
			
			
			 $deleteItemLedger = $this->SalesInvoices->ItemLedgers->query();
				$deleteResult = $deleteItemLedger->delete()
					->where(['sales_invoice_id' => $salesInvoice->id])
					->execute();
					$deleteAccountEntries = $this->SalesInvoices->AccountingEntries->query();
					$result = $deleteAccountEntries->delete()
						->where(['AccountingEntries.sales_invoice_id' => $id])
						->execute();
					$gstVal=0;
					$gVal=0;
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
                return $this->redirect(['action' => 'index']);
            }
            $this->Flash->error(__('The sales invoice could not be saved. Please, try again.'));
        }
        $companies = $this->SalesInvoices->Companies->find('list');
        $customers = $this->SalesInvoices->Customers->find('list')->where(['company_id'=>$company_id]);
        $gstFigures = $this->SalesInvoices->GstFigures->find('list')->where(['company_id'=>$company_id]);
        $this->set(compact('salesInvoice', 'companies', 'customers', 'gstFigures'));

		$customers = $this->SalesInvoices->Customers->find()
					->where(['company_id'=>$company_id]);
						$customerOptions=[];
		foreach($customers as $customer){
			$customerOptions[]=['text' =>$customer->name, 'value' => $customer->id ,'customer_state_id'=>$customer->state_id];
		}
		
/* 		$items = $this->SalesInvoices->SalesInvoiceRows->Items->find()
					->where(['Items.company_id'=>$company_id])
					->contain(['FirstGstFigures', 'SecondGstFigures', 'Units']);
		$itemLedgers=[];
		foreach($items->toArray() as $data)
		{
			$itemId=$data->id;
			$query = $this->SalesInvoices->SalesInvoiceRows->Items->ItemLedgers->find()
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
		->contain(['Items'=>['FirstGstFigures', 'SecondGstFigures', 'Units']]);
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
				$itemOptions[]=['text'=>$dd->item->item_code.' '.$dd->item->name, 'value'=>$dd->item_id,'item_code'=>$dd->item->item_code, 'first_gst_figure_id'=>$dd->item->first_gst_figure_id, 'gst_amount'=>floatval($dd->item->gst_amount), 'sales_rate'=>$dd->item->sales_rate, 'second_gst_figure_id'=>$dd->item->second_gst_figure_id, 'FirstGstFigure'=>$dd->item->FirstGstFigures->tax_percentage, 'SecondGstFigure'=>$dd->item->SecondGstFigures->tax_percentage];
				}
			}
		} */
	
		$items = $this->SalesInvoices->SalesInvoiceRows->Items->find()
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
			//pr($items->toArray());	
			$itemOptions=[];
			foreach($items as $d)
			{
				foreach($d->item_ledgers as $dd)
				{  
					if($dd->total_in > $dd->total_out || $dd->sales_invoice_id==$id)
					{ 
					$itemOptions[]=['text'=>$d->item_code.' '.$d->name, 'value'=>$dd->item_id,'item_code'=>$d->item_code, 'first_gst_figure_id'=>$d->first_gst_figure_id, 'gst_amount'=>floatval($d->gst_amount), 'sales_rate'=>$d->sales_rate, 'second_gst_figure_id'=>$d->second_gst_figure_id, 'FirstGstFigure'=>$d->FirstGstFigures->tax_percentage, 'SecondGstFigure'=>$d->SecondGstFigures->tax_percentage];
					}
				}
			}
		$items1 = $this->SalesInvoices->SalesInvoiceRows->Items->find()
					->where(['Items.company_id'=>$company_id])
					->contain(['ItemLedgers'=>function($query) use($company_id,$location_id,$id){
						return $query->where(['ItemLedgers.company_id' => $company_id, 'ItemLedgers.sales_invoice_id' => $id]);
					},'FirstGstFigures', 'SecondGstFigures', 'Units']);
		//pr($items1->toArray()); exit;
		foreach($items1 as $d)
			{
				foreach($d->item_ledgers as $dd)
				{  
					
					$itemOptions[]=['text'=>$d->item_code.' '.$d->name, 'value'=>$dd->item_id,'item_code'=>$d->item_code, 'first_gst_figure_id'=>$d->first_gst_figure_id, 'gst_amount'=>floatval($d->gst_amount), 'sales_rate'=>$d->sales_rate, 'second_gst_figure_id'=>$d->second_gst_figure_id, 'FirstGstFigure'=>$d->FirstGstFigures->tax_percentage, 'SecondGstFigure'=>$d->SecondGstFigures->tax_percentage];
					
				}
			}
		//pr($itemOptions); exit;
        $partyParentGroups = $this->SalesInvoices->SalesInvoiceRows->Ledgers->AccountingGroups->find()
						->where(['AccountingGroups.company_id'=>$company_id, 'AccountingGroups.sale_invoice_party'=>'1']);
		$partyGroups=[];
		foreach($partyParentGroups as $partyParentGroup)
		{
			$accountingGroups = $this->SalesInvoices->SalesInvoiceRows->Ledgers->AccountingGroups
			->find('children', ['for' => $partyParentGroup->id])->toArray();
			$partyGroups[]=$partyParentGroup->id;
			foreach($accountingGroups as $accountingGroup){
				$partyGroups[]=$accountingGroup->id;
			}
		}
		if($partyGroups)
		{  
			$Partyledgers = $this->SalesInvoices->SalesInvoiceRows->Ledgers->find()
							->where(['Ledgers.accounting_group_id IN' =>$partyGroups,'Ledgers.company_id'=>$company_id])
							->contain(['Customers']);
        }
		$partyOptions=[];
		foreach($Partyledgers as $Partyledger){
		
		$receiptAccountLedgers = $this->SalesInvoices->SalesInvoiceRows->Ledgers->AccountingGroups->find()
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
		
		$accountLedgers = $this->SalesInvoices->SalesInvoiceRows->Ledgers->AccountingGroups->find()->where(['AccountingGroups.sale_invoice_sales_account'=>1,'AccountingGroups.company_id'=>$company_id])->first();

		$accountingGroups2 = $this->SalesInvoices->SalesInvoiceRows->Ledgers->AccountingGroups
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
			$Accountledgers = $this->SalesInvoices->SalesInvoiceRows->Ledgers->find('list')->where(['Ledgers.accounting_group_id IN' =>$account_ids]);
        }
        $gstFigures = $this->SalesInvoices->GstFigures->find('list')
						->where(['company_id'=>$company_id]);
						
						//pr($sales_return_qty);
						//exit;
						
						
        $this->set(compact('salesInvoice', 'companies', 'customerOptions', 'gstFigures', 'voucher_no','company_id','itemOptions','state_id', 'Accountledgers', 'partyOptions', 'location_id','sales_return_qty'));
        $this->set('_serialize', ['salesInvoice']);
    }	
	
	
    public function salesInvoiceBill($id=null)
    {
		
	    $this->viewBuilder()->layout('');
		$company_id=$this->Auth->User('session_company_id');
		$stateDetails=$this->Auth->User('session_company');
		$state_id=$stateDetails->state_id;
		$invoiceBills= $this->SalesInvoices->find()
		->where(['SalesInvoices.id'=>$id])
		->contain(['Companies'=>['States'],'SalesInvoiceRows'=>['Items'=>['Sizes','Shades','Units'], 'GstFigures']]);
		
		$unit_ids=[];
		//pr($units->toArray());
		//exit;
	    foreach($invoiceBills->toArray() as $data){
			
		foreach($data->sales_invoice_rows as $sales_invoice_row){
		if(!in_array($sales_invoice_row->item->unit_id,$unit_ids)){
			$unit_ids[]=$sales_invoice_row->item->unit_id;
			}
		$item_id=$sales_invoice_row->item_id;
		$accountingEntries= $this->SalesInvoices->AccountingEntries->find()
		->where(['AccountingEntries.sales_invoice_id'=>$data->id]);
		$sales_invoice_row->accountEntries=$accountingEntries->toArray();
		
			$partyDetail= $this->SalesInvoices->SalesInvoiceRows->Ledgers->find()
			->where(['id'=>$data->party_ledger_id])->first();
		    $partyCustomerid=$partyDetail->customer_id;
			if($partyCustomerid>0)
			{
				$partyDetails= $this->SalesInvoices->Customers->find()
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
		$query = $this->SalesInvoices->SalesInvoiceRows->find();
		
		$totalTaxableAmt = $query->newExpr()
			->addCase(
				$query->newExpr()->add(['sales_invoice_id']),
				$query->newExpr()->add(['taxable_value']),
				'integer'
			);
		$totalgstAmt = $query->newExpr()
			->addCase(
				$query->newExpr()->add(['sales_invoice_id']),
				$query->newExpr()->add(['gst_value']),
				'integer'
			);
		$query->select([
			'total_taxable_amount' => $query->func()->sum($totalTaxableAmt),
			'total_gst_amount' => $query->func()->sum($totalgstAmt),'sales_invoice_id','item_id'
		])
		->where(['SalesInvoiceRows.sales_invoice_id' => $id])
		->group('gst_figure_id')
		->autoFields(true)
		->contain(['GstFigures']);
        $sale_invoice_rows = ($query);
		
		//pr($invoiceBills->toArray());exit;
		
		
		$this->set(compact('invoiceBills','taxable_type','sale_invoice_rows','partyCustomerid','units'));
        $this->set('_serialize', ['invoiceBills']);
    }	
	
	public function salesInvoiceBill1($id=null)
    {
		
	    $this->viewBuilder()->layout('');
		$company_id=$this->Auth->User('session_company_id');
		$stateDetails=$this->Auth->User('session_company');
		$state_id=$stateDetails->state_id;
		$invoiceBills= $this->SalesInvoices->find()
		->where(['SalesInvoices.id'=>$id])
		->contain(['Companies'=>['States'],'SalesInvoiceRows'=>['Items'=>['Sizes'], 'GstFigures']]);
	
	    foreach($invoiceBills->toArray() as $data){
		foreach($data->sales_invoice_rows as $sales_invoice_row){
		$item_id=$sales_invoice_row->item_id;
		$accountingEntries= $this->SalesInvoices->AccountingEntries->find()
		->where(['AccountingEntries.sales_invoice_id'=>$data->id]);
		$sales_invoice_row->accountEntries=$accountingEntries->toArray();
		
			$partyDetail= $this->SalesInvoices->SalesInvoiceRows->Ledgers->find()
			->where(['id'=>$data->party_ledger_id])->first();
		    $partyCustomerid=$partyDetail->customer_id;
			if($partyCustomerid>0)
			{
				$partyDetails= $this->SalesInvoices->Customers->find()
				->where(['Customers.id'=>$partyCustomerid])
				->contain(['States', 'Cities'])->first();
				$data->partyDetails=$partyDetails;
			}
			else
			{
				$partyDetails=(object)['name'=>'Cash Customer', 'state_id'=>$state_id];
				$data->partyDetails=$partyDetails;
			}
			
			if(@$data->company->state_id==$data->partyDetails->state_id){
				$taxable_type='CGST/SGST';
			}else{
				$taxable_type='IGST';
			}
			
		}
		}
		//pr($id);exit;
		$query = $this->SalesInvoices->SalesInvoiceRows->find();
		
		$totalTaxableAmt = $query->newExpr()
			->addCase(
				$query->newExpr()->add(['sales_invoice_id']),
				$query->newExpr()->add(['taxable_value']),
				'integer'
			);
		$totalgstAmt = $query->newExpr()
			->addCase(
				$query->newExpr()->add(['sales_invoice_id']),
				$query->newExpr()->add(['gst_value']),
				'integer'
			);
		$query->select([
			'total_taxable_amount' => $query->func()->sum($totalTaxableAmt),
			'total_gst_amount' => $query->func()->sum($totalgstAmt),'sales_invoice_id','item_id'
		])
		->where(['SalesInvoiceRows.sales_invoice_id' => $id])
		->group('gst_figure_id')
		->autoFields(true)
		->contain(['GstFigures']);
        $sale_invoice_rows = ($query);
		
		//pr($invoiceBills->toArray());exit;
		
		$this->set(compact('invoiceBills','taxable_type','sale_invoice_rows','partyCustomerid'));
        $this->set('_serialize', ['invoiceBills']);
    }
	public function salesInvoiceBill2($id=null)
    {
		
	    $this->viewBuilder()->layout('');
		$company_id=$this->Auth->User('session_company_id');
		$stateDetails=$this->Auth->User('session_company');
		$state_id=$stateDetails->state_id;
		$invoiceBills= $this->SalesInvoices->find()
		->where(['SalesInvoices.id'=>$id])
		->contain(['Companies'=>['States'],'SalesInvoiceRows'=>['Items'=>['Sizes'], 'GstFigures']]);
	
	    foreach($invoiceBills->toArray() as $data){
		foreach($data->sales_invoice_rows as $sales_invoice_row){
		$item_id=$sales_invoice_row->item_id;
		$accountingEntries= $this->SalesInvoices->AccountingEntries->find()
		->where(['AccountingEntries.sales_invoice_id'=>$data->id]);
		$sales_invoice_row->accountEntries=$accountingEntries->toArray();
		
			$partyDetail= $this->SalesInvoices->SalesInvoiceRows->Ledgers->find()
			->where(['id'=>$data->party_ledger_id])->first();
		    $partyCustomerid=$partyDetail->customer_id;
			if($partyCustomerid>0)
			{
				$partyDetails= $this->SalesInvoices->Customers->find()
				->where(['Customers.id'=>$partyCustomerid])
				->contain(['States', 'Cities'])->first();
				$data->partyDetails=$partyDetails;
			}
			else
			{
				$partyDetails=(object)['name'=>'Cash Customer', 'state_id'=>$state_id];
				$data->partyDetails=$partyDetails;
			}
			
			if(@$data->company->state_id==$data->partyDetails->state_id){
				$taxable_type='CGST/SGST';
			}else{
				$taxable_type='IGST';
			}
			
		}
		}
		//pr($id);exit;
		$query = $this->SalesInvoices->SalesInvoiceRows->find();
		
		$totalTaxableAmt = $query->newExpr()
			->addCase(
				$query->newExpr()->add(['sales_invoice_id']),
				$query->newExpr()->add(['taxable_value']),
				'integer'
			);
		$totalgstAmt = $query->newExpr()
			->addCase(
				$query->newExpr()->add(['sales_invoice_id']),
				$query->newExpr()->add(['gst_value']),
				'integer'
			);
		$query->select([
			'total_taxable_amount' => $query->func()->sum($totalTaxableAmt),
			'total_gst_amount' => $query->func()->sum($totalgstAmt),'sales_invoice_id','item_id'
		])
		->where(['SalesInvoiceRows.sales_invoice_id' => $id])
		->group('gst_figure_id')
		->autoFields(true)
		->contain(['GstFigures']);
        $sale_invoice_rows = ($query);
		
		//pr($invoiceBills->toArray());exit;
		
		$this->set(compact('invoiceBills','taxable_type','sale_invoice_rows','partyCustomerid'));
        $this->set('_serialize', ['invoiceBills']);
    }
	
	public function ajaxItemQuantity($itemId=null)
    {
	    $this->viewBuilder()->layout('');
		$company_id=$this->Auth->User('session_company_id');
		$stateDetails=$this->Auth->User('session_company');
		$location_id=$this->Auth->User('session_location_id');
		$state_id=$stateDetails->state_id;
		$items = $this->SalesInvoices->SalesInvoiceRows->Items->find()
					->where(['Items.company_id'=>$company_id, 'Items.id'=>$itemId])
					->contain(['Units'])->first();
					$itemUnit=$items->unit->name;
		
		$query = $this->SalesInvoices->SalesInvoiceRows->Items->ItemLedgers->find()->where(['ItemLedgers.company_id'=>$company_id]);
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

public function ajaxItemQuantityNew($itemId=null)
    {
	    $this->viewBuilder()->layout('');
		$company_id=$this->Auth->User('session_company_id');
		$stateDetails=$this->Auth->User('session_company');
		$location_id=$this->Auth->User('session_location_id');
		$state_id=$stateDetails->state_id;
		$items = $this->SalesInvoices->SalesInvoiceRows->Items->find()
					->where(['Items.company_id'=>$company_id, 'Items.id'=>$itemId])
					->contain(['Units'])->first();
					$itemUnit=$items->unit->name;
		
		$query = $this->SalesInvoices->SalesInvoiceRows->Items->ItemLedgers->find()->where(['ItemLedgers.company_id'=>$company_id]);
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
		->where(['ItemLedgers.item_id' => $itemId, 'ItemLedgers.company_id' => $company_id, 'ItemLedgers.location_id' => $location_id, 'ItemLedgers.sales_invoice_id' =>0])
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
     * Edit method
     *
     * @param string|null $id Sales Invoice id.
     * @return \Cake\Http\Response|null Redirects on successful edit, renders view otherwise.
     * @throws \Cake\Network\Exception\NotFoundException When record not found.
     */
    

    /**
     * Delete method
     *
     * @param string|null $id Sales Invoice id.
     * @return \Cake\Http\Response|null Redirects to index.
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function delete($id = null)
    {
        $this->request->allowMethod(['post', 'delete']);
        $salesInvoice = $this->SalesInvoices->get($id);
        if ($this->SalesInvoices->delete($salesInvoice)) {
            $this->Flash->success(__('The sales invoice has been deleted.'));
        } else {
            $this->Flash->error(__('The sales invoice could not be deleted. Please, try again.'));
        }

        return $this->redirect(['action' => 'index']);
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
			$where['SalesInvoices.transaction_date >=']=$From;
		}
		if(!empty($To)){
			$To=date("Y-m-d",strtotime($this->request->query('To')));
			$where['SalesInvoices.transaction_date <=']=$To;
		}
		
		$SalesInvoices =$this->SalesInvoices->find()->contain(['SalesInvoiceRows'=>['Items'=>['Units','StockGroups']]])->where($where)->where(['company_id'=>$company_id]);
		$hsn=[];
		$quantity=[];
		$taxable_value=[];
		$item_category=[];
		$total_value=[];
		$unit=[];
		$gst=[];
		
		foreach($SalesInvoices as $Invoice){ 
			foreach($Invoice->sales_invoice_rows as $invoice_row){  
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
	
	public function saleReturnIndex($id = null,$item_code = null)
    {
		$this->viewBuilder()->layout('index_layout');
		$company_id=$this->Auth->User('session_company_id');
		$stateDetails=$this->Auth->User('session_company');
		@$invoice_no=$this->request->query('invoice_no');
		@$item_code=$this->request->query('item_code');
		$financialYear_id=$this->Auth->User('financialYear_id');
		
		$sales_return="No";
		if(!empty(@$invoice_no)){ 
		$SalesInvoices = $this->SalesInvoices->find()
						->where(['SalesInvoices.voucher_no' =>$invoice_no,'SalesInvoices.company_id' => $company_id])
						->contain(['Companies', 'PartyLedgers', 'SalesLedgers']);
						
		//pr($SalesInvoice->party_ledger->name); 
		
		$sales_return="Yes";
		}	
		if(!empty($item_code)){
			$items = $this->SalesInvoices->SalesInvoiceRows->Items->find()->select(['id'])
					->where(['Items.company_id'=>$company_id,'Items.id'=>$item_code])->first();
				
				$item_id=@$items->id;
			
			
			
				$SalesInvoices = $this->SalesInvoices->find()->contain(['PartyLedgers'])->where(['SalesInvoices.company_id' => $company_id]);
				$SalesInvoices->innerJoinWith('SalesInvoiceRowDatas',function ($q) use($item_id,$company_id) {
					return $q->where(['SalesInvoiceRowDatas.item_id' =>$item_id]);
				});
				$SalesInvoices->where(['SalesInvoices.company_id' => $company_id])->contain(['SalesInvoiceRowDatas'=>function ($q) use($item_id,$company_id) {
					return $q->where(['SalesInvoiceRowDatas.item_id' =>$item_id]);
				}]);
				
				
			$sales_return="Yes";
		}
		$stockItems1=$this->SalesInvoices->SalesInvoiceRows->Items->find()->where(['Items.company_id'=>$company_id]);
		$stockItems=[];
		foreach($stockItems1 as $data){
		//	$merge=
		//$stockItems[]=['text'=>$data->name.'('.$data->item_code.')', 'value'=>$data->id];
		$stockItems[]=['text'=>$data->item_code.' ('.$data->name.') ', 'value'=>$data->id];
		}
		
		//pr($stockItems->toArray()); exit;
		$this->set(compact('sales_return','SalesInvoices','stockItems'));
		//exit;
	}
	
	public function cancel($id = null)
    {
		// $this->request->allowMethod(['post', 'delete']);
        $salesInvoice = $this->SalesInvoices->get($id);
		$company_id=$this->Auth->User('session_company_id');
		//pr($salesInvoice);exit;
		$salesInvoice->status='cancel';
        if ($this->SalesInvoices->save($salesInvoice)) {
			$receipt = $this->SalesInvoices->Receipts->find()->where(['Receipts.sales_invoice_id' => $id])->first();
			if($receipt){
			$receipt_id=$receipt->id;
			//$receipt_rows= $this->SalesInvoices->Receipts->ReceiptRows->find()->where(['ReceiptRows.receipt_id' => $receipt_id,'ReceiptRows.cr_dr' =>'Cr'])->first();
			//$receipt_row_id=$receipt_rows->id;
			//if($receipt_row_id){
				$refData1 = $this->SalesInvoices->Receipts->ReceiptRows->ReferenceDetails->query();
								$refData1->update()
								->set([
											'type' => 'New Ref'
											])
								->where(['ReferenceDetails.company_id'=>$company_id, 'ReferenceDetails.receipt_id'=>$receipt_id,'ReferenceDetails.type'=>'Against'])
								->execute();
				$deleteRefDetails = $this->SalesInvoices->Receipts->ReceiptRows->ReferenceDetails->query();
				$deleteRef = $deleteRefDetails->delete()
					->where(['sales_invoice_id' => $salesInvoice->id])
					->execute();
				$deleteAccountEntries = $this->SalesInvoices->AccountingEntries->query();
				$result = $deleteAccountEntries->delete()
				->where(['AccountingEntries.sales_invoice_id' => $id])
				->execute();
			}
			$deleteItemLedger = $this->SalesInvoices->ItemLedgers->query();
				$deleteResult = $deleteItemLedger->delete()
					->where(['sales_invoice_id' => $salesInvoice->id])
					->execute();
				$deleteAccountEntries = $this->SalesInvoices->AccountingEntries->query();
				$result = $deleteAccountEntries->delete()
				->where(['AccountingEntries.sales_invoice_id' => $id])
				->execute();
            $this->Flash->success(__('The Sales Invoice has been cancelled.'));
        } else {
            $this->Flash->error(__('The Sales Invoice could not be deleted. Please, try again.'));
        }

        return $this->redirect(['action' => 'index']);
    }
}
