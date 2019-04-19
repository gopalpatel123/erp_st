<?php
namespace App\Controller;

use App\Controller\AppController;

/**
 * SalesVouchers Controller
 *
 * @property \App\Model\Table\SalesVouchersTable $SalesVouchers
 *
 * @method \App\Model\Entity\SalesVoucher[] paginate($object = null, array $settings = [])
 */
class SalesVouchersController extends AppController
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
            'contain' => ['Companies','SalesVoucherRows'=>'Ledgers'],
			'limit' => 100
        ];
        $salesVouchers = $this->paginate($this->SalesVouchers->find()->where(['SalesVouchers.company_id'=>$company_id])->where([
		'OR' => [
            'SalesVouchers.voucher_no' => $search,
            //....
			'SalesVouchers.reference_no LIKE' => '%'.$search.'%',
			//...
			'SalesVouchers.transaction_date ' => date('Y-m-d',strtotime($search))
		 ]]));
		// pr($salesVouchers->toArray());
		 //exit;

        $this->set(compact('salesVouchers','search'));
        $this->set('_serialize', ['salesVouchers']);
    }

    /**
     * View method
     *
     * @param string|null $id Sales Voucher id.
     * @return \Cake\Http\Response|void
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function view($id = null)
    {
		$this->viewBuilder()->layout('index_layout');
        $salesVoucher = $this->SalesVouchers->get($id, [
            'contain' => ['Companies', 'SalesVoucherRows'=>['Ledgers','ReferenceDetails'],]
        ]);
       // pr($salesVoucher);exit;
        $this->set('salesVoucher', $salesVoucher);
        $this->set('_serialize', ['salesVoucher']);
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
        $this->request->data['company_id'] =$company_id;
        $salesVoucher = $this->SalesVouchers->newEntity();
		
        if ($this->request->is('post')) {
			//pr($this->request->getData()); exit;
			$this->request->data['transaction_date'] = date("Y-m-d",strtotime($this->request->getData()['transaction_date']));
            $salesVoucher = $this->SalesVouchers->patchEntity($salesVoucher, $this->request->getData());
			$Voucher = $this->SalesVouchers->find()->select(['voucher_no'])->where(['company_id'=>$company_id])->order(['voucher_no' => 'DESC'])->first();
			if($Voucher)
			{
				$salesVoucher->voucher_no = $Voucher->voucher_no+1;
			}
			else
			{
				$salesVoucher->voucher_no = 1;
			} 
			$salesVoucher = $this->SalesVouchers->patchEntity($salesVoucher, $this->request->getData(), [
							'associated' => ['SalesVoucherRows','SalesVoucherRows.ReferenceDetails']
						]);
			//$salesVoucher->financial_year_id =$financialYear_id;		
			//transaction date for reference detail code start here--
			foreach($salesVoucher->sales_voucher_rows as $sales_voucher_row)
			{
				if(!empty($sales_voucher_row->reference_details))
				{
					foreach($sales_voucher_row->reference_details as $reference_detail)
					{
						$reference_detail->transaction_date = $salesVoucher->transaction_date;
					}
				}
			}
			//transaction date for reference detail code close here--
			
            if ($this->SalesVouchers->save($salesVoucher)) {
				
				foreach($salesVoucher->sales_voucher_rows as $sales_voucher_row)
				{
					$accountEntry = $this->SalesVouchers->AccountingEntries->newEntity();
					$accountEntry->ledger_id                  = $sales_voucher_row->ledger_id;
					$accountEntry->debit                      = round($sales_voucher_row->debit,2);
					$accountEntry->credit                     = round($sales_voucher_row->credit,2);
					$accountEntry->transaction_date           = $salesVoucher->transaction_date;
					$accountEntry->company_id                 = $company_id;
					$accountEntry->sales_voucher_id           = $salesVoucher->id;
					$accountEntry->sales_voucher_row_id       = $sales_voucher_row->id;
					
					$this->SalesVouchers->AccountingEntries->save($accountEntry);
				}
                $this->Flash->success(__('The sales voucher has been saved.'));

                return $this->redirect(['action' => 'index']);
            }
            $this->Flash->error(__('The sales voucher could not be saved. Please, try again.'));
        }
		$Voucher = $this->SalesVouchers->find()->select(['voucher_no'])->where(['company_id'=>$company_id])->order(['voucher_no' => 'DESC'])->first();
		if($Voucher)
		{
			$voucher_no=$Voucher->voucher_no+1;
		}
		else
		{ 
			$voucher_no=1;
		} 
		$bankParentGroups = $this->SalesVouchers->SalesVoucherRows->Ledgers->AccountingGroups->find()
						->where(['AccountingGroups.company_id'=>$company_id, 'AccountingGroups.bank'=>'1']);
						
		$bankGroups=[];
		
		foreach($bankParentGroups as $bankParentGroup)
		{
			$accountingGroups = $this->SalesVouchers->SalesVoucherRows->Ledgers->AccountingGroups
			->find('children', ['for' => $bankParentGroup->id])->toArray();
			$bankGroups[]=$bankParentGroup->id;
			foreach($accountingGroups as $accountingGroup){
				$bankGroups[]=$accountingGroup->id;
			}
		}
		
		$ParentGroups = $this->SalesVouchers->SalesVoucherRows->Ledgers->AccountingGroups->find()->where(['sales_voucher_first_ledger'=>1,'company_id'=>$company_id]); 
		$Groups=[];
		foreach($ParentGroups as $ParentGroup)
		{
			$ChildGroups = $this->SalesVouchers->SalesVoucherRows->Ledgers->AccountingGroups->find('children', ['for' =>$ParentGroup->id])->toArray();
			$Groups[]=$ParentGroup->id;
			foreach($ChildGroups as $ChildGroup)
			{
				$Groups[]=$ChildGroup->id;
			}
		}
		$ParentLedgers = $this->SalesVouchers->SalesVoucherRows->Ledgers->find()->where(['Ledgers.accounting_group_id IN' =>$Groups]);
		
		$ledgerDroption =[];
		foreach($ParentLedgers as $ParentLedger){
		if(in_array($ParentLedger->accounting_group_id,$bankGroups)){
				$ledgerDroption[]=['text' =>$ParentLedger->name, 'value' => $ParentLedger->id ,'open_window' => 'bank'];
			}
			else if($ParentLedger->bill_to_bill_accounting == 'yes'){
				$ledgerDroption[]=['text' =>$ParentLedger->name, 'value' => $ParentLedger->id,'open_window' => 'party','default_days'=>$ParentLedger->default_credit_days];
			}
			else{
				$ledgerDroption[]=['text' =>$ParentLedger->name, 'value' => $ParentLedger->id,'open_window' => 'no' ];
			}
		}
		
		$ParentSalesAccountGroups = $this->SalesVouchers->SalesVoucherRows->Ledgers->AccountingGroups->find()->where(['sales_voucher_sales_ledger'=>1,'company_id'=>$company_id]); 
		
		$Groupcrs=[];
		
		foreach($ParentSalesAccountGroups as $ParentSalesAccountGroup)
		{
			$ChildSalesAccountGroups = $this->SalesVouchers->SalesVoucherRows->Ledgers->AccountingGroups->find('children', ['for' =>$ParentSalesAccountGroup->id])->toArray();
			$Groupcrs[]=$ParentSalesAccountGroup->id;
			foreach($ChildSalesAccountGroups as $ChildSalesAccountGroup)
			{
				$Groupcrs[]=$ChildSalesAccountGroup->id;
			}
		}
		
		$ledgers = $this->SalesVouchers->SalesVoucherRows->Ledgers->find()->where(['Ledgers.accounting_group_id IN' =>$Groupcrs]);
		$ledgerOptions =[];
		foreach($ledgers as $ledger){
		if(in_array($ledger->accounting_group_id,$bankGroups)){
				$ledgerOptions[]=['text' =>$ledger->name, 'value' => $ledger->id ,'open_window' => 'bank'];
			}
			else if($ledger->bill_to_bill_accounting == 'yes'){
				$ledgerOptions[]=['text' =>$ledger->name, 'value' => $ledger->id,'open_window' => 'party','default_days'=>$ledger->default_credit_days];
			}
			else{
				$ledgerOptions[]=['text' =>$ledger->name, 'value' => $ledger->id,'open_window' => 'no' ];
			}
		}
		
		$ParentAccountGroups = $this->SalesVouchers->SalesVoucherRows->Ledgers->AccountingGroups->find()->where(['sales_voucher_all_ledger'=>1,'company_id'=>$company_id]); 
		
		$AllGroups=[];
		
		foreach($ParentAccountGroups as $ParentAccountGroup)
		{
			$ChildAccountGroups = $this->SalesVouchers->SalesVoucherRows->Ledgers->AccountingGroups->find('children', ['for' =>$ParentAccountGroup->id])->toArray();
			$AllGroups[]=$ParentAccountGroup->id;
			foreach($ChildAccountGroups as $ChildAccountGroup)
			{
				$AllGroups[]=$ChildAccountGroup->id;
			}
		}
		
		$all_ledgers = $this->SalesVouchers->SalesVoucherRows->Ledgers->find()->where(['Ledgers.accounting_group_id IN' =>$AllGroups]);
		$AllLedgers =[];
		foreach($all_ledgers as $all_ledger){
		if(in_array($all_ledger->accounting_group_id,$bankGroups)){
				$AllLedgers[]=['text' =>$all_ledger->name, 'value' => $all_ledger->id ,'open_window' => 'bank'];
			}
			else if($all_ledger->bill_to_bill_accounting == 'yes'){
				$AllLedgers[]=['text' =>$all_ledger->name, 'value' => $all_ledger->id,'open_window' => 'party','default_days'=>$all_ledger->default_credit_days ];
			}
			else{
				$AllLedgers[]=['text' =>$all_ledger->name, 'value' => $all_ledger->id,'open_window' => 'no' ];
			}
		}
		
		$referenceDetails=$this->SalesVouchers->SalesVoucherRows->ReferenceDetails->find('list');
        $this->set(compact('salesVoucher','voucher_no','ledgerOptions','company_id','referenceDetails','ledgerDroption','AllLedgers'));
        $this->set('_serialize', ['salesVoucher']);
    }

    /**
     * Edit method
     *
     * @param string|null $id Sales Voucher id.
     * @return \Cake\Http\Response|null Redirects on successful edit, renders view otherwise.
     * @throws \Cake\Network\Exception\NotFoundException When record not found.
     */
    public function edit($id = null)
    {
		
		$this->viewBuilder()->layout('index_layout');
		$company_id=$this->Auth->User('session_company_id');
        $this->request->data['company_id'] =$company_id;
        $salesVoucher = $this->SalesVouchers->get($id, [
            'contain' => ['SalesVoucherRows'=>['ReferenceDetails']]
        ]);
		$originalSalesVoucher=$salesVoucher;
		
		if ($this->request->is(['patch', 'post', 'put'])) {
			//GET ORIGINAL DATA AND DELETE REFERENCE DATA//
			$orignalSales_voucher_row_ids=[];
			foreach($originalSalesVoucher->sales_voucher_rows as $originalSales_voucher_rows){
				$orignalSales_voucher_row_ids[]=$originalSales_voucher_rows->id;
			}
			$query_update = $this->SalesVouchers->SalesVoucherRows->query();
							$query_update->update()
							->set(['mode_of_payment' => '', 'cheque_no' => '', 'cheque_date' => ''])
							->where(['sales_voucher_id' => $salesVoucher->id])
							->execute();
			 $salesVoucher = $this->SalesVouchers->get($id, [
            'contain' => ['SalesVoucherRows'=>['ReferenceDetails']]
        ]);
			$this->SalesVouchers->SalesVoucherRows->ReferenceDetails->deleteAll(['ReferenceDetails.sales_voucher_row_id IN'=>$orignalSales_voucher_row_ids]);
			
			//GET ORIGINAL DATA AND DELETE REFERENCE DATA//
			
			$this->request->data['transaction_date'] = date("Y-m-d",strtotime($this->request->getData()['transaction_date']));
           
			$salesVoucher = $this->SalesVouchers->patchEntity($salesVoucher, $this->request->getData(), [
							'associated' => ['SalesVoucherRows','SalesVoucherRows.ReferenceDetails']
						]);

			//pr($salesVoucher);
			//exit;
			foreach($salesVoucher->sales_voucher_rows as $sales_voucher_row)
			{
				if(!empty($sales_voucher_row->reference_details))
				{
					foreach($sales_voucher_row->reference_details as $reference_detail)
					{
						$reference_detail->transaction_date = $salesVoucher->transaction_date;
					}
				}
			}
			//ppr($salesVoucher->toArray()); exit;
			if ($this->SalesVouchers->save($salesVoucher)) {
				$query_delete = $this->SalesVouchers->AccountingEntries->query();
					$query_delete->delete()
					->where(['sales_voucher_id' => $salesVoucher->id,'company_id'=>$company_id])
					->execute();
					
				foreach($salesVoucher->sales_voucher_rows as $sales_voucher_row)
				{
					$accountEntry = $this->SalesVouchers->AccountingEntries->newEntity();
					$accountEntry->ledger_id                  = $sales_voucher_row->ledger_id;
					$accountEntry->debit                      = round($sales_voucher_row->debit,2);
					$accountEntry->credit                     = round($sales_voucher_row->credit,2);
					$accountEntry->transaction_date           = $salesVoucher->transaction_date;
					$accountEntry->company_id                 = $company_id;
					$accountEntry->sales_voucher_id           = $salesVoucher->id;
					$accountEntry->sales_voucher_row_id       = $sales_voucher_row->id;
					
					$this->SalesVouchers->AccountingEntries->save($accountEntry);
				}
                $this->Flash->success(__('The sales voucher has been saved.'));
				$this->repairRef();
                return $this->redirect(['action' => 'index']);
            }
			
            $this->Flash->error(__('The sales voucher could not be saved. Please, try again.'));
		
        }
		
		
		$refDropDown =[];
		foreach($salesVoucher->sales_voucher_rows as $sales_voucher_row)
		{
			if(!empty($sales_voucher_row->reference_details))
			{
				foreach($sales_voucher_row->reference_details as $referenceDetailRows)
				{
					@$ref_details_name[]=$referenceDetailRows->ref_name;
				}
				$query = $this->SalesVouchers->SalesVoucherRows->ReferenceDetails->find();
				$query->select(['total_debit' => $query->func()->sum('ReferenceDetails.debit'),'total_credit' => $query->func()->sum('ReferenceDetails.credit')])
				->where(['ReferenceDetails.ledger_id'=>$sales_voucher_row->ledger_id,'ReferenceDetails.type !='=>'On Account'])
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
				
				$refDropDown[$sales_voucher_row->id] = $option;
			}
		}
		
		
		
		$bankParentGroups = $this->SalesVouchers->SalesVoucherRows->Ledgers->AccountingGroups->find()
						->where(['AccountingGroups.company_id'=>$company_id, 'AccountingGroups.bank'=>'1']);
						
		$bankGroups=[];
		
		foreach($bankParentGroups as $bankParentGroup)
		{
			$accountingGroups = $this->SalesVouchers->SalesVoucherRows->Ledgers->AccountingGroups
			->find('children', ['for' => $bankParentGroup->id])->toArray();
			$bankGroups[]=$bankParentGroup->id;
			foreach($accountingGroups as $accountingGroup){
				$bankGroups[]=$accountingGroup->id;
			}
		}
		$ParentGroups = $this->SalesVouchers->SalesVoucherRows->Ledgers->AccountingGroups->find()->where(['sales_voucher_first_ledger'=>1,'company_id'=>$company_id]); 
		$Groups=[];
		foreach($ParentGroups as $ParentGroup)
		{
			$ChildGroups = $this->SalesVouchers->SalesVoucherRows->Ledgers->AccountingGroups->find('children', ['for' =>$ParentGroup->id])->toArray();
			$Groups[]=$ParentGroup->id;
			foreach($ChildGroups as $ChildGroup)
			{
				$Groups[]=$ChildGroup->id;
			}
		}
		$ParentLedgers = $this->SalesVouchers->SalesVoucherRows->Ledgers->find()->where(['Ledgers.accounting_group_id IN' =>$Groups]);
		$ledgerDroption =[];
		foreach($ParentLedgers as $ParentLedger){
		if(in_array($ParentLedger->accounting_group_id,$bankGroups)){
				$ledgerDroption[]=['text' =>$ParentLedger->name, 'value' => $ParentLedger->id ,'open_window' => 'bank'];
			}
			else if($ParentLedger->bill_to_bill_accounting == 'yes'){
				$ledgerDroption[]=['text' =>$ParentLedger->name, 'value' => $ParentLedger->id,'open_window' => 'party','default_days'=>$ParentLedger->default_credit_days ];
			}
			else{
				$ledgerDroption[]=['text' =>$ParentLedger->name, 'value' => $ParentLedger->id,'open_window' => 'no' ];
			}
		}
		
		$ParentSalesAccountGroups = $this->SalesVouchers->SalesVoucherRows->Ledgers->AccountingGroups->find()->where(['sales_voucher_sales_ledger'=>1,'company_id'=>$company_id]); 
		
		$Groupcrs=[];
		
		foreach($ParentSalesAccountGroups as $ParentSalesAccountGroup)
		{
			$ChildSalesAccountGroups = $this->SalesVouchers->SalesVoucherRows->Ledgers->AccountingGroups->find('children', ['for' =>$ParentSalesAccountGroup->id])->toArray();
			$Groupcrs[]=$ParentSalesAccountGroup->id;
			foreach($ChildSalesAccountGroups as $ChildSalesAccountGroup)
			{
				$Groupcrs[]=$ChildSalesAccountGroup->id;
			}
		}
		
		$ledgers = $this->SalesVouchers->SalesVoucherRows->Ledgers->find()->where(['Ledgers.accounting_group_id IN' =>$Groupcrs]);
		$ledgerOptions =[];
		foreach($ledgers as $ledger){
		if(in_array($ledger->accounting_group_id,$bankGroups)){
				$ledgerOptions[]=['text' =>$ledger->name, 'value' => $ledger->id ,'open_window' => 'bank'];
			}
			else if($ledger->bill_to_bill_accounting == 'yes'){
				$ledgerOptions[]=['text' =>$ledger->name, 'value' => $ledger->id,'open_window' => 'party','default_days'=>$ledger->default_credit_days];
			}
			else{
				$ledgerOptions[]=['text' =>$ledger->name, 'value' => $ledger->id,'open_window' => 'no' ];
			}
		}
		$ParentAccountGroups = $this->SalesVouchers->SalesVoucherRows->Ledgers->AccountingGroups->find()->where(['sales_voucher_all_ledger'=>1,'company_id'=>$company_id]); 
		
		$AllGroups=[];
		
		foreach($ParentAccountGroups as $ParentAccountGroup)
		{
			$ChildAccountGroups = $this->SalesVouchers->SalesVoucherRows->Ledgers->AccountingGroups->find('children', ['for' =>$ParentAccountGroup->id])->toArray();
			$AllGroups[]=$ParentAccountGroup->id;
			foreach($ChildAccountGroups as $ChildAccountGroup)
			{
				$AllGroups[]=$ChildAccountGroup->id;
			}
		}
		
		$all_ledgers = $this->SalesVouchers->SalesVoucherRows->Ledgers->find()->where(['Ledgers.accounting_group_id IN' =>$AllGroups]);
		$AllLedgers =[];
		foreach($all_ledgers as $all_ledger){
		if(in_array($all_ledger->accounting_group_id,$bankGroups)){
				$AllLedgers[]=['text' =>$all_ledger->name, 'value' => $all_ledger->id ,'open_window' => 'bank'];
			}
			else if($all_ledger->bill_to_bill_accounting == 'yes'){
				$AllLedgers[]=['text' =>$all_ledger->name, 'value' => $all_ledger->id,'open_window' => 'party','default_days'=>$all_ledger->default_credit_days];
			}
			else{
				$AllLedgers[]=['text' =>$all_ledger->name, 'value' => $all_ledger->id,'open_window' => 'no' ];
			}
		}
		
        $this->set(compact('salesVoucher', 'company_id','ledgerDroption','ledgerOptions','refDropDown','AllLedgers'));
        $this->set('_serialize', ['salesVoucher']);
    }

    /**
     * Delete method
     *
     * @param string|null $id Sales Voucher id.
     * @return \Cake\Http\Response|null Redirects to index.
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function delete($id = null)
    {
        $this->request->allowMethod(['post', 'delete']);
        $salesVoucher = $this->SalesVouchers->get($id);
        if ($this->SalesVouchers->delete($salesVoucher)) {
            $this->Flash->success(__('The sales voucher has been deleted.'));
        } else {
            $this->Flash->error(__('The sales voucher could not be deleted. Please, try again.'));
        }

        return $this->redirect(['action' => 'index']);
    }
	public function cancel($id = null)
    {
		// $this->request->allowMethod(['post', 'delete']);
        $salesVoucher = $this->SalesVouchers->get($id, [
            'contain' => ['SalesVoucherRows'=>['ReferenceDetails']]
        ]);
		$sales_voucher_row_ids=[];
		foreach($salesVoucher->sales_voucher_rows as $sales_voucher_row){
			$sales_voucher_row_ids[]=$sales_voucher_row->id;
		}
		$company_id=$this->Auth->User('session_company_id');
		$salesVoucher->status='cancel';
        if ($this->SalesVouchers->save($salesVoucher)) {
			
				$deleteRefDetails = $this->SalesVouchers->SalesVoucherRows->ReferenceDetails->query();
				$deleteRef = $deleteRefDetails->delete()
					->where(['ReferenceDetails.sales_voucher_row_id IN' => $sales_voucher_row_ids])
					->execute();
				$deleteAccountEntries = $this->SalesVouchers->AccountingEntries->query();
				$result = $deleteAccountEntries->delete()
				->where(['AccountingEntries.sales_voucher_id' => $salesVoucher->id])
				->execute();
			
			
				
            $this->Flash->success(__('The Sales Vouchers has been cancelled.'));
        } else {
            $this->Flash->error(__('The Sales Vouchers could not be cancelled. Please, try again.'));
        }

        return $this->redirect(['action' => 'index']);
    }
}
