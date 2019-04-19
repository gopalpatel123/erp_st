<?php
namespace App\Controller;
use App\Controller\AppController;
//	use Cake\View\Helper\HtmlHelper;
use Cake\View\Helper\FormHelper;


/**
 * Receipts Controller
 *
 * @property \App\Model\Table\ReceiptsTable $Receipts
 *
 * @method \App\Model\Entity\Receipt[] paginate($object = null, array $settings = [])
 */
class ReceiptsController extends AppController
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
            'contain' => ['Companies','ReceiptRows'=>['Ledgers']],
			'limit' => 100
        ];
		if($search){
        $receipts = $this->paginate($this->Receipts->find()->where(['Receipts.company_id'=>$company_id,'Receipts.financial_year_id'=>$financialYear_id])->where([
		'OR' => [
            'Receipts.voucher_no' => $search,
            //....
			'Receipts.transaction_date ' => date('Y-m-d',strtotime($search))
			//...
		 ]]));
		}else{
		  $receipts = $this->paginate($this->Receipts->find()->where(['Receipts.company_id'=>$company_id,'Receipts.financial_year_id'=>$financialYear_id]));	
		}
        $this->set(compact('receipts','search'));
        $this->set('_serialize', ['receipts']);
    }

    /**
     * View method
     *
     * @param string|null $id Receipt id.
     * @return \Cake\Http\Response|void
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function view($id = null)
    {   
		$this->viewBuilder()->layout('index_layout');
	    $company_id=$this->Auth->User('session_company_id');
        $receipts = $this->Receipts->find()->where(['Receipts.company_id'=>$company_id, 'Receipts.id'=>$id])
		->contain(['Companies', 'ReceiptRows'=>['ReferenceDetails', 'Ledgers']]);
	
        $this->set(compact('receipts'));
        $this->set('_serialize', ['receipts']);
    }

    /**
     * Add method
     *
     * @return \Cake\Http\Response|null Redirects on successful add, renders view otherwise.
     */
    public function add()
    {   
		$this->viewBuilder()->layout('index_layout');
        $receipt = $this->Receipts->newEntity();
		$company_id=$this->Auth->User('session_company_id');
		$financialYear_id=$this->Auth->User('financialYear_id');
        if ($this->request->is('post')) {

		$receipt = $this->Receipts->patchEntity($receipt, $this->request->getData(),['associated' => ['ReceiptRows','ReceiptRows.ReferenceDetails']]);
		$tdate=$this->request->data('transaction_date');
		$receipt->transaction_date=date('Y-m-d',strtotime($tdate));
		$receipt->financial_year_id =$financialYear_id;
		   //transaction date for receipt code start here--
			foreach($receipt->receipt_rows as $receipt_row)
			{
				if(!empty($receipt_row->reference_details))
				{
					foreach($receipt_row->reference_details as $reference_detail)
					{
						$reference_detail->transaction_date = $receipt->transaction_date;
					}
				}
			}
			//transaction date for receipt code close here-- 

            if ($this->Receipts->save($receipt)) {
			
			foreach($receipt->receipt_rows as $receipt_row)
				{
					$accountEntry = $this->Receipts->AccountingEntries->newEntity();
					$accountEntry->ledger_id                  = $receipt_row->ledger_id;
					$accountEntry->debit                      = @$receipt_row->debit;
					$accountEntry->credit                     = @$receipt_row->credit;
					$accountEntry->transaction_date           = $receipt->transaction_date;
					$accountEntry->company_id                 = $company_id;
					$accountEntry->receipt_id                 = $receipt->id;
					$accountEntry->receipt_row_id             = $receipt_row->id;
					$this->Receipts->AccountingEntries->save($accountEntry);
				}
			
                $this->Flash->success(__('The receipt has been saved.'));
                return $this->redirect(['action' => 'add']);
            }
            $this->Flash->error(__('The receipt could not be saved. Please, try again.'));
        }
		$Voucher_no = $this->Receipts->find()->select(['voucher_no'])->where(['company_id'=>$company_id,'financial_year_id'=>$financialYear_id])->order(['voucher_no' => 'DESC'])->first();
		if($Voucher_no)
		{
			$voucher_no=$Voucher_no->voucher_no+1;
		}
		else
		{
			$voucher_no=1;
		}
		
		//bank group
		$bankParentGroups = $this->Receipts->ReceiptRows->Ledgers->AccountingGroups->find()
						->where(['AccountingGroups.company_id'=>$company_id, 'AccountingGroups.bank'=>'1']);
						
		$bankGroups=[];
		
		foreach($bankParentGroups as $bankParentGroup)
		{
			$accountingGroups = $this->Receipts->ReceiptRows->Ledgers->AccountingGroups
			->find('children', ['for' => $bankParentGroup->id])->toArray();
			$bankGroups[]=$bankParentGroup->id;
			foreach($accountingGroups as $accountingGroup){
				$bankGroups[]=$accountingGroup->id;
			}
		}
		
		//cash-in-hand group
		$cashParentGroups = $this->Receipts->ReceiptRows->Ledgers->AccountingGroups->find()
						->where(['AccountingGroups.company_id'=>$company_id, 'AccountingGroups.cash'=>'1']);
						
		$cashGroups=[];
		
		foreach($cashParentGroups as $cashParentGroup)
		{
			$cashChildGroups = $this->Receipts->ReceiptRows->Ledgers->AccountingGroups
			->find('children', ['for' => $cashParentGroup->id])->toArray();
			$cashGroups[]=$cashParentGroup->id;
			foreach($cashChildGroups as $cashChildGroup){
				$cashGroups[]=$cashChildGroup->id;
			}
		}
		
		$partyParentGroups = $this->Receipts->ReceiptRows->Ledgers->AccountingGroups->find()
							->where(['AccountingGroups.company_id'=>$company_id, 'AccountingGroups.receipt_ledger'=>1]);

		$partyGroups=[];
		
		foreach($partyParentGroups as $partyParentGroup)
		{
			
			$partyChildGroups = $this->Receipts->ReceiptRows->Ledgers->AccountingGroups->find('children', ['for' => $partyParentGroup->id]);
			$partyGroups[]=$partyParentGroup->id;
			foreach($partyChildGroups as $partyChildGroup){
				$partyGroups[]=$partyChildGroup->id;
			}
		}
	
		$partyLedgers = $this->Receipts->ReceiptRows->Ledgers->find()
		->where(['Ledgers.accounting_group_id IN' =>$partyGroups,'Ledgers.company_id'=>$company_id]);
		
		//$ledgers = $this->Payments->PaymentRows->Ledgers->find()->where(['company_id'=>$company_id]);
		foreach($partyLedgers as $ledger){
			if(in_array($ledger->accounting_group_id,$bankGroups)){
				$ledgerOptions[]=['text' =>$ledger->name, 'value' => $ledger->id ,'open_window' => 'bank','bank_and_cash' => 'yes'];
			}
			else if($ledger->bill_to_bill_accounting == 'yes'){
				$ledgerOptions[]=['text' =>$ledger->name, 'value' => $ledger->id,'open_window' => 'party','bank_and_cash' => 'no','default_days'=>$ledger->default_credit_days];
			}
			else if(in_array($ledger->accounting_group_id,$cashGroups)){
				$ledgerOptions[]=['text' =>$ledger->name, 'value' => $ledger->id ,'open_window' => 'no','bank_and_cash' => 'yes'];
			}
			else{
				$ledgerOptions[]=['text' =>$ledger->name, 'value' => $ledger->id,'open_window' => 'no','bank_and_cash' => 'no' ];
			}
		}
		$referenceDetails=$this->Receipts->ReceiptRows->ReferenceDetails->find('list');
        $companies = $this->Receipts->Companies->find('list', ['limit' => 200]);
        $this->set(compact('receipt', 'companies','voucher_no','ledgerOptions','company_id','referenceDetails'));
        $this->set('_serialize', ['receipt']);
    }

    /**
     * Edit method
     *
     * @param string|null $id Receipt id.
     * @return \Cake\Http\Response|null Redirects on successful edit, renders view otherwise.
     * @throws \Cake\Network\Exception\NotFoundException When record not found.
     */
    
    /**
     * Edit method
     *
     * @param string|null $id Receipt id.
     * @return \Cake\Http\Response|null Redirects on successful edit, renders view otherwise.
     * @throws \Cake\Network\Exception\NotFoundException When record not found.
     */
     public function edit($id = null)
    {
	
        $this->viewBuilder()->layout('index_layout');
        $receipt = $this->Receipts->get($id, [
            'contain' => ['ReceiptRows'=>['ReferenceDetails']]
        ]);
		$company_id=$this->Auth->User('session_company_id');
		
		
		$originalReceipt=$receipt;
        if ($this->request->is('put','patch','post')) {
		
		//GET ORIGINAL DATA AND DELETE REFERENCE DATA//
			$orignalReceipt_ids=[];
			foreach($originalReceipt->receipt_rows as $originalReceipt_rows){
				$orignalReceipt_ids[]=$originalReceipt_rows->id;
			}
			$this->Receipts->ReceiptRows->ReferenceDetails->deleteAll(['ReferenceDetails.receipt_row_id IN'=>$orignalReceipt_ids]);
			
			
			//GET ORIGINAL DATA AND DELETE REFERENCE DATA//
			
			 $query_update = $this->Receipts->ReceiptRows->query();
					$query_update->update()
					->set(['mode_of_payment' => '', 'cheque_no' => '', 'cheque_date' => ''])
					->where(['receipt_id' => $receipt->id])
					->execute();
		$receipt = $this->Receipts->get($id, [
            'contain' => ['ReceiptRows'=>['ReferenceDetails']]
        ]);			 
		$receipt = $this->Receipts->patchEntity($receipt, $this->request->getData(),['associated' => ['ReceiptRows','ReceiptRows.ReferenceDetails']]);

		
		//transaction date for receipt code start here--
			foreach($receipt->receipt_rows as $receipt_row)
			{
				if(!empty($receipt_row->reference_details))
				{
					foreach($receipt_row->reference_details as $reference_detail)
					{
						$reference_detail->transaction_date = $receipt->transaction_date;
					}
				}
			}
			//transaction date for receipt code close here-- 
			//pr($receipt->toArray());
			//exit;
            if ($this->Receipts->save($receipt)) {
			$query_delete = $this->Receipts->AccountingEntries->query();
					$query_delete->delete()
					->where(['receipt_id' => $receipt->id,'company_id'=>$company_id])
					->execute();
			
			foreach($receipt->receipt_rows as $receipt_row)
				{
					$accountEntry = $this->Receipts->AccountingEntries->newEntity();
					$accountEntry->ledger_id                  = $receipt_row->ledger_id;
					$accountEntry->debit                      = @$receipt_row->debit;
					$accountEntry->credit                     = @$receipt_row->credit;
					$accountEntry->transaction_date           = $receipt->transaction_date;
					$accountEntry->company_id                 = $company_id;
					$accountEntry->receipt_id                 = $receipt->id;
					$accountEntry->receipt_row_id             = $receipt_row->id;
					$this->Receipts->AccountingEntries->save($accountEntry);
				}

                $this->Flash->success(__('The receipt has been saved.'));
				$this->repairRef();

                return $this->redirect(['action' => 'index']);
            }
            $this->Flash->error(__('The receipt could not be saved. Please, try again.'));
        }
		
		
		$refDropDown =[];
		foreach($receipt->receipt_rows as $receipt_row)
		{	
			if(!empty($receipt_row->reference_details))
			{	
				foreach($receipt_row->reference_details as $referenceDetailRows)
				{
					@$ref_details_name[]=$referenceDetailRows->ref_name;
				}
				
				$query = $this->Receipts->ReceiptRows->ReferenceDetails->find();
				$query->select(['total_debit' => $query->func()->sum('ReferenceDetails.debit'),'total_credit' => $query->func()->sum('ReferenceDetails.credit')])
				->where(['ReferenceDetails.ledger_id'=>$receipt_row->ledger_id,'ReferenceDetails.type !='=>'On Account'])
				->group(['ReferenceDetails.ref_name'])
				->autoFields(true);
				$referenceDetails=$query;
				$option=[];
				foreach($referenceDetails as $referenceDetail){
					$remider=$referenceDetail->total_debit-$referenceDetail->total_credit;
					if($remider>0){
						$bal=abs($remider).' Dr';
					}else if($remider<0){
						$bal=abs($remider).' Cr';
					}
					if($referenceDetail->total_debit!=$referenceDetail->total_credit || in_array($referenceDetail->ref_name,$ref_details_name)){
						$option[] =['text' =>$referenceDetail->ref_name.' ('.$bal.')', 'value' => $referenceDetail->ref_name];
					}
				}
				$refDropDown[$receipt_row->id] = $option;
			}
		}
		
		
		//bank group
		$bankParentGroups = $this->Receipts->ReceiptRows->Ledgers->AccountingGroups->find()
						->where(['AccountingGroups.company_id'=>$company_id, 'AccountingGroups.bank'=>'1']);
						
		$bankGroups=[];
		
		foreach($bankParentGroups as $bankParentGroup)
		{
			$accountingGroups = $this->Receipts->ReceiptRows->Ledgers->AccountingGroups
			->find('children', ['for' => $bankParentGroup->id])->toArray();
			$bankGroups[]=$bankParentGroup->id;
			foreach($accountingGroups as $accountingGroup){
				$bankGroups[]=$accountingGroup->id;
			}
		}
		
		//cash-in-hand group
		$cashParentGroups = $this->Receipts->ReceiptRows->Ledgers->AccountingGroups->find()
						->where(['AccountingGroups.company_id'=>$company_id, 'AccountingGroups.cash'=>'1']);
						
		$cashGroups=[];
		
		foreach($cashParentGroups as $cashParentGroup)
		{
			$cashChildGroups = $this->Receipts->ReceiptRows->Ledgers->AccountingGroups
			->find('children', ['for' => $cashParentGroup->id])->toArray();
			$cashGroups[]=$cashParentGroup->id;
			foreach($cashChildGroups as $cashChildGroup){
				$cashGroups[]=$cashChildGroup->id;
			}
		}
		
		$partyParentGroups = $this->Receipts->ReceiptRows->Ledgers->AccountingGroups->find()
							->where(['AccountingGroups.company_id'=>$company_id, 'AccountingGroups.receipt_ledger'=>1]);

		$partyGroups=[];
		
		foreach($partyParentGroups as $partyParentGroup)
		{
			
			$partyChildGroups = $this->Receipts->ReceiptRows->Ledgers->AccountingGroups->find('children', ['for' => $partyParentGroup->id]);
			$partyGroups[]=$partyParentGroup->id;
			foreach($partyChildGroups as $partyChildGroup){
				$partyGroups[]=$partyChildGroup->id;
			}
		}
	
		$partyLedgers = $this->Receipts->ReceiptRows->Ledgers->find()
		->where(['Ledgers.accounting_group_id IN' =>$partyGroups,'Ledgers.company_id'=>$company_id]);
		
		//$ledgers = $this->Payments->PaymentRows->Ledgers->find()->where(['company_id'=>$company_id]);
		foreach($partyLedgers as $ledger){
			if(in_array($ledger->accounting_group_id,$bankGroups)){
				$ledgerOptions[]=['text' =>$ledger->name, 'value' => $ledger->id ,'open_window' => 'bank','bank_and_cash' => 'yes'];
			}
			else if($ledger->bill_to_bill_accounting == 'yes'){
				$ledgerOptions[]=['text' =>$ledger->name, 'value' => $ledger->id,'open_window' => 'party','bank_and_cash' => 'no','default_days'=>$ledger->default_credit_days];
			}
			else if(in_array($ledger->accounting_group_id,$cashGroups)){
				$ledgerOptions[]=['text' =>$ledger->name, 'value' => $ledger->id ,'open_window' => 'no','bank_and_cash' => 'yes'];
			}
			else{
				$ledgerOptions[]=['text' =>$ledger->name, 'value' => $ledger->id,'open_window' => 'no','bank_and_cash' => 'no' ];
			}
		}
		//pr($receipt);
	//exit;
		$referenceDetails=$this->Receipts->ReceiptRows->ReferenceDetails->find('list');
        $companies = $this->Receipts->Companies->find('list', ['limit' => 200]);
        $this->set(compact('receipt', 'companies','voucher_no','ledgerOptions','company_id','referenceDetails','refDropDown'));
        $this->set('_serialize', ['receipt']);
    }

    /**
     * Edit method
     *
     * @param string|null $id Receipt id.
     * @return \Cake\Http\Response|null Redirects on successful edit, renders view otherwise.
     * @throws \Cake\Network\Exception\NotFoundException When record not found.
     */
  
	public function ajaxReferenceDetails($itemValue=null)
    {
		//$html = new HtmlHelper(new \Cake\View\View());
		$html = new FormHelper(new \Cake\View\View());
	    //$this->viewBuilder()->layout('');
		$company_id=$this->Auth->User('session_company_id');
		$query = $this->Receipts->ReceiptRows->ReferenceDetails->find();
		$totalInCase = $query->newExpr()
			->addCase(
				$query->newExpr()->add(['debit >' => 0]),
				$query->newExpr()->add(['debit']),
				'integer'
			);
		$totalOutCase = $query->newExpr()
			->addCase(
				$query->newExpr()->add(['credit >' => 0]),
				$query->newExpr()->add(['credit']),
				'integer'
			);
		$query->select([
			'total_in' => $query->func()->sum($totalInCase),
			'total_out' => $query->func()->sum($totalOutCase),'id','ledger_id'
		])
		->where(['ReferenceDetails.company_id'=>$company_id])
		->group('ref_name')
		->autoFields(true);
        $refdetails = ($query);
		
		$partyOptions=[];
		foreach($refdetails as $data)
		{
		           $ref_name=$data->ref_name;
		           $refeDebitBill=$data->total_in;
				   $refeCreditBill=$data->total_out;
				   $tot=$refeDebitBill-$refeCreditBill;
		$refOptions[]=['text' =>$ref_name.' - '.$tot, 'value' => $ref_name];
		}
		if(!empty($refdetails->toArray()) && $itemValue=='Agst Ref')
		{
			echo $html->input('ref_name', ['options'=>$refOptions,'label' => false,'class' => 'form-control input-medium ref_name','required'=>'required']); 
		}
		else if($itemValue!='Agst Ref')
		{
			echo $html->input('ref_name', ['label' => false,'class' => 'form-control input-medium ref_name','required'=>'required']); 
		}
		else
		{
			echo 'No, record found. Select different type.'; 
		}		
		exit;
}	

    /**
     * Delete method
     *
     * @param string|null $id Receipt id.
     * @return \Cake\Http\Response|null Redirects to index.
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function delete($id = null)
    {
        $this->request->allowMethod(['post', 'delete']);
        $receipt = $this->Receipts->get($id);
        if ($this->Receipts->delete($receipt)) {
            $this->Flash->success(__('The receipt has been deleted.'));
        } else {
            $this->Flash->error(__('The receipt could not be deleted. Please, try again.'));
        }

        return $this->redirect(['action' => 'index']);
    }
	public function cancel($id = null)
    {
		 $Receipts = $this->Receipts->get($id, [
            'contain' => ['ReceiptRows'=>['ReferenceDetails']]
        ]);
		$company_id=$this->Auth->User('session_company_id');
		$Receipts->status='cancel';
		$receipt_row_ids=[];
		foreach($Receipts->receipt_rows as $receipt_row){
			$receipt_row_ids[]=$receipt_row->id;
		}
		
        if ($this->Receipts->save($Receipts)) {
			$deleteRefDetails = $this->Receipts->ReceiptRows->ReferenceDetails->query();
				$deleteRef = $deleteRefDetails->delete()
					->where(['ReferenceDetails.receipt_row_id IN' => $receipt_row_ids])
					->execute();
				$deleteAccountEntries = $this->Receipts->AccountingEntries->query();
				$result = $deleteAccountEntries->delete()
				->where(['AccountingEntries.receipt_id' => $Receipts->id])
				->execute();
				
            $this->Flash->success(__('The Receipt has been cancelled.'));
        } else {
            $this->Flash->error(__('The Receipt could not be deleted. Please, try again.'));
        }
        return $this->redirect(['action' => 'index']);
    }
}
