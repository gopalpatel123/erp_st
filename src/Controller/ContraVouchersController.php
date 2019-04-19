<?php
namespace App\Controller;

use App\Controller\AppController;

/**
 * ContraVouchers Controller
 *
 * @property \App\Model\Table\ContraVouchersTable $ContraVouchers
 *
 * @method \App\Model\Entity\ContraVoucher[] paginate($object = null, array $settings = [])
 */
class ContraVouchersController extends AppController
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
            'contain' => ['Companies','ContraVoucherRows'=>'Ledgers'],
			'limit' => 100
        ];
		if($search){
		$contraVouchers = $this->paginate($this->ContraVouchers->find()->where(['ContraVouchers.company_id'=>$company_id,'ContraVouchers.financial_year_id'=>$financialYear_id])->where([
		'OR' => [
            'ContraVouchers.voucher_no' => $search,
            //....
			'ContraVouchers.reference_no LIKE' => '%'.$search.'%',
				//...
			'ContraVouchers.transaction_date ' => date('Y-m-d',strtotime($search))
		 ]]));
		}
		else {
        $contraVouchers = $this->paginate($this->ContraVouchers->find()->where(['ContraVouchers.company_id'=>$company_id,'ContraVouchers.financial_year_id'=>$financialYear_id]));
		}
        $this->set(compact('contraVouchers','search'));
        $this->set('_serialize', ['contraVouchers']);
    }

    /**
     * View method
     *
     * @param string|null $id Contra Voucher id.
     * @return \Cake\Http\Response|void
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function view($id = null)
    {
		$this->viewBuilder()->layout('index_layout');
        $contraVoucher = $this->ContraVouchers->get($id, [
            'contain' => ['ContraVoucherRows'=>['Ledgers']]
        ]);

        $this->set('contraVoucher', $contraVoucher);
        $this->set('_serialize', ['contraVoucher']);
    }

    /**
     * Add method
     *
     * @return \Cake\Http\Response|null Redirects on successful add, renders view otherwise.
     */
    public function add()
    {
		$this->viewBuilder()->layout('index_layout');
        $contraVoucher = $this->ContraVouchers->newEntity();
		$company_id=$this->Auth->User('session_company_id');
		$financialYear_id=$this->Auth->User('financialYear_id');
        $this->request->data['company_id'] =$company_id;
        if ($this->request->is('post')) {
			$this->request->data['transaction_date'] = date("Y-m-d",strtotime($this->request->getData()['transaction_date']));
            $contraVoucher = $this->ContraVouchers->patchEntity($contraVoucher, $this->request->getData());
			$Voucher = $this->ContraVouchers->find()->select(['voucher_no'])->where(['company_id'=>$company_id,'financial_year_id'=>$financialYear_id])->order(['voucher_no' => 'DESC'])->first();
			if($Voucher)
			{
				$contraVoucher->voucher_no = $Voucher->voucher_no+1;
			}
			else
			{
				$contraVoucher->voucher_no = 1;
			} 
			$contraVoucher->financial_year_id =$financialYear_id;
            if ($this->ContraVouchers->save($contraVoucher)) {
				
				foreach($contraVoucher->contra_voucher_rows as $contra_voucher_row)
				{
					$accountEntry = $this->ContraVouchers->AccountingEntries->newEntity();
					$accountEntry->ledger_id                  = $contra_voucher_row->ledger_id;
					$accountEntry->debit                      = round($contra_voucher_row->debit,2);
					$accountEntry->credit                     = round($contra_voucher_row->credit,2);
					$accountEntry->transaction_date           = $contraVoucher->transaction_date;
					$accountEntry->company_id                 = $company_id;
					$accountEntry->contra_voucher_id          = $contraVoucher->id;
					$accountEntry->contra_voucher_row_id      = $contra_voucher_row->id;
					
					$this->ContraVouchers->AccountingEntries->save($accountEntry);
				}
                $this->Flash->success(__('The contra voucher has been saved.'));

                return $this->redirect(['action' => 'add']);
            }
            $this->Flash->error(__('The contra voucher could not be saved. Please, try again.'));
        }
		$Voucher = $this->ContraVouchers->find()->select(['voucher_no'])->where(['company_id'=>$company_id,'financial_year_id'=>$financialYear_id])->order(['voucher_no' => 'DESC'])->first();
		if($Voucher)
		{
			$voucher_no=$Voucher->voucher_no+1;
		}
		else
		{ 
			$voucher_no=1;
		} 
		$bankParentGroups = $this->ContraVouchers->ContraVoucherRows->Ledgers->AccountingGroups->find()
						->where(['AccountingGroups.company_id'=>$company_id, 'AccountingGroups.bank'=>'1']);
						
		$bankGroups=[];
		
		foreach($bankParentGroups as $bankParentGroup)
		{
			$accountingGroups = $this->ContraVouchers->ContraVoucherRows->Ledgers->AccountingGroups
			->find('children', ['for' => $bankParentGroup->id])->toArray();
			$bankGroups[]=$bankParentGroup->id;
			foreach($accountingGroups as $accountingGroup){
				$bankGroups[]=$accountingGroup->id;
			}
		}
		
		$ParentGroups = $this->ContraVouchers->ContraVoucherRows->Ledgers->AccountingGroups->find()->where(['contra_voucher_ledger'=>1,'company_id'=>$company_id]); 
		$Groups=[];
		foreach($ParentGroups as $ParentGroup)
		{
			$ChildGroups = $this->ContraVouchers->ContraVoucherRows->Ledgers->AccountingGroups->find('children', ['for' =>$ParentGroup->id])->toArray();
			$Groups[]=$ParentGroup->id;
			foreach($ChildGroups as $ChildGroup)
			{
				$Groups[]=$ChildGroup->id;
			}
		}
		$ParentLedgers = $this->ContraVouchers->ContraVoucherRows->Ledgers->find()->where(['Ledgers.accounting_group_id IN' =>$Groups]);
		
		$ledgers =[];
		foreach($ParentLedgers as $ParentLedger){
		if(in_array($ParentLedger->accounting_group_id,$bankGroups)){
				$ledgers[]=['text' =>$ParentLedger->name, 'value' => $ParentLedger->id ,'open_window' => 'bank'];
			}
			else
			{
				$ledgers[]=['text' =>$ParentLedger->name, 'value' => $ParentLedger->id,'open_window' => 'no' ];
			}
		}
        $this->set(compact('contraVoucher','voucher_no','ledgers','company_id'));
        $this->set('_serialize', ['contraVoucher']);
    }

    /**
     * Edit method
     *
     * @param string|null $id Contra Voucher id.
     * @return \Cake\Http\Response|null Redirects on successful edit, renders view otherwise.
     * @throws \Cake\Network\Exception\NotFoundException When record not found.
     */
    public function edit($id = null)
    {
		$this->viewBuilder()->layout('index_layout');
        $contraVoucher = $this->ContraVouchers->get($id, [
            'contain' => ['ContraVoucherRows']
        ]);
		$company_id=$this->Auth->User('session_company_id');
        $this->request->data['company_id'] =$company_id;
        if ($this->request->is(['patch', 'post', 'put'])) {
			$query_update = $this->ContraVouchers->ContraVoucherRows->query();
							$query_update->update()
							->set(['mode_of_payment' => '', 'cheque_no' => '', 'cheque_date' => ''])
							->where(['contra_voucher_id' => $contraVoucher->id])
							->execute();
			 $contraVoucher = $this->ContraVouchers->get($id, [
            'contain' => ['ContraVoucherRows']
        ]);
			$this->request->data['transaction_date'] = date("Y-m-d",strtotime($this->request->getData()['transaction_date']));
            $contraVoucher = $this->ContraVouchers->patchEntity($contraVoucher, $this->request->getData());
			//pr($contraVoucher);exit;
            if ($this->ContraVouchers->save($contraVoucher)) {
				$query_delete = $this->ContraVouchers->AccountingEntries->query();
					$query_delete->delete()
					->where(['contra_voucher_id' => $contraVoucher->id,'company_id'=>$company_id])
					->execute();
				foreach($contraVoucher->contra_voucher_rows as $contra_voucher_row)
				{
					$accountEntry = $this->ContraVouchers->AccountingEntries->newEntity();
					$accountEntry->ledger_id                  = $contra_voucher_row->ledger_id;
					$accountEntry->debit                      = round($contra_voucher_row->debit,2);
					$accountEntry->credit                     = round($contra_voucher_row->credit,2);
					$accountEntry->transaction_date           = $contraVoucher->transaction_date;
					$accountEntry->company_id                 = $company_id;
					$accountEntry->contra_voucher_id          = $contraVoucher->id;
					$accountEntry->contra_voucher_row_id      = $contra_voucher_row->id;
					
					$this->ContraVouchers->AccountingEntries->save($accountEntry);
				}
                $this->Flash->success(__('The contra voucher has been saved.'));

                return $this->redirect(['action' => 'index']);
            }
            $this->Flash->error(__('The contra voucher could not be saved. Please, try again.'));
        }
        
		$bankParentGroups = $this->ContraVouchers->ContraVoucherRows->Ledgers->AccountingGroups->find()
						->where(['AccountingGroups.company_id'=>$company_id, 'AccountingGroups.bank'=>'1']);
						
		$bankGroups=[];
		
		foreach($bankParentGroups as $bankParentGroup)
		{
			$accountingGroups = $this->ContraVouchers->ContraVoucherRows->Ledgers->AccountingGroups
			->find('children', ['for' => $bankParentGroup->id])->toArray();
			$bankGroups[]=$bankParentGroup->id;
			foreach($accountingGroups as $accountingGroup){
				$bankGroups[]=$accountingGroup->id;
			}
		}
		
		$ParentGroups = $this->ContraVouchers->ContraVoucherRows->Ledgers->AccountingGroups->find()->where(['contra_voucher_ledger'=>1,'company_id'=>$company_id]); 
		$Groups=[];
		foreach($ParentGroups as $ParentGroup)
		{
			$ChildGroups = $this->ContraVouchers->ContraVoucherRows->Ledgers->AccountingGroups->find('children', ['for' =>$ParentGroup->id])->toArray();
			$Groups[]=$ParentGroup->id;
			foreach($ChildGroups as $ChildGroup)
			{
				$Groups[]=$ChildGroup->id;
			}
		}
		$ParentLedgers = $this->ContraVouchers->ContraVoucherRows->Ledgers->find()->where(['Ledgers.accounting_group_id IN' =>$Groups]);
		
		$ledgers =[];
		foreach($ParentLedgers as $ParentLedger){
		if(in_array($ParentLedger->accounting_group_id,$bankGroups)){
				$ledgers[]=['text' =>$ParentLedger->name, 'value' => $ParentLedger->id ,'open_window' => 'bank'];
			}
			else
			{
				$ledgers[]=['text' =>$ParentLedger->name, 'value' => $ParentLedger->id,'open_window' => 'no' ];
			}
		}
        $this->set(compact('contraVoucher', 'ledgers'));
        $this->set('_serialize', ['contraVoucher']);
    }
     
	
    /**
     * Delete method
     *
     * @param string|null $id Contra Voucher id.
     * @return \Cake\Http\Response|null Redirects to index.
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function delete($id = null)
    {
        $this->request->allowMethod(['post', 'delete']);
        $contraVoucher = $this->ContraVouchers->get($id);
        if ($this->ContraVouchers->delete($contraVoucher)) {
            $this->Flash->success(__('The contra voucher has been deleted.'));
        } else {
            $this->Flash->error(__('The contra voucher could not be deleted. Please, try again.'));
        }

        return $this->redirect(['action' => 'index']);
    }
	public function cancel($id = null)
    {
        $contraVoucher = $this->ContraVouchers->get($id, [
            'contain' => ['ContraVoucherRows']
        ]);
		$contra_voucher_row_ids=[];
		foreach($contraVoucher->contra_voucher_rows as $contra_voucher_row){
			$contra_voucher_row_ids[]=$contra_voucher_row->id;
		}
		$company_id=$this->Auth->User('session_company_id');
		$contraVoucher->status='cancel';
        if ($this->ContraVouchers->save($contraVoucher)) {
			
			$deleteAccountEntries = $this->ContraVouchers->AccountingEntries->query();
				$result = $deleteAccountEntries->delete()
				->where(['AccountingEntries.contra_voucher_id' => $contraVoucher->id])
				->execute();
			$this->Flash->success(__('The Contra Voucher has been cancelled.'));
        } else {
            $this->Flash->error(__('The Contra Voucher could not be cancelled. Please, try again.'));
        }

        return $this->redirect(['action' => 'index']);
    }
}
