<?php
namespace App\Controller;

use App\Controller\AppController;

/**
 * CreditNotes Controller
 *
 * @property \App\Model\Table\CreditNotesTable $CreditNotes
 *
 * @method \App\Model\Entity\CreditNote[] paginate($object = null, array $settings = [])
 */
class CreditNotesController extends AppController
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
            'contain' => ['Companies','CreditNoteRows'=>['Ledgers']],
			'limit' => 100
        ];
		if($search){
        $creditNotes = $this->paginate($this->CreditNotes->find()->where(['CreditNotes.company_id'=>$company_id,'CreditNotes.financial_year_id'=>$financialYear_id])->where([
		'OR' => [
            'CreditNotes.voucher_no' => $search,
          
		 ]]));

		}
		else{
		 $creditNotes = $this->paginate($this->CreditNotes->find()->where(['CreditNotes.company_id'=>$company_id,'CreditNotes.financial_year_id'=>$financialYear_id]));
		}
        $this->set(compact('creditNotes','search'));
        $this->set('_serialize', ['creditNotes']);
    }

    /**
     * View method
     *
     * @param string|null $id Credit Note id.
     * @return \Cake\Http\Response|void
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function view($id = null)
    {
		$this->viewBuilder()->layout('index_layout');
	    $company_id=$this->Auth->User('session_company_id');
		  $creditNotes = $this->CreditNotes->get($id, [
            'contain' => ['Companies', 'CreditNoteRows'=>['ReferenceDetails', 'Ledgers']]
        ]);

        $this->set(compact('creditNotes'));
        $this->set('_serialize', ['creditNotes']);
    }

    /**
     * Add method
     *
     * @return \Cake\Http\Response|null Redirects on successful add, renders view otherwise.
     */
 
 public function add()
    {   
		$this->viewBuilder()->layout('index_layout');
        $creditNote = $this->CreditNotes->newEntity();
		$company_id=$this->Auth->User('session_company_id');
		$financialYear_id=$this->Auth->User('financialYear_id');
        if ($this->request->is('post')) {
		$creditNote = $this->CreditNotes->patchEntity($creditNote, $this->request->getData(),['associated' => ['CreditNoteRows','CreditNoteRows.ReferenceDetails']]);
		$tdate=$this->request->data('transaction_date');
		$creditNote->transaction_date=date('Y-m-d',strtotime($tdate));
		$creditNote->financial_year_id =$financialYear_id;
		//pr($creditNote);exit;
		//transaction date for credit note code start here--
			foreach($creditNote->credit_note_rows as $credit_note_row)
			{
				if(!empty($credit_note_row->reference_details))
				{
					foreach($credit_note_row->reference_details as $reference_detail)
					{
						$reference_detail->transaction_date = $creditNote->transaction_date;
					}
				}
			}
			//pr($creditNote);exit;
			//transaction date for credit note code close here--
		 
            if ($this->CreditNotes->save($creditNote)) {
			
			foreach($creditNote->credit_note_rows as $credit_note_row)
				{
					$accountEntry = $this->CreditNotes->AccountingEntries->newEntity();
					$accountEntry->ledger_id                  = $credit_note_row->ledger_id;
					$accountEntry->debit                      = @$credit_note_row->debit;
					$accountEntry->credit                     = @$credit_note_row->credit;
					$accountEntry->transaction_date           = $creditNote->transaction_date;
					$accountEntry->company_id                 = $company_id;
					$accountEntry->credit_note_id                 = $creditNote->id;
					$accountEntry->credit_note_row_id             = $credit_note_row->id;
					$this->CreditNotes->AccountingEntries->save($accountEntry);
				}
			
                $this->Flash->success(__('The Credit Note has been saved.'));
                return $this->redirect(['action' => 'add']);
            }
            $this->Flash->error(__('The Credit Note could not be saved. Please, try again.'));
        }
		$Voucher_no = $this->CreditNotes->find()->select(['voucher_no'])->where(['company_id'=>$company_id,'financial_year_id'=>$financialYear_id])->order(['voucher_no' => 'DESC'])->first();
		if($Voucher_no)
		{
			$voucher_no=$Voucher_no->voucher_no+1;
		}
		else
		{
			$voucher_no=1;
		}
		
		//frst row options
		$bankParentGroups = $this->CreditNotes->CreditNoteRows->Ledgers->AccountingGroups->find()
						->where(['AccountingGroups.company_id'=>$company_id, 'AccountingGroups.bank'=>'1']);
						
		$bankGroups=[];
		
		foreach($bankParentGroups as $bankParentGroup)
		{
			$accountingGroups = $this->CreditNotes->CreditNoteRows->Ledgers->AccountingGroups
			->find('children', ['for' => $bankParentGroup->id])->toArray();
			$bankGroups[]=$bankParentGroup->id;
			foreach($accountingGroups as $accountingGroup){
				$bankGroups[]=$accountingGroup->id;
			}
		}
		
		//cash-in-hand group
		$cashParentGroups = $this->CreditNotes->CreditNoteRows->Ledgers->AccountingGroups->find()
						->where(['AccountingGroups.company_id'=>$company_id, 'AccountingGroups.cash'=>'1']);
						
		$cashGroups=[];
		
		foreach($cashParentGroups as $cashParentGroup)
		{
			$cashChildGroups = $this->CreditNotes->CreditNoteRows->Ledgers->AccountingGroups
			->find('children', ['for' => $cashParentGroup->id])->toArray();
			$cashGroups[]=$cashParentGroup->id;
			foreach($cashChildGroups as $cashChildGroup){
				$cashGroups[]=$cashChildGroup->id;
			}
		}
		
		$partyParentGroups = $this->CreditNotes->CreditNoteRows->Ledgers->AccountingGroups->find()
							->where(['AccountingGroups.company_id'=>$company_id, 'AccountingGroups.credit_note_first_row'=>1]);

		$partyGroups=[];
		
		foreach($partyParentGroups as $partyParentGroup)
		{
			
			$partyChildGroups = $this->CreditNotes->CreditNoteRows->Ledgers->AccountingGroups->find('children', ['for' => $partyParentGroup->id]);
			$partyGroups[]=$partyParentGroup->id;
			foreach($partyChildGroups as $partyChildGroup){
				$partyGroups[]=$partyChildGroup->id;
			}
		}
	
		$partyLedgers = $this->CreditNotes->CreditNoteRows->Ledgers->find()
		->where(['Ledgers.accounting_group_id IN' =>$partyGroups,'Ledgers.company_id'=>$company_id]);
		
		//$ledgers = $this->Payments->PaymentRows->Ledgers->find()->where(['company_id'=>$company_id]);
		foreach($partyLedgers as $ledger){
			if(in_array($ledger->accounting_group_id,$bankGroups)){
				$ledgerFirstOptions[]=['text' =>$ledger->name, 'value' => $ledger->id ,'open_window' => 'bank','bank_and_cash' => 'yes'];
			}
			else if($ledger->bill_to_bill_accounting == 'yes'){
				$ledgerFirstOptions[]=['text' =>$ledger->name, 'value' => $ledger->id,'open_window' => 'party','bank_and_cash' => 'no','default_days'=>$ledger->default_credit_days ];
			}
			else if(in_array($ledger->accounting_group_id,$cashGroups)){
				$ledgerFirstOptions[]=['text' =>$ledger->name, 'value' => $ledger->id ,'open_window' => 'no','bank_and_cash' => 'yes'];
			}
			else{
				$ledgerFirstOptions[]=['text' =>$ledger->name, 'value' => $ledger->id,'open_window' => 'no','bank_and_cash' => 'no' ];
			}
		}
		
		
		//2nd bank group
		$bankParentGroups = $this->CreditNotes->CreditNoteRows->Ledgers->AccountingGroups->find()
						->where(['AccountingGroups.company_id'=>$company_id, 'AccountingGroups.bank'=>'1']);
						
		$bankGroups=[];
		
		foreach($bankParentGroups as $bankParentGroup)
		{
			$accountingGroups = $this->CreditNotes->CreditNoteRows->Ledgers->AccountingGroups
			->find('children', ['for' => $bankParentGroup->id])->toArray();
			$bankGroups[]=$bankParentGroup->id;
			foreach($accountingGroups as $accountingGroup){
				$bankGroups[]=$accountingGroup->id;
			}
		}
		
		//cash-in-hand group
		$cashParentGroups = $this->CreditNotes->CreditNoteRows->Ledgers->AccountingGroups->find()
						->where(['AccountingGroups.company_id'=>$company_id, 'AccountingGroups.cash'=>'1']);
						
		$cashGroups=[];
		
		foreach($cashParentGroups as $cashParentGroup)
		{
			$cashChildGroups = $this->CreditNotes->CreditNoteRows->Ledgers->AccountingGroups
			->find('children', ['for' => $cashParentGroup->id])->toArray();
			$cashGroups[]=$cashParentGroup->id;
			foreach($cashChildGroups as $cashChildGroup){
				$cashGroups[]=$cashChildGroup->id;
			}
		}
		
		$partyParentGroups = $this->CreditNotes->CreditNoteRows->Ledgers->AccountingGroups->find()
							->where(['AccountingGroups.company_id'=>$company_id, 'AccountingGroups.credit_note_all_row'=>1]);

		$partyGroups=[];
		
		foreach($partyParentGroups as $partyParentGroup)
		{
			
			$partyChildGroups = $this->CreditNotes->CreditNoteRows->Ledgers->AccountingGroups->find('children', ['for' => $partyParentGroup->id]);
			$partyGroups[]=$partyParentGroup->id;
			foreach($partyChildGroups as $partyChildGroup){
				$partyGroups[]=$partyChildGroup->id;
			}
		}
	
		$partyLedgers = $this->CreditNotes->CreditNoteRows->Ledgers->find()
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
		
		
		$referenceDetails=$this->CreditNotes->CreditNoteRows->ReferenceDetails->find('list');
        $companies = $this->CreditNotes->Companies->find('list', ['limit' => 200]);
        $this->set(compact('creditNote', 'companies','voucher_no','ledgerOptions','company_id','referenceDetails','ledgerFirstOptions'));
        $this->set('_serialize', ['creditNote']);
    }

    /**
     * Edit method
     *
     * @param string|null $id CreditNote id.
     * @return \Cake\Http\Response|null Redirects on successful edit, renders view otherwise.
     * @throws \Cake\Network\Exception\NotFoundException When record not found.
     */
    
    /**
     * Edit method
     *
     * @param string|null $id CreditNote id.
     * @return \Cake\Http\Response|null Redirects on successful edit, renders view otherwise.
     * @throws \Cake\Network\Exception\NotFoundException When record not found.
     */
     public function edit($id = null)
    {
	
        $this->viewBuilder()->layout('index_layout');
        $creditNote = $this->CreditNotes->get($id, [
            'contain' => ['CreditNoteRows'=>['ReferenceDetails']]
        ]);
		$company_id=$this->Auth->User('session_company_id');

		$originalcreditNote=$creditNote;
        if ($this->request->is('put','patch','post')) {
		
	//GET ORIGINAL DATA AND DELETE REFERENCE DATA//
			$orignalCreditNote_ids=[];
			foreach($originalcreditNote->credit_note_rows as $originalCreditNote_rows){
				$orignalCreditNote_ids[]=$originalCreditNote_rows->id;
			}
			
		$this->CreditNotes->CreditNoteRows->ReferenceDetails->deleteAll(['ReferenceDetails.credit_note_row_id IN'=>$orignalCreditNote_ids]);
			$query_update = $this->CreditNotes->CreditNoteRows->query();
					$query_update->update()
					->set(['mode_of_payment' => '', 'cheque_no' => '', 'cheque_date' => ''])
					->where(['credit_note_id' => $creditNote->id])
					->execute();
				
			//GET ORIGINAL DATA AND DELETE REFERENCE DATA//
			//exit;
		
			 $creditNote = $this->CreditNotes->get($id, [
            'contain' => ['CreditNoteRows'=>['ReferenceDetails']]
        ]);
		
		
			$creditNote = $this->CreditNotes->patchEntity($creditNote, $this->request->getData(),['associated' => ['CreditNoteRows','CreditNoteRows.ReferenceDetails']]);
			$tdate=$this->request->data('transaction_date');
			$creditNote->transaction_date=date('Y-m-d',strtotime($tdate));
		 
		 
		 
			//transaction date for credit note code start here--
			foreach($creditNote->credit_note_rows as $credit_note_row)
			{
				if(!empty($credit_note_row->reference_details))
				{
					foreach($credit_note_row->reference_details as $reference_detail)
					{
						$reference_detail->transaction_date = $creditNote->transaction_date;
					}
				}
			}
			//transaction date for credit note code close here--
		//pr($creditNote);exit;
            if ($this->CreditNotes->save($creditNote)) {
			$query_delete = $this->CreditNotes->AccountingEntries->query();
					$query_delete->delete()
					->where(['credit_note_id' => $creditNote->id,'company_id'=>$company_id])
					->execute();
			
			foreach($creditNote->credit_note_rows as $credit_note_row)
				{
					$accountEntry = $this->CreditNotes->AccountingEntries->newEntity();
					$accountEntry->ledger_id                  = $credit_note_row->ledger_id;
					$accountEntry->debit                      = @$credit_note_row->debit;
					$accountEntry->credit                     = @$credit_note_row->credit;
					$accountEntry->transaction_date           = $creditNote->transaction_date;
					$accountEntry->company_id                 = $company_id;
					$accountEntry->credit_note_id             = $creditNote->id;
					$accountEntry->credit_note_row_id         = $credit_note_row->id;
					$this->CreditNotes->AccountingEntries->save($accountEntry);
				}

                $this->Flash->success(__('The Credit Note has been Update.'));
				$this->repairRef();

                return $this->redirect(['action' => 'index']);
            }
            $this->Flash->error(__('The Credit Note could not be saved. Please, try again.'));
        }
		
				
		$refDropDown =[];
		foreach($creditNote->credit_note_rows as $credit_note_row)
		{
			if(!empty($credit_note_row->reference_details))
			{
				$query = $this->CreditNotes->CreditNoteRows->ReferenceDetails->find();
				$query->select(['total_debit' => $query->func()->sum('ReferenceDetails.debit'),'total_credit' => $query->func()->sum('ReferenceDetails.credit')])
				->where(['ReferenceDetails.ledger_id'=>$credit_note_row->ledger_id,'ReferenceDetails.type !='=>'On Account'])
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
					if($referenceDetail->total_debit!=$referenceDetail->total_credit){
						$option[] =['text' =>$referenceDetail->ref_name.' ('.$bal.')', 'value' => $referenceDetail->ref_name];
						 
					}
				}
				$refDropDown[$credit_note_row->id] = $option;
			}
		}
		
		//frst row options
		$bankParentGroups = $this->CreditNotes->CreditNoteRows->Ledgers->AccountingGroups->find()
						->where(['AccountingGroups.company_id'=>$company_id, 'AccountingGroups.bank'=>'1']);
						
		$bankGroups=[];
		
		foreach($bankParentGroups as $bankParentGroup)
		{
			$accountingGroups = $this->CreditNotes->CreditNoteRows->Ledgers->AccountingGroups
			->find('children', ['for' => $bankParentGroup->id])->toArray();
			$bankGroups[]=$bankParentGroup->id;
			foreach($accountingGroups as $accountingGroup){
				$bankGroups[]=$accountingGroup->id;
			}
		}
		
		//cash-in-hand group
		$cashParentGroups = $this->CreditNotes->CreditNoteRows->Ledgers->AccountingGroups->find()
						->where(['AccountingGroups.company_id'=>$company_id, 'AccountingGroups.cash'=>'1']);
						
		$cashGroups=[];
		
		foreach($cashParentGroups as $cashParentGroup)
		{
			$cashChildGroups = $this->CreditNotes->CreditNoteRows->Ledgers->AccountingGroups
			->find('children', ['for' => $cashParentGroup->id])->toArray();
			$cashGroups[]=$cashParentGroup->id;
			foreach($cashChildGroups as $cashChildGroup){
				$cashGroups[]=$cashChildGroup->id;
			}
		}
		
		$partyParentGroups = $this->CreditNotes->CreditNoteRows->Ledgers->AccountingGroups->find()
							->where(['AccountingGroups.company_id'=>$company_id, 'AccountingGroups.credit_note_first_row'=>1]);

		$partyGroups=[];
		
		foreach($partyParentGroups as $partyParentGroup)
		{
			$partyChildGroups = $this->CreditNotes->CreditNoteRows->Ledgers->AccountingGroups->find('children', ['for' => $partyParentGroup->id]);
			$partyGroups[]=$partyParentGroup->id;
			foreach($partyChildGroups as $partyChildGroup){
				$partyGroups[]=$partyChildGroup->id;
			}
		}
	
		$partyLedgers = $this->CreditNotes->CreditNoteRows->Ledgers->find()
		->where(['Ledgers.accounting_group_id IN' =>$partyGroups,'Ledgers.company_id'=>$company_id]);
		
		//$ledgers = $this->Payments->PaymentRows->Ledgers->find()->where(['company_id'=>$company_id]);
		foreach($partyLedgers as $ledger){
			if(in_array($ledger->accounting_group_id,$bankGroups)){
				$ledgerFirstOptions[]=['text' =>$ledger->name, 'value' => $ledger->id ,'open_window' => 'bank','bank_and_cash' => 'yes'];
			}
			else if($ledger->bill_to_bill_accounting == 'yes'){
				$ledgerFirstOptions[]=['text' =>$ledger->name, 'value' => $ledger->id,'open_window' => 'party','bank_and_cash' => 'no','default_days'=>$ledger->default_credit_days];
			}
			else if(in_array($ledger->accounting_group_id,$cashGroups)){
				$ledgerFirstOptions[]=['text' =>$ledger->name, 'value' => $ledger->id ,'open_window' => 'no','bank_and_cash' => 'yes'];
			}
			else{
				$ledgerFirstOptions[]=['text' =>$ledger->name, 'value' => $ledger->id,'open_window' => 'no','bank_and_cash' => 'no' ];
			}
		}
		
		
		//2nd bank group
		$bankParentGroups = $this->CreditNotes->CreditNoteRows->Ledgers->AccountingGroups->find()
						->where(['AccountingGroups.company_id'=>$company_id, 'AccountingGroups.bank'=>'1']);
						
		$bankGroups=[];
		
		foreach($bankParentGroups as $bankParentGroup)
		{
			$accountingGroups = $this->CreditNotes->CreditNoteRows->Ledgers->AccountingGroups
			->find('children', ['for' => $bankParentGroup->id])->toArray();
			$bankGroups[]=$bankParentGroup->id;
			foreach($accountingGroups as $accountingGroup){
				$bankGroups[]=$accountingGroup->id;
			}
		}
		
		//cash-in-hand group
		$cashParentGroups = $this->CreditNotes->CreditNoteRows->Ledgers->AccountingGroups->find()
						->where(['AccountingGroups.company_id'=>$company_id, 'AccountingGroups.cash'=>'1']);
						
		$cashGroups=[];
		
		foreach($cashParentGroups as $cashParentGroup)
		{
			$cashChildGroups = $this->CreditNotes->CreditNoteRows->Ledgers->AccountingGroups
			->find('children', ['for' => $cashParentGroup->id])->toArray();
			$cashGroups[]=$cashParentGroup->id;
			foreach($cashChildGroups as $cashChildGroup){
				$cashGroups[]=$cashChildGroup->id;
			}
		}
		
		$partyParentGroups = $this->CreditNotes->CreditNoteRows->Ledgers->AccountingGroups->find()
							->where(['AccountingGroups.company_id'=>$company_id, 'AccountingGroups.credit_note_all_row'=>1]);

		$partyGroups=[];
		
		foreach($partyParentGroups as $partyParentGroup)
		{
			
			$partyChildGroups = $this->CreditNotes->CreditNoteRows->Ledgers->AccountingGroups->find('children', ['for' => $partyParentGroup->id]);
			$partyGroups[]=$partyParentGroup->id;
			foreach($partyChildGroups as $partyChildGroup){
				$partyGroups[]=$partyChildGroup->id;
			}
		}
	
		$partyLedgers = $this->CreditNotes->CreditNoteRows->Ledgers->find()
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
		$referenceDetails=$this->CreditNotes->CreditNoteRows->ReferenceDetails->find('list');
        $companies = $this->CreditNotes->Companies->find('list', ['limit' => 200]);
        $this->set(compact('creditNote', 'companies','voucher_no','ledgerOptions','company_id','referenceDetails','refDropDown', 'ledgerFirstOptions'));
        $this->set('_serialize', ['creditNote']);
    }
	

    /**
     * Delete method
     *
     * @param string|null $id Credit Note id.
     * @return \Cake\Http\Response|null Redirects to index.
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function delete($id = null)
    {
        $this->request->allowMethod(['post', 'delete']);
        $creditNote = $this->CreditNotes->get($id);
        if ($this->CreditNotes->delete($creditNote)) {
            $this->Flash->success(__('The credit note has been deleted.'));
        } else {
            $this->Flash->error(__('The credit note could not be deleted. Please, try again.'));
        }

        return $this->redirect(['action' => 'index']);
    }
	public function cancel($id = null)
    {
		// $this->request->allowMethod(['post', 'delete']);
        $creditNote = $this->CreditNotes->get($id, [
            'contain' => ['CreditNoteRows'=>['ReferenceDetails']]
        ]);
		$debit_note_row_ids=[];
		foreach($creditNote->credit_note_rows as $credit_note_row){
			$credit_note_row_ids[]=$credit_note_row->id;
		}
		$company_id=$this->Auth->User('session_company_id');
		$creditNote->status='cancel';
        if ($this->CreditNotes->save($creditNote)) {
			
				$deleteRefDetails = $this->CreditNotes->CreditNoteRows->ReferenceDetails->query();
				$deleteRef = $deleteRefDetails->delete()
					->where(['ReferenceDetails.credit_note_row_id IN' => $credit_note_row_ids])
					->execute();
				$deleteAccountEntries = $this->CreditNotes->AccountingEntries->query();
				$result = $deleteAccountEntries->delete()
				->where(['AccountingEntries.credit_note_id' => $creditNote->id])
				->execute();
			$this->Flash->success(__('The Credit Notes has been cancelled.'));
        } else {
            $this->Flash->error(__('The Credit Notes could not be cancelled. Please, try again.'));
        }

        return $this->redirect(['action' => 'index']);
    }
}
