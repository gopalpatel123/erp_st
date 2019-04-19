<?php
namespace App\Controller;

use App\Controller\AppController;

/**
 * Payments Controller
 *
 * @property \App\Model\Table\PaymentsTable $Payments
 *
 * @method \App\Model\Entity\Payment[] paginate($object = null, array $settings = [])
 */
class PaymentsController extends AppController
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
            'contain' => ['Companies', 'PaymentRows'=>['Ledgers']]
        ];
		if($search){
        $payments = $this->paginate($this->Payments->find()->where(['Payments.company_id'=>$company_id,'Payments.financial_year_id'=>$financialYear_id])->where([
		'OR' => [
            'Payments.voucher_no' => $search,
            //....
			'Payments.transaction_date ' => date('Y-m-d',strtotime($search))
			//...
		 ]]));
		} else {
		 $payments = $this->paginate($this->Payments->find()->where(['Payments.company_id'=>$company_id,'Payments.financial_year_id'=>$financialYear_id]));
		}
        $this->set(compact('payments','search'));
        $this->set('_serialize', ['payments']);
    }

    /**
     * View method
     *
     * @param string|null $id Payment id.
     * @return \Cake\Http\Response|void
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function view($id = null)
    {	
		$this->viewBuilder()->layout('index_layout');
		$company_id=$this->Auth->User('session_company_id');
        $payment = $this->Payments->get($id, [
            'contain' => ['Companies', 'PaymentRows'=>['ReferenceDetails', 'Ledgers']]
        ]);

        $this->set('payment', $payment);
        $this->set('_serialize', ['payment']);
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
		$financialYear_id=$this->Auth->User('financialYear_id');
        $this->request->data['company_id'] =$company_id;
		
        $payment = $this->Payments->newEntity();
		
		if ($this->request->is('post')) {
			
			//$this->request->data['transaction_date'] = date("Y-m-d",strtotime($this->request->getData()['transaction_date']));
			$Voucher = $this->Payments->find()->select(['voucher_no'])->where(['company_id'=>$company_id,'financial_year_id'=>$financialYear_id])->order(['voucher_no' => 'DESC'])->first();
			if($Voucher)
			{
				$payment->voucher_no = $Voucher->voucher_no+1;
			}
			else
			{
				$payment->voucher_no = 1;
			} 
			$payment = $this->Payments->patchEntity($payment, $this->request->getData(), [
							'associated' => ['PaymentRows','PaymentRows.ReferenceDetails']
						]);
			$payment->financial_year_id =$financialYear_id;
			//transaction date for payment code start here--
			foreach($payment->payment_rows as $payment_row)
			{
				if(!empty($payment_row->reference_details))
				{
					foreach($payment_row->reference_details as $reference_detail)
					{
						$reference_detail->transaction_date = $payment->transaction_date;
					}
				}
			}
			//transaction date for payment code close here-- 
			
			if ($this->Payments->save($payment)) {
			foreach($payment->payment_rows as $payment_row)
				{
					$accountEntry = $this->Payments->AccountingEntries->newEntity();
					$accountEntry->ledger_id                  = $payment_row->ledger_id;
					$accountEntry->debit                      = @$payment_row->debit;
					$accountEntry->credit                     = @$payment_row->credit;
					$accountEntry->transaction_date           = $payment->transaction_date;
					$accountEntry->company_id                 = $company_id;
					$accountEntry->payment_id                 = $payment->id;
					$accountEntry->payment_row_id             = $payment_row->id;
					$this->Payments->AccountingEntries->save($accountEntry);
				}
				$this->Flash->success(__('The payment has been saved.'));

				return $this->redirect(['action' => 'add']);
			}
			$this->Flash->error(__('The payment could not be saved. Please, try again.'));
		}
		$Voucher = $this->Payments->find()->select(['voucher_no'])->where(['company_id'=>$company_id,'financial_year_id'=>$financialYear_id])->order(['voucher_no' => 'DESC'])->first();
		if($Voucher)
		{
			$voucher_no=$Voucher->voucher_no+1;
		}
		else
		{ 
			$voucher_no=1;
		} 		
		//bank group
		$bankParentGroups = $this->Payments->PaymentRows->Ledgers->AccountingGroups->find()
						->where(['AccountingGroups.company_id'=>$company_id, 'AccountingGroups.bank'=>'1']);
						
		$bankGroups=[];
		
		foreach($bankParentGroups as $bankParentGroup)
		{
			$accountingGroups = $this->Payments->PaymentRows->Ledgers->AccountingGroups
			->find('children', ['for' => $bankParentGroup->id])->toArray();
			$bankGroups[]=$bankParentGroup->id;
			foreach($accountingGroups as $accountingGroup){
				$bankGroups[]=$accountingGroup->id;
			}
		}
		
		//cash-in-hand group
		$cashParentGroups = $this->Payments->PaymentRows->Ledgers->AccountingGroups->find()
						->where(['AccountingGroups.company_id'=>$company_id, 'AccountingGroups.cash'=>'1']);
						
		$cashGroups=[];
		
		foreach($cashParentGroups as $cashParentGroup)
		{
			$cashChildGroups = $this->Payments->PaymentRows->Ledgers->AccountingGroups
			->find('children', ['for' => $cashParentGroup->id])->toArray();
			$cashGroups[]=$cashParentGroup->id;
			foreach($cashChildGroups as $cashChildGroup){
				$cashGroups[]=$cashChildGroup->id;
			}
		}
		
		$partyParentGroups = $this->Payments->PaymentRows->Ledgers->AccountingGroups->find()
							->where(['AccountingGroups.company_id'=>$company_id, 'AccountingGroups.payment_ledger'=>1]);

		$partyGroups=[];
		
		foreach($partyParentGroups as $partyParentGroup)
		{
			
			$partyChildGroups = $this->Payments->PaymentRows->Ledgers->AccountingGroups->find('children', ['for' => $partyParentGroup->id]);
			$partyGroups[]=$partyParentGroup->id;
			foreach($partyChildGroups as $partyChildGroup){
				$partyGroups[]=$partyChildGroup->id;
			}
		}
	
		$partyLedgers = $this->Payments->PaymentRows->Ledgers->find()
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
		
		//$referenceDetails=$this->Payments->PaymentRows->ReferenceDetails->find('list');
		
		$this->set(compact('payment', 'company_id','voucher_no','ledgerOptions', 'referenceDetails'));
		$this->set('_serialize', ['payment']);
    }

    /**
     * Edit method
     *
     * @param string|null $id Payment id.
     * @return \Cake\Http\Response|null Redirects on successful edit, renders view otherwise.
     * @throws \Cake\Network\Exception\NotFoundException When record not found.
     */
    public function edit($id = null)
    {
		$this->viewBuilder()->layout('index_layout');
		$company_id=$this->Auth->User('session_company_id');
        $this->request->data['company_id'] =$company_id;
        $payment = $this->Payments->get($id, [
            'contain' => ['PaymentRows'=>['ReferenceDetails']]
        ]);
		
		
		$originalPayment=$payment;
        if ($this->request->is(['patch', 'post', 'put'])) {
		
		//GET ORIGINAL DATA AND DELETE REFERENCE DATA//
			$orignalPayment_ids=[];
			foreach($originalPayment->payment_rows as $originalPayment_rows){
				$orignalPayment_ids[]=$originalPayment_rows->id;
			}
			$this->Payments->PaymentRows->ReferenceDetails->deleteAll(['ReferenceDetails.payment_row_id IN'=>$orignalPayment_ids]);
			$query_update = $this->Payments->PaymentRows->query();
					$query_update->update()
					->set(['mode_of_payment' => '', 'cheque_no' => '', 'cheque_date' => ''])
					->where(['payment_id' => $payment->id])
					->execute();
			//GET ORIGINAL DATA AND DELETE REFERENCE DATA//
			 $payment = $this->Payments->get($id, [
            'contain' => ['PaymentRows'=>['ReferenceDetails']]
			]);
		
			$payment = $this->Payments->patchEntity($payment, $this->request->getData(), [
							'associated' => ['PaymentRows','PaymentRows.ReferenceDetails']
						]);

			//transaction date for payment code start here--
			foreach($payment->payment_rows as $payment_row)
			{
				if(!empty($payment_row->reference_details))
				{
					foreach($payment_row->reference_details as $reference_detail)
					{
						$reference_detail->transaction_date = $payment->transaction_date;
					}
				}
			}
            if ($this->Payments->save($payment)) {
			
			$query_delete = $this->Payments->AccountingEntries->query();
					$query_delete->delete()
					->where(['payment_id' => $payment->id,'company_id'=>$company_id])
					->execute();
					
			foreach($payment->payment_rows as $payment_row)
				{
					$accountEntry = $this->Payments->AccountingEntries->newEntity();
					$accountEntry->ledger_id                  = $payment_row->ledger_id;
					$accountEntry->debit                      = @$payment_row->debit;
					$accountEntry->credit                     = @$payment_row->credit;
					$accountEntry->transaction_date           = $payment->transaction_date;
					$accountEntry->company_id                 = $company_id;
					$accountEntry->payment_id                 = $payment->id;
					$accountEntry->payment_row_id             = $payment_row->id;
					$this->Payments->AccountingEntries->save($accountEntry);
				}
				
				
                $this->Flash->success(__('The payment has been updated.'));
				$this->repairRef();

                return $this->redirect(['action' => 'index']);
            }
            $this->Flash->error(__('The payment could not be saved. Please, try again.'));
        }
		
		$refDropDown =[];
		foreach($payment->payment_rows as $payment_row)
		{
			if(!empty($payment_row->reference_details))
			{
				foreach($payment_row->reference_details as $referenceDetailRows)
				{
					@$ref_details_name[]=$referenceDetailRows->ref_name;
				}
				$query = $this->Payments->PaymentRows->ReferenceDetails->find();
				$query->select(['total_debit' => $query->func()->sum('ReferenceDetails.debit'),'total_credit' => $query->func()->sum('ReferenceDetails.credit')])
				->where(['ReferenceDetails.ledger_id'=>$payment_row->ledger_id,'ReferenceDetails.type !='=>'On Account'])
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
					if($referenceDetail->total_debit!=$referenceDetail->total_credit  || in_array($referenceDetail->ref_name,$ref_details_name)){
						$option[] =['text' =>$referenceDetail->ref_name.' ('.$bal.')', 'value' => $referenceDetail->ref_name];
					}
				}
				$refDropDown[$payment_row->id] = $option;
			}
		}
       $Voucher = $this->Payments->find()->select(['voucher_no'])->where(['company_id'=>$company_id])->order(['voucher_no' => 'DESC'])->first();
		if($Voucher)
		{
			$voucher_no=$Voucher->voucher_no+1;
		}
		else
		{ 
			$voucher_no=1;
		} 
		
		//bank group
		$bankParentGroups = $this->Payments->PaymentRows->Ledgers->AccountingGroups->find()
						->where(['AccountingGroups.company_id'=>$company_id, 'AccountingGroups.bank'=>'1']);
						
		$bankGroups=[];
		
		foreach($bankParentGroups as $bankParentGroup)
		{
			$accountingGroups = $this->Payments->PaymentRows->Ledgers->AccountingGroups
			->find('children', ['for' => $bankParentGroup->id])->toArray();
			$bankGroups[]=$bankParentGroup->id;
			foreach($accountingGroups as $accountingGroup){
				$bankGroups[]=$accountingGroup->id;
			}
		}
		
		//cash-in-hand group
		$cashParentGroups = $this->Payments->PaymentRows->Ledgers->AccountingGroups->find()
						->where(['AccountingGroups.company_id'=>$company_id, 'AccountingGroups.cash'=>'1']);
						
		$cashGroups=[];
		
		foreach($cashParentGroups as $cashParentGroup)
		{
			$cashChildGroups = $this->Payments->PaymentRows->Ledgers->AccountingGroups
			->find('children', ['for' => $cashParentGroup->id])->toArray();
			$cashGroups[]=$cashParentGroup->id;
			foreach($cashChildGroups as $cashChildGroup){
				$cashGroups[]=$cashChildGroup->id;
			}
		}
		
		$partyParentGroups = $this->Payments->PaymentRows->Ledgers->AccountingGroups->find()
							->where(['AccountingGroups.company_id'=>$company_id, 'AccountingGroups.payment_ledger'=>1]);

		$partyGroups=[];
		
		foreach($partyParentGroups as $partyParentGroup)
		{
			
			$partyChildGroups = $this->Payments->PaymentRows->Ledgers->AccountingGroups->find('children', ['for' => $partyParentGroup->id]);
			$partyGroups[]=$partyParentGroup->id;
			foreach($partyChildGroups as $partyChildGroup){
				$partyGroups[]=$partyChildGroup->id;
			}
		}
	
		$partyLedgers = $this->Payments->PaymentRows->Ledgers->find()
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
		
		
		//$referenceDetails=$this->Payments->PaymentRows->ReferenceDetails->find('list');
		$this->set(compact('payment', 'company_id','voucher_no','ledgerOptions', 'referenceDetails','refDropDown'));
        $this->set('_serialize', ['payment']);
    }

    /**
     * Delete method
     *
     * @param string|null $id Payment id.
     * @return \Cake\Http\Response|null Redirects to index.
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function delete($id = null)
    {
        $this->request->allowMethod(['post', 'delete']);
        $payment = $this->Payments->get($id);
        if ($this->Payments->delete($payment)) {
            $this->Flash->success(__('The payment has been deleted.'));
        } else {
            $this->Flash->error(__('The payment could not be deleted. Please, try again.'));
        }

        return $this->redirect(['action' => 'index']);
    }
	public function cancel($id = null)
    {
		 $Payments = $this->Payments->get($id, [
            'contain' => ['PaymentRows'=>['ReferenceDetails']]
        ]);
		$company_id=$this->Auth->User('session_company_id');
		$Payments->status='cancel';
		$payment_row_ids=[];
		foreach($Payments->payment_rows as $payment_row){
			$payment_row_ids[]=$payment_row->id;
		}
		
        if ($this->Payments->save($Payments)) {
			$deleteRefDetails = $this->Payments->PaymentRows->ReferenceDetails->query();
				$deleteRef = $deleteRefDetails->delete()
					->where(['ReferenceDetails.payment_row_id IN' => $payment_row_ids])
					->execute();
				$deleteAccountEntries = $this->Payments->AccountingEntries->query();
				$result = $deleteAccountEntries->delete()
				->where(['AccountingEntries.payment_id' => $Payments->id])
				->execute();
				
            $this->Flash->success(__('The Payment has been cancelled.'));
        } else {
            $this->Flash->error(__('The Payment could not be deleted. Please, try again.'));
        }

        return $this->redirect(['action' => 'index']);
    }
}
