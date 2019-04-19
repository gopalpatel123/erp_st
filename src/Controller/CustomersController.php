<?php
namespace App\Controller;

use App\Controller\AppController;

/**
 * Customers Controller
 *
 * @property \App\Model\Table\CustomersTable $Customers
 *
 * @method \App\Model\Entity\Customer[] paginate($object = null, array $settings = [])
 */
class CustomersController extends AppController
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
            'contain' => ['States'],
			'limit' => 100
        ];
        $customers = $this->paginate($this->Customers->find()->where(['Customers.company_id'=>$company_id])->where([
		'OR' => [
            'Customers.name LIKE' => '%'.$search.'%',
			//...
			'States.name LIKE' => '%'.$search.'%',
			//...
			'Customers.gstin LIKE' => '%'.$search.'%',
			//...
			'Customers.mobile LIKE' => '%'.$search.'%',
			//...
			'Customers.email LIKE' => '%'.$search.'%'
		]]));

        $this->set(compact('customers','search'));
        $this->set('_serialize', ['customers']);
    }

    /**
     * View method
     *
     * @param string|null $id Customer id.
     * @return \Cake\Http\Response|void
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function view($id = null)
    {
        $customer = $this->Customers->get($id, [
            'contain' => ['States', 'Ledgers']
        ]);

        $this->set('customer', $customer);
        $this->set('_serialize', ['customer']);
    }

    /**
     * Add method
     *
     * @return \Cake\Http\Response|null Redirects on successful add, renders view otherwise.
     */
	 public function addNew(){
	
		$user_id=$this->Auth->User('id');
		$city_id=$this->Auth->User('city_id');
		$customer = $this->Customers->newEntity();
		$company_id=$this->Auth->User('session_company_id');
        if ($this->request->is('post')) {

            $customer = $this->Customers->patchEntity($customer, $this->request->getData());
			$customer->company_id=$company_id;
			//pr($customer);exit;
			$AccountingGroup=$this->Customers->Ledgers->AccountingGroups->find()->where(['AccountingGroups.name'=>'Sundry Debtors','AccountingGroups.company_id'=>$company_id])->first();
		//	pr($AccountingGroup);exit;
			$accounting_group_id=$AccountingGroup->id;
            if ($category_data=$this->Customers->save($customer)) {
				//Create Ledger//
				$ledger = $this->Customers->Ledgers->newEntity();
				$ledger->name = $customer->name;
				$ledger->accounting_group_id = $accounting_group_id;
				$ledger->company_id =$company_id;
				$ledger->customer_id=$customer->id;
				$ledger->bill_to_bill_accounting="No";
				$ledger->default_credit_days=0;
				$this->Customers->Ledgers->save($ledger);
				
				$partyParentGroups = $this->Customers->Ledgers->AccountingGroups->find()
						->where(['AccountingGroups.company_id'=>$company_id, 'AccountingGroups.sale_invoice_party'=>'1']);
				$partyGroups=[];
				
				foreach($partyParentGroups as $partyParentGroup)
				{
					$accountingGroups = $this->Customers->Ledgers->AccountingGroups
					->find('children', ['for' => $partyParentGroup->id])->toArray();
					$partyGroups[]=$partyParentGroup->id;
					foreach($accountingGroups as $accountingGroup){
						$partyGroups[]=$accountingGroup->id;
					}
				}
			
				if($partyGroups)
				{  
					$Partyledgers = $this->Customers->Ledgers->find()
									->where(['Ledgers.accounting_group_id IN' =>$partyGroups,'Ledgers.company_id'=>$company_id])
									->contain(['Customers']);
				}
				
				
				$partyOptions=[];
				foreach($Partyledgers as $Partyledger){
				
				$receiptAccountLedgers = $this->Customers->Ledgers->AccountingGroups->find()
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
				
				//pr($partyOptions);
				
            }
           
        }
	$this->set(compact('partyOptions'));
	}
	 
    public function add()
    {
		$this->viewBuilder()->layout('index_layout');
		$company_id=$this->Auth->User('session_company_id');
        $customer = $this->Customers->newEntity();
		$this->request->data['company_id'] = $company_id;
        if ($this->request->is('post')) {
			
			$customer = $this->Customers->patchEntity($customer, $this->request->data);
			$bill_to_bill_accounting=$customer->bill_to_bill_accounting;
			$default_credit_days=$customer->default_credit_days;
			
				if(!empty($customer->reference_details))
				{
					foreach($customer->reference_details as $reference_detail)
					{
						$reference_detail->transaction_date = $this->Auth->User('session_company')->books_beginning_from;
						$reference_detail->company_id = $company_id;
						$reference_detail->opening_balance = 'yes';
					}
				}
			
			if ($this->Customers->save($customer)) {
				
				$query=$this->Customers->query();
						$result = $query->update()
						->set(['customer_id' => $customer->id])
						->where(['id' => $customer->id])
						->execute();
				//Create Ledger//
				$ledger = $this->Customers->Ledgers->newEntity();
				$ledger->name = $customer->name;
				$ledger->accounting_group_id = $customer->accounting_group_id;
				$ledger->company_id =$company_id;
				$ledger->customer_id=$customer->id;
				$ledger->bill_to_bill_accounting=$bill_to_bill_accounting;
				$ledger->default_credit_days=$default_credit_days;
				if($this->Customers->Ledgers->save($ledger))
				{
					$query=$this->Customers->ReferenceDetails->query();
						$result = $query->update()
						->set(['ledger_id' => $ledger->id])
						->where(['customer_id' => $customer->id])
						->execute();
					//Create Accounting Entry//
			        $transaction_date=$this->Auth->User('session_company')->books_beginning_from;
					$AccountingEntry = $this->Customers->Ledgers->AccountingEntries->newEntity();
					$AccountingEntry->ledger_id = $ledger->id;
					if($customer->debit_credit=="Dr")
					{
						$AccountingEntry->debit = $customer->opening_balance_value;
					}
					if($customer->debit_credit=="Cr")
					{
						$AccountingEntry->credit = $customer->opening_balance_value;
					}
					$AccountingEntry->customer_id      = $customer->id;
					$AccountingEntry->transaction_date = date("Y-m-d",strtotime($transaction_date));
					$AccountingEntry->company_id       = $company_id;
					$AccountingEntry->is_opening_balance = 'yes';
					if($customer->opening_balance_value){
					$this->Customers->Ledgers->AccountingEntries->save($AccountingEntry);
					}
					
				}

                $this->Flash->success(__('The customer has been saved.'));

                return $this->redirect(['action' => 'add']);
            }
			$this->Flash->error(__('The customer could not be saved. Please, try again.'));
        }
		$SundryDebtor = $this->Customers->Ledgers->AccountingGroups->find()->where(['customer'=>1,'company_id'=>$company_id])->first();
		$accountingGroups = $this->Customers->Ledgers->AccountingGroups
							->find('children', ['for' => $SundryDebtor->id])
							->find('List')->toArray();
		$accountingGroups[$SundryDebtor->id]=$SundryDebtor->name;
		ksort($accountingGroups);
        $states = $this->Customers->States->  find('list',
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
													
		$cities = $this->Customers->Cities->  find('list',
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
		
        $this->set(compact('customer', 'states','cities','accountingGroups'));
        $this->set('_serialize', ['customer', 'accountingGroups']);
    }

    /**
     * Edit method
     *
     * @param string|null $id Customer id.
     * @return \Cake\Http\Response|null Redirects on successful edit, renders view otherwise.
     * @throws \Cake\Network\Exception\NotFoundException When record not found.
     */
    public function edit($id = null)
    {
		$this->viewBuilder()->layout('index_layout');
        $customer = $this->Customers->get($id, [
            'contain' => ['Ledgers','ReferenceDetails']
        ]);
		
		$company_id=$this->Auth->User('session_company_id');
        if ($this->request->is(['patch', 'post', 'put'])) {
            $customer = $this->Customers->patchEntity($customer, $this->request->getData());
			$this->Customers->ReferenceDetails->deleteAll(['ReferenceDetails.customer_id'=>$customer->id]);
			$bill_to_bill_accounting=$customer->bill_to_bill_accounting;
			$default_credit_days=$customer->default_credit_days;
			if(!empty($customer->reference_details))
				{
					foreach($customer->reference_details as $reference_detail)
					{
						$reference_detail->transaction_date = $this->Auth->User('session_company')->books_beginning_from;
						$reference_detail->company_id = $company_id;
						$reference_detail->opening_balance = 'yes';
						$reference_detail->ledger_id =$customer->ledger->id ;
					}
				}
            if ($this->Customers->save($customer)) {
				$query = $this->Customers->Ledgers->query();
					$query->update()
						->set(['name' => $customer->name,'accounting_group_id'=>$customer->accounting_group_id,'bill_to_bill_accounting'=>$bill_to_bill_accounting,'default_credit_days'=>$default_credit_days])
						->where(['customer_id' => $id,'company_id'=>$company_id])
						->execute();
						
					//Accounting Entry
					$query_delete = $this->Customers->Ledgers->AccountingEntries->query();
					$query_delete->delete()
					->where(['ledger_id' => $customer->ledger->id,'company_id'=>$company_id,'is_opening_balance'=>'yes'])
					->execute();
					
					$transaction_date=$this->Auth->User('session_company')->books_beginning_from;
					$AccountingEntry = $this->Customers->Ledgers->AccountingEntries->newEntity();
					$AccountingEntry->ledger_id        = $customer->ledger->id;
					if($customer->debit_credit=="Dr")
					{
						$AccountingEntry->debit        = $customer->opening_balance_value;
					}
					if($customer->debit_credit=="Cr")
					{
						$AccountingEntry->credit       = $customer->opening_balance_value;
					}
					$AccountingEntry->customer_id      = $customer->id;
					$AccountingEntry->transaction_date = date("Y-m-d",strtotime($transaction_date));
					$AccountingEntry->company_id       = $company_id;
					$AccountingEntry->is_opening_balance = 'yes';
					if($customer->opening_balance_value){
					$this->Customers->Ledgers->AccountingEntries->save($AccountingEntry);
					}
                $this->Flash->success(__('The customer has been saved.'));

                return $this->redirect(['action' => 'index']);
            }
            $this->Flash->error(__('The customer could not be saved. Please, try again.'));
        }
		
		$SundryDebtor = $this->Customers->Ledgers->AccountingGroups->find()->where(['customer'=>1,'company_id'=>$company_id])->first();
		$accountingGroups = $this->Customers->Ledgers->AccountingGroups
							->find('children', ['for' => $SundryDebtor->id])
							->find('List')->toArray();
		$accountingGroups[$SundryDebtor->id]=$SundryDebtor->name;
		ksort($accountingGroups);
		$account_entry  = $this->Customers->Ledgers->AccountingEntries->find()->where(['ledger_id'=>$customer->ledger->id,'company_id'=>$company_id,'is_opening_balance'=>'yes'])->first();
		//pr($account_entry->toArray());exit;
        $states = $this->Customers->States->find('list',
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
												
		$cities = $this->Customers->Cities->  find('list',
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
		
		$this->set(compact('customer', 'states','cities','accountingGroups','account_entry'));
        $this->set('_serialize', ['customer']);
    }

    /**
     * Delete method
     *
     * @param string|null $id Customer id.
     * @return \Cake\Http\Response|null Redirects to index.
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function delete($id = null)
    {
		
        $this->request->allowMethod(['post', 'delete']);
        $customer = $this->Customers->get($id);
        if ($this->Customers->delete($customer)) {
            $this->Flash->success(__('The customer has been deleted.'));
        } else {
            $this->Flash->error(__('The customer could not be deleted. Please, try again.'));
        }

        return $this->redirect(['action' => 'index']);
    }
}
