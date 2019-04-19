<?php
namespace App\Controller;

use App\Controller\AppController;

/**
 * JournalVouchers Controller
 *
 * @property \App\Model\Table\JournalVouchersTable $JournalVouchers
 *
 * @method \App\Model\Entity\JournalVoucher[] paginate($object = null, array $settings = [])
 */
class JournalVouchersController extends AppController
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
            'contain' => ['Companies','JournalVoucherRows'=>['Ledgers']],
			'limit' => 100
        ];
		if($search){
        $journalVouchers = $this->paginate($this->JournalVouchers->find()->where(['JournalVouchers.company_id'=>$company_id,'JournalVouchers.financial_year_id'=>$financialYear_id])->where([
		'OR' => [
            'JournalVouchers.voucher_no' => $search,
            //....
			'JournalVouchers.reference_no LIKE' => '%'.$search.'%',
				//...
			'JournalVouchers.transaction_date ' => date('Y-m-d',strtotime($search))

		 ]]));
		} else {
        $journalVouchers = $this->paginate($this->JournalVouchers->find()->where(['JournalVouchers.company_id'=>$company_id,'JournalVouchers.financial_year_id'=>$financialYear_id]));
		}
        $this->set(compact('journalVouchers','search'));
        $this->set('_serialize', ['journalVouchers']);
    }

    /**
     * View method
     *
     * @param string|null $id Journal Voucher id.
     * @return \Cake\Http\Response|void
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function view($id = null)
    {
		$this->viewBuilder()->layout('index_layout');
        $journalVoucher = $this->JournalVouchers->get($id, [
            'contain' => ['Companies','JournalVoucherRows'=>['Ledgers','ReferenceDetails']]
        ]);

        $this->set('journalVoucher', $journalVoucher);
        $this->set('_serialize', ['journalVoucher']);
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
        $journalVoucher = $this->JournalVouchers->newEntity();
        if ($this->request->is('post')) {
			$journalVoucher = $this->JournalVouchers->patchEntity($journalVoucher, $this->request->getData());
			$Voucher_no = $this->JournalVouchers->find()->select(['voucher_no'])->where(['company_id'=>$company_id,'financial_year_id'=>$financialYear_id])->order(['voucher_no' => 'DESC'])->first();
			if($Voucher_no)
			{
				$journalVoucher->voucher_no = $Voucher_no->voucher_no+1;
			}
			else
			{
				$journalVoucher->voucher_no = 1;
			} 
			$journalVoucher->company_id            = $company_id;
            
			$journalVoucher = $this->JournalVouchers->patchEntity($journalVoucher, $this->request->getData(), [
							'associated' => ['JournalVoucherRows','JournalVoucherRows.ReferenceDetails']
						]);
			$journalVoucher->transaction_date      = date("Y-m-d",strtotime($journalVoucher->transaction_date));
			$journalVoucher->financial_year_id =$financialYear_id;
			foreach($journalVoucher->journal_voucher_rows as $journal_voucher_row)
			{
				if(!empty($journal_voucher_row->reference_details))
				{
					foreach($journal_voucher_row->reference_details as $reference_detail)
					{
						$reference_detail->transaction_date = $journalVoucher->transaction_date;
					}
				}
			}
            if ($this->JournalVouchers->save($journalVoucher)) 
			{
				foreach($journalVoucher->journal_voucher_rows as $journal_voucher_row)
				{
					$accountEntry = $this->JournalVouchers->AccountingEntries->newEntity();
					$accountEntry->ledger_id                  = $journal_voucher_row->ledger_id;
					$accountEntry->debit                      = $journal_voucher_row->debit;
					$accountEntry->credit                     = $journal_voucher_row->credit;
					$accountEntry->transaction_date           = $journalVoucher->transaction_date;
					$accountEntry->company_id                 = $company_id;
					$accountEntry->journal_voucher_id         = $journalVoucher->id;
					$accountEntry->journal_voucher_row_id     = $journal_voucher_row->id;
					
					$this->JournalVouchers->AccountingEntries->save($accountEntry);
				}
                $this->Flash->success(__('The journal voucher has been saved.'));

                return $this->redirect(['action' => 'add']);
            }
            $this->Flash->error(__('The journal voucher could not be saved. Please, try again.'));
        }
		$Voucher_no = $this->JournalVouchers->find()->select(['voucher_no'])->where(['company_id'=>$company_id,'financial_year_id'=>$financialYear_id])->order(['voucher_no' => 'DESC'])->first();
		if($Voucher_no)
		{
			$voucher_no=$Voucher_no->voucher_no+1;
		}
		else
		{
			$voucher_no=1;
		} 
		
		$bankParentGroups = $this->JournalVouchers->JournalVoucherRows->Ledgers->AccountingGroups->find()
						->where(['AccountingGroups.company_id'=>$company_id, 'AccountingGroups.bank'=>'1']);
						
		$bankGroups=[];
		
		foreach($bankParentGroups as $bankParentGroup)
		{
			$accountingGroups = $this->JournalVouchers->JournalVoucherRows->Ledgers->AccountingGroups
			->find('children', ['for' => $bankParentGroup->id])->toArray();
			$bankGroups[]=$bankParentGroup->id;
			foreach($accountingGroups as $accountingGroup){
				$bankGroups[]=$accountingGroup->id;
			}
		}
		
		$accountGroupParents = $this->JournalVouchers->JournalVoucherRows->Ledgers->AccountingGroups->find()->where(['journal_voucher_ledger'=>1,'company_id'=>$company_id]); 
		$CreditGroups=[];
		foreach($accountGroupParents as $accountGroupParent)
		{ 
			$ChildGroups = $this->JournalVouchers->JournalVoucherRows->Ledgers->AccountingGroups->find('children', ['for' =>$accountGroupParent->id])->toArray();
			
			$CreditGroups[]=$accountGroupParent->id;
			foreach($ChildGroups as $ChildGroup)
			{
				$CreditGroups[]=$ChildGroup->id;
			}
		}
		$AllCreditledgers = $this->JournalVouchers->JournalVoucherRows->Ledgers->find()->where(['Ledgers.accounting_group_id IN' =>$CreditGroups]);
		
		$ledgers=[];
		foreach($AllCreditledgers as $AllCreditledger){
		if(in_array($AllCreditledger->accounting_group_id,$bankGroups)){
				$ledgers[]=['text' =>$AllCreditledger->name, 'value' => $AllCreditledger->id ,'open_window' => 'bank'];
			}
			else if($AllCreditledger->bill_to_bill_accounting == 'yes'){
				$ledgers[]=['text' =>$AllCreditledger->name, 'value' => $AllCreditledger->id,'open_window' => 'party','default_days'=>$AllCreditledger->default_credit_days];
			}
			else{
				$ledgers[]=['text' =>$AllCreditledger->name, 'value' => $AllCreditledger->id,'open_window' => 'no' ];
			}
		}
		
        $this->set(compact('journalVoucher', 'company_id','ledgers','voucher_no'));
        $this->set('_serialize', ['journalVoucher']);
    }

	
    /**
     * Edit method
     *
     * @param string|null $id Journal Voucher id.
     * @return \Cake\Http\Response|null Redirects on successful edit, renders view otherwise.
     * @throws \Cake\Network\Exception\NotFoundException When record not found.
     */
    public function edit($id = null)
    {
		$this->viewBuilder()->layout('index_layout');
		$company_id=$this->Auth->User('session_company_id');
        $journalVoucher = $this->JournalVouchers->get($id, [
            'contain' => ['JournalVoucherRows'=>['Ledgers','ReferenceDetails']]
        ]);
		$originalJournalVoucher=$journalVoucher;
        if ($this->request->is(['patch', 'post', 'put'])) {
			//GET ORIGINAL DATA AND DELETE REFERENCE DATA//
			$orignalJournal_voucher_row_ids=[];
			foreach($originalJournalVoucher->journal_voucher_rows as $originalJournal_voucher_rows){
				$orignalJournal_voucher_row_ids[]=$originalJournal_voucher_rows->id;
			}
			$this->JournalVouchers->JournalVoucherRows->ReferenceDetails->deleteAll(['ReferenceDetails.journal_voucher_row_id IN'=>$orignalJournal_voucher_row_ids]);
			//GET ORIGINAL DATA AND DELETE REFERENCE DATA//
			
            $journalVoucher = $this->JournalVouchers->patchEntity($journalVoucher, $this->request->getData());
			
			$journalVoucher = $this->JournalVouchers->patchEntity($journalVoucher, $this->request->getData(), [
							'associated' => ['JournalVoucherRows','JournalVoucherRows.ReferenceDetails']
						]);
			$journalVoucher->transaction_date      = date("Y-m-d",strtotime($journalVoucher->transaction_date));
			//pr($journalVoucher);exit;
			foreach($journalVoucher->journal_voucher_rows as $journal_voucher_row)
			{
				if(!empty($journal_voucher_row->reference_details))
				{
					foreach($journal_voucher_row->reference_details as $reference_detail)
					{
						$reference_detail->transaction_date = $journalVoucher->transaction_date;
					}
				}
			}
            if ($this->JournalVouchers->save($journalVoucher)) {
				$query_delete = $this->JournalVouchers->AccountingEntries->query();
					$query_delete->delete()
					->where(['journal_voucher_id' => $journalVoucher->id,'company_id'=>$company_id])
					->execute();
				foreach($journalVoucher->journal_voucher_rows as $journal_voucher_row)
				{
					$accountEntry = $this->JournalVouchers->AccountingEntries->newEntity();
					$accountEntry->ledger_id                  = $journal_voucher_row->ledger_id;
					$accountEntry->debit                      = $journal_voucher_row->debit;
					$accountEntry->credit                     = $journal_voucher_row->credit;
					$accountEntry->transaction_date           = $journalVoucher->transaction_date;
					$accountEntry->company_id                 = $company_id;
					$accountEntry->journal_voucher_id         = $journalVoucher->id;
					$accountEntry->journal_voucher_row_id     = $journal_voucher_row->id;
					
					$this->JournalVouchers->AccountingEntries->save($accountEntry);
				}
                $this->Flash->success(__('The journal voucher has been saved.'));
				$this->repairRef();
                return $this->redirect(['action' => 'index']);
            }
            $this->Flash->error(__('The journal voucher could not be saved. Please, try again.'));
        }
		
		$refDropDown =[];
		foreach($journalVoucher->journal_voucher_rows as $journal_voucher_row)
		{
			if(!empty($journal_voucher_row->reference_details))
			{
				foreach($journal_voucher_row->reference_details as $referenceDetailRows)
				{
					@$ref_details_name[]=$referenceDetailRows->ref_name;
				}
				$query = $this->JournalVouchers->JournalVoucherRows->ReferenceDetails->find();
				$query->select(['total_debit' => $query->func()->sum('ReferenceDetails.debit'),'total_credit' => $query->func()->sum('ReferenceDetails.credit')])
				->where(['ReferenceDetails.ledger_id'=>$journal_voucher_row->ledger_id,'ReferenceDetails.type !='=>'On Account'])
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
						$option[] = ['text' =>$referenceDetail->ref_name.' ('.$bal.')', 'value' => $referenceDetail->ref_name];
						 
					}
				}
				
				$refDropDown[$journal_voucher_row->id] = $option;
			}
		}
		
		$bankParentGroups = $this->JournalVouchers->JournalVoucherRows->Ledgers->AccountingGroups->find()
						->where(['AccountingGroups.company_id'=>$company_id, 'AccountingGroups.bank'=>'1']);
						
		$bankGroups=[];
		
		foreach($bankParentGroups as $bankParentGroup)
		{
			$accountingGroups = $this->JournalVouchers->JournalVoucherRows->Ledgers->AccountingGroups
			->find('children', ['for' => $bankParentGroup->id])->toArray();
			$bankGroups[]=$bankParentGroup->id;
			foreach($accountingGroups as $accountingGroup){
				$bankGroups[]=$accountingGroup->id;
			}
		}
		
		$accountGroupParents = $this->JournalVouchers->JournalVoucherRows->Ledgers->AccountingGroups->find()->where(['journal_voucher_ledger'=>1,'company_id'=>$company_id]); 
		$CreditGroups=[];
		foreach($accountGroupParents as $accountGroupParent)
		{ 
			$ChildGroups = $this->JournalVouchers->JournalVoucherRows->Ledgers->AccountingGroups->find('children', ['for' =>$accountGroupParent->id])->toArray();
			
			$CreditGroups[]=$accountGroupParent->id;
			foreach($ChildGroups as $ChildGroup)
			{
				$CreditGroups[]=$ChildGroup->id;
			}
		}
		$AllCreditledgers = $this->JournalVouchers->JournalVoucherRows->Ledgers->find()->where(['Ledgers.accounting_group_id IN' =>$CreditGroups]);
		
		$ledgers=[];
		foreach($AllCreditledgers as $AllCreditledger)
		{
		    if(in_array($AllCreditledger->accounting_group_id,$bankGroups)){
				$ledgers[]=['text' =>$AllCreditledger->name, 'value' => $AllCreditledger->id ,'open_window' => 'bank'];
			}
			else if($AllCreditledger->bill_to_bill_accounting == 'yes'){
				$ledgers[]=['text' =>$AllCreditledger->name, 'value' => $AllCreditledger->id,'open_window' => 'party','default_days'=>$AllCreditledger->default_credit_days ];
			}
			else{
				$ledgers[]=['text' =>$AllCreditledger->name, 'value' => $AllCreditledger->id,'open_window' => 'no' ];
			}
		}
		
        $this->set(compact('journalVoucher', 'company_id','ledgers','refDropDown'));
        $this->set('_serialize', ['journalVoucher']);
    }

    /**
     * Delete method
     *
     * @param string|null $id Journal Voucher id.
     * @return \Cake\Http\Response|null Redirects to index.
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function delete($id = null)
    {
        $this->request->allowMethod(['post', 'delete']);
        $journalVoucher = $this->JournalVouchers->get($id);
        if ($this->JournalVouchers->delete($journalVoucher)) {
            $this->Flash->success(__('The journal voucher has been deleted.'));
        } else {
            $this->Flash->error(__('The journal voucher could not be deleted. Please, try again.'));
        }

        return $this->redirect(['action' => 'index']);
    }
	public function cancel($id = null)
    {
		// $this->request->allowMethod(['post', 'delete']);
        $journalVoucher = $this->JournalVouchers->get($id, [
            'contain' => ['JournalVoucherRows'=>['ReferenceDetails']]
        ]);
		$journal_voucher_row_ids=[];
		foreach($journalVoucher->journal_voucher_rows as $journal_voucher_row){
			$journal_voucher_row_ids[]=$journal_voucher_row->id;
		}
		$company_id=$this->Auth->User('session_company_id');
		$journalVoucher->status='cancel';
        if ($this->JournalVouchers->save($journalVoucher)) {
			
				$deleteRefDetails = $this->JournalVouchers->JournalVoucherRows->ReferenceDetails->query();
				$deleteRef = $deleteRefDetails->delete()
					->where(['ReferenceDetails.journal_voucher_row_id IN' => $journal_voucher_row_ids])
					->execute();
				$deleteAccountEntries = $this->JournalVouchers->AccountingEntries->query();
				$result = $deleteAccountEntries->delete()
				->where(['AccountingEntries.journal_voucher_id' => $journalVoucher->id])
				->execute();
			$this->Flash->success(__('The Journal Voucher has been cancelled.'));
        } else {
            $this->Flash->error(__('The Journal Voucher could not be cancelled. Please, try again.'));
        }

        return $this->redirect(['action' => 'index']);
    }
}
