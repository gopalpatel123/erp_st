<?php
namespace App\Controller;

use App\Controller\AppController;

/**
 * AppCart Controller
 *
 * @property \App\Model\Table\AppCartTable $AppCart
 *
 * @method \App\Model\Entity\AppCart[] paginate($object = null, array $settings = [])
 */
class AppCartController extends AppController
{

    public function initialize()
    {
        parent::initialize();
        $this->Auth->allow(['addItem','viewItem','submitItem']);
    }

    /**
     * Index method
     *
     * @return \Cake\Http\Response|void
     */
    public function index()
    {
        $this->paginate = [
            'contain' => ['Items', 'Users', 'Companies', 'Locations']
        ];
        $appCart = $this->paginate($this->AppCart);

        $this->set(compact('appCart'));
        $this->set('_serialize', ['appCart']);
    }

    /**
     * View method
     *
     * @param string|null $id App Cart id.
     * @return \Cake\Http\Response|void
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function view($id = null)
    {
        $appCart = $this->AppCart->get($id, [
            'contain' => ['Items', 'Users', 'Companies', 'Locations']
        ]);

        $this->set('appCart', $appCart);
        $this->set('_serialize', ['appCart']);
    }

    /**
     * Add method
     *
     * @return \Cake\Http\Response|null Redirects on successful add, renders view otherwise.
     */
    public function add()
    {
        $appCart = $this->AppCart->newEntity();
        if ($this->request->is('post')) {
            $appCart = $this->AppCart->patchEntity($appCart, $this->request->getData());
            if ($this->AppCart->save($appCart)) {
                $this->Flash->success(__('The app cart has been saved.'));

                return $this->redirect(['action' => 'index']);
            }
            $this->Flash->error(__('The app cart could not be saved. Please, try again.'));
        }
        $items = $this->AppCart->Items->find('list', ['limit' => 200]);
        $users = $this->AppCart->Users->find('list', ['limit' => 200]);
        $companies = $this->AppCart->Companies->find('list', ['limit' => 200]);
        $locations = $this->AppCart->Locations->find('list', ['limit' => 200]);
        $this->set(compact('appCart', 'items', 'users', 'companies', 'locations'));
        $this->set('_serialize', ['appCart']);
    }

    /**
     * Edit method
     *
     * @param string|null $id App Cart id.
     * @return \Cake\Http\Response|null Redirects on successful edit, renders view otherwise.
     * @throws \Cake\Network\Exception\NotFoundException When record not found.
     */
    public function edit($id = null)
    {
        $appCart = $this->AppCart->get($id, [
            'contain' => []
        ]);
        if ($this->request->is(['patch', 'post', 'put'])) {
            $appCart = $this->AppCart->patchEntity($appCart, $this->request->getData());
            if ($this->AppCart->save($appCart)) {
                $this->Flash->success(__('The app cart has been saved.'));

                return $this->redirect(['action' => 'index']);
            }
            $this->Flash->error(__('The app cart could not be saved. Please, try again.'));
        }
        $items = $this->AppCart->Items->find('list', ['limit' => 200]);
        $users = $this->AppCart->Users->find('list', ['limit' => 200]);
        $companies = $this->AppCart->Companies->find('list', ['limit' => 200]);
        $locations = $this->AppCart->Locations->find('list', ['limit' => 200]);
        $this->set(compact('appCart', 'items', 'users', 'companies', 'locations'));
        $this->set('_serialize', ['appCart']);
    }

    /**
     * Delete method
     *
     * @param string|null $id App Cart id.
     * @return \Cake\Http\Response|null Redirects to index.
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function delete($id = null)
    {
        $this->request->allowMethod(['post', 'delete']);
        $appCart = $this->AppCart->get($id);
        if ($this->AppCart->delete($appCart)) {
            $this->Flash->success(__('The app cart has been deleted.'));
        } else {
            $this->Flash->error(__('The app cart could not be deleted. Please, try again.'));
        }

        return $this->redirect(['action' => 'index']);
    }

    public function addItem()
    {
        $appCart = $this->AppCart->newEntity();
        if ($this->request->is('post')) {
            $appCart = $this->AppCart->patchEntity($appCart, $this->request->getData());
			//pr($appCart);exit;	
            if ($this->AppCart->save($appCart)) {
                $success = true;
                $message = "Item Added";
            }
            else
            {
                $success = false;
                $message = "Unable to add item";
            }
            
        }

        $this->set(compact(['success','message']));
        $this->set('_serialize', ['success','message']);
    }

    public function viewItem()
    {
        $id = $this->request->getData('id');
        $user_id = $this->request->getData('user_id');

        if($id != null && $this->AppCart->exists(['id'=>$id]))
        {
            $appCart = $this->AppCart->get($id);
            $this->AppCart->delete($appCart);
        }
        
        $app_cart = $this->AppCart->find()->select(['AppCart.id','item'=>'Items.name','price'=>'Items.sales_rate','AppCart.quantity','AppCart.discount','item_code'=>'Items.item_code'])
        ->where(['AppCart.user_id'=>$user_id])->contain(['Items']);

        if (!empty($app_cart->toArray())) {
            $success = true;
            $message = "List Found";
        }
        else
        {
            $success = false;
            $message = "Unable to find list";
        }
           

        $this->set(compact(['success','message','app_cart']));
        $this->set('_serialize', ['success','message','app_cart']);
    }

    public function submitItem()
    {
        $user_id = $this->request->getData('user_id');
        $name = $this->request->getData('name');
        $mobile = $this->request->getData('mobile');
        $gst_no = $this->request->getData('gst_no');
        $address = $this->request->getData('address');
        //pr($this->AppCart); exit;
        $app_carts = $this->AppCart->find()->select($this->AppCart)
                    ->select(['item'=>'Items.name','price'=>'AppCart.price','gst_figure_id'=>'Items.gst_figure_id','kind_of_gst'=>'Items.kind_of_gst','first_gst_figure_id'=>'Items.first_gst_figure_id','second_gst_figure_id'=>'Items.second_gst_figure_id'])
        ->where(['AppCart.user_id'=>$user_id])->contain(['Items'=>['Shades']])->toArray();
        //pr($app_carts); exit;
        if(!empty($app_carts))
        {
		$company_id=@(int)$app_carts[0]['company_id'];
		$location_id=@(int)$app_carts[0]['location_id'];
		
		
		/* $mobile=9001855886;
		$name="Gops";
		$address="Savina"; */
		//exit;
		$customerCheck=array();
		$customerCheck=(object)$customerCheck;
		if(!empty($mobile)){
			$customerCheck=$this->AppCart->SalesInvoices->Customers->find()->where(['Customers.company_id'=>$company_id,'Customers.mobile'=>$mobile])->first();
		}
		
		if($customerCheck){
			$Customer = $this->AppCart->SalesInvoices->Customers->newEntity();
			$Customer->name = $name;
			$Customer->state_id = 46;
			$Customer->mobile = $mobile;
			$Customer->city_id = 1;
			$Customer->company_id = $company_id;
			//$Customer->gstin = $gst_no;
			$Customer->address = $address;
			$customerData=$this->AppCart->SalesInvoices->Customers->save($Customer);
			
		}else{
			$customerData=$customerCheck;
		} 
		//pr($customerData);exit;
	//pr($customerData);exit;
		//pr(['company_id'=>$company_id]); exit;
		$today=date('Y-m-d');
		$FinancialYear = $this->AppCart->SalesInvoices->Companies->FinancialYears->find()->where(['company_id'=>$company_id,'fy_from <='=>$today,'fy_to >='=>$today])->first();
		//pr($FinancialYear); exit;
		$financial_year_id=$FinancialYear->id;
		
		$Voucher_no = $this->AppCart->SalesInvoices->find()->select(['voucher_no'])->where(['SalesInvoices.company_id'=>$company_id,'SalesInvoices.financial_year_id'=>$financial_year_id])->order(['voucher_no' => 'DESC'])->first();
		if($Voucher_no)
		{
			$voucher_no=$Voucher_no->voucher_no+1;
		}
		else
		{
			$voucher_no=1;
		} 
		
		$InvoiceCalculation=$this->AppCart->find()
		->select(['total'=>$this->AppCart->find()->func()->sum('AppCart.amount'),'total_mrp'=>$this->AppCart->find()->func()->sum('AppCart.price')])
		->group(['AppCart.user_id'])
		->where(['AppCart.user_id'=>$user_id])->first();
		//$total_mrp=$InvoiceCalculation->total_mrp;
		//echo $net_total=$InvoiceCalculation->total;exit;
		
		
		$CashId = $this->AppCart->SalesInvoices->SalesInvoiceRows->Ledgers->find()
		->where(['Ledgers.cash' =>'1','Ledgers.company_id'=>$company_id])->first();
		
		$Salesdata = $this->AppCart->SalesInvoices->SalesInvoiceRows->Ledgers->find()
		->where(['Ledgers.sale_acc' =>'1','Ledgers.company_id'=>$company_id])->first();
		
		$SalesId=$Salesdata->id;
		//$SalesId=33;
		//Sales Invoice Entry
		
		$SalesInvoice = $this->AppCart->SalesInvoices->newEntity();
		$SalesInvoice->voucher_no = $voucher_no;
		$SalesInvoice->company_id = $company_id;
		$SalesInvoice->location_id = $location_id;
		$SalesInvoice->financial_year_id = $financial_year_id;
		$SalesInvoice->amount_after_tax = $InvoiceCalculation->total;
		$SalesInvoice->transaction_date = $today;
		if($customerData){
			$SalesInvoice->customer_id = 0;
			
		}else{
			$SalesInvoice->customer_id = $customerData->id;
		}
		
		
		$SalesInvoice->invoice_receipt_type = "CASH App"; 
		$SalesInvoiceData=$this->AppCart->SalesInvoices->save($SalesInvoice);
		
		
		//pr($salesInvoice); exit;
		 
		$total_mrp=0;
		$total_taxable_amount=0;
		$total_gst_amount=0;
		$total_discount=0;
		$total_amount=0;
		$tot_gst=0;
		foreach($app_carts as $app_cart){ 
			if($app_cart->kind_of_gst == "fix"){
				$GstFig=$this->AppCart->Items->GstFigures->get($app_cart->first_gst_figure_id);
				if($app_cart->discount==0){
					
					$newPrice=$app_cart->price;
					$tax_percentage=($GstFig->tax_percentage);
					$x=100+$GstFig->tax_percentage;
					//$gst_rate=($newPrice*$tax_percentage)/$x;
					$amount=$newPrice*$app_cart->quantity;
					$gst_rate=($amount*$tax_percentage)/$x;
					$total_taxable_amount+=$amount-$gst_rate;
					$total_gst_amount+=$gst_rate;
					$total_amount+=$amount;
					
					//$gst_rate=($app_cart->price*$tax_percentage)/$x;
				}else{ //pr($app_cart->price); 
					$dis=($app_cart->price*$app_cart->discount)/100;
					$total_discount+=$dis;
					$newPrice=$app_cart->price-$dis;
					$tax_percentage=($GstFig->tax_percentage);
					$x=100+$GstFig->tax_percentage;
					$amount=$newPrice*$app_cart->quantity;
					$gst_rate=($amount*$tax_percentage)/$x;
					$total_taxable_amount+=$amount-$gst_rate;
					$total_gst_amount+=$gst_rate;
					$total_amount+=$amount;
					//pr($amount-$gst_rate); pr($gst_rate); pr($amount);
				}
			}else{ 
					//$GstFig=$this->AppCart->Items->GstFigures->get($app_cart->first_gst_figure_id);
					if($app_cart->discount==0){
						
						$newPrice=$app_cart->price;
						if($newPrice < 1050){
							$GstFig=$this->AppCart->Items->GstFigures->get($app_cart->first_gst_figure_id);
						}else if($newPrice >= 1050){
							$GstFig=$this->AppCart->Items->GstFigures->get($app_cart->second_gst_figure_id);
						}
						$tax_percentage=($GstFig->tax_percentage);
						$x=100+$GstFig->tax_percentage;
						//$gst_rate=($newPrice*$tax_percentage)/$x;
						$amount=$newPrice*$app_cart->quantity;
						$gst_rate=($amount*$tax_percentage)/$x;
						$total_taxable_amount+=$amount-$gst_rate;
						$total_gst_amount+=$gst_rate;
						$total_amount+=$amount;
						
						//$gst_rate=($app_cart->price*$tax_percentage)/$x;
					}else{ 
						$dis=($app_cart->price*$app_cart->discount)/100;
						$total_discount+=$dis;
						$newPrice=$app_cart->price-$dis;
						
						if($newPrice < 1050){ 
							$GstFig=$this->AppCart->Items->GstFigures->get($app_cart->first_gst_figure_id);
						}else if($newPrice >= 1050){ 
							$GstFig=$this->AppCart->Items->GstFigures->get($app_cart->second_gst_figure_id);
						}
						//pr($GstFig); exit;
						$tax_percentage=($GstFig->tax_percentage);
						$x=100+$GstFig->tax_percentage;
						$amount=round($newPrice*$app_cart->quantity,2);
						$gst_rate=($amount*$tax_percentage)/$x;
						$total_taxable_amount+=$amount-$gst_rate;
						$total_gst_amount+=$gst_rate;
						$total_amount+=$amount;
						//pr($amount-$gst_rate); pr($gst_rate); pr($amount);
					}
				}
			
			
			//Sales Invoice Rows Entry
			$SalesInvoiceRow = $this->AppCart->SalesInvoices->SalesInvoiceRows->newEntity();
			$SalesInvoiceRow->sales_invoice_id = @$SalesInvoiceData->id;
			$SalesInvoiceRow->item_id = $app_cart->item_id;
			$SalesInvoiceRow->rate = $app_cart->price;
			$SalesInvoiceRow->discount_percentage = $app_cart->discount;
			$SalesInvoiceRow->quantity = $app_cart->quantity;
			$SalesInvoiceRow->taxable_value = @round($amount-$gst_rate,2);
			$SalesInvoiceRow->gst_value = round($gst_rate,2);
			$SalesInvoiceRow->net_amount = @round($amount,2);
			$SalesInvoiceRow->gst_figure_id = $GstFig->id;  //pr($SalesInvoiceRow); exit;
			$SalesInvoiceRow = $this->AppCart->SalesInvoices->SalesInvoiceRows->save($SalesInvoiceRow);
			
			$total_mrp+=$app_cart->price*$app_cart->quantity;
			
			//Item Ledger Entry
			$ItemLedger = $this->AppCart->SalesInvoices->ItemLedgers->newEntity();
			$ItemLedger->sales_invoice_id = @$SalesInvoiceData->id;
			$ItemLedger->sales_invoice_row_id = @$SalesInvoiceRow->id;
			$ItemLedger->company_id = $company_id;
			$ItemLedger->location_id = $location_id;
			$ItemLedger->item_id = $app_cart->item_id;
			$ItemLedger->quantity = $app_cart->quantity;
			$ItemLedger->rate = $newPrice;
			$ItemLedger->amount = $newPrice*$app_cart->quantity;
			$ItemLedger->transaction_date = $today;
			$ItemLedger->status = "out"; //pr($ItemLedger);
			$this->AppCart->SalesInvoices->ItemLedgers->save($ItemLedger);
			
			if($gst_rate){
				$gstVal=$gst_rate/2;
				$gstLedgersCGST = $this->AppCart->SalesInvoices->SalesInvoiceRows->Ledgers->find()
				->where(['Ledgers.gst_figure_id' =>$GstFig->id,'Ledgers.company_id'=>$company_id, 'Ledgers.input_output'=>'output', 'Ledgers.gst_type'=>'CGST'])->first();
				$AccountingEntrie = $this->AppCart->SalesInvoices->AccountingEntries->newEntity();
				$AccountingEntrie->ledger_id = @$gstLedgersCGST->id;
				$AccountingEntrie->debit = 0;
				$AccountingEntrie->credit = round($gstVal,2);
				$AccountingEntrie->transaction_date = $today;
				$AccountingEntrie->company_id = $company_id;
				$AccountingEntrie->sales_invoice_id = @$SalesInvoiceData->id; 
				$this->AppCart->SalesInvoices->AccountingEntries->save($AccountingEntrie);
				
				$tot_gst+=round($gstVal,2);
				
				$gstLedgersSGST = $this->AppCart->SalesInvoices->SalesInvoiceRows->Ledgers->find()
				->where(['Ledgers.gst_figure_id' =>$GstFig->id,'Ledgers.company_id'=>$company_id, 'Ledgers.input_output'=>'output', 'Ledgers.gst_type'=>'SGST'])->first();
				$AccountingEntrie = $this->AppCart->SalesInvoices->AccountingEntries->newEntity();
				$AccountingEntrie->ledger_id = @$gstLedgersSGST->id;
				$AccountingEntrie->debit = 0;
				$AccountingEntrie->credit = @round($gstVal,2);
				$AccountingEntrie->transaction_date = $today;
				$AccountingEntrie->company_id = $company_id;
				$AccountingEntrie->sales_invoice_id = @$SalesInvoiceData->id; //pr($AccountingEntrie); 
				$this->AppCart->SalesInvoices->AccountingEntries->save($AccountingEntrie);
				$tot_gst+=round($gstVal,2);
			}
		}
			
		$taxable_with_gst=round($total_taxable_amount,2)+ round($tot_gst,2);
		$x=round($total_amount,2);
		$y=round($total_amount);
		$round_off_amt=round(($y-$taxable_with_gst),2);
		
		// Sales CAsh Entry
		$AccountingEntrie = $this->AppCart->SalesInvoices->AccountingEntries->newEntity();
		$AccountingEntrie->ledger_id = @$CashId->id;
		$AccountingEntrie->debit = round($y,2);
		$AccountingEntrie->credit = 0;
		$AccountingEntrie->transaction_date = $today;
		$AccountingEntrie->company_id = $company_id;
		$AccountingEntrie->sales_invoice_id = @$SalesInvoiceData->id; //pr($AccountingEntrie); 
		$this->AppCart->SalesInvoices->AccountingEntries->save($AccountingEntrie);
		
		// Sales Account Entry
		$AccountingEntrie = $this->AppCart->SalesInvoices->AccountingEntries->newEntity();
		$AccountingEntrie->ledger_id = $SalesId;
		$AccountingEntrie->debit = 0;
		$AccountingEntrie->credit = round($total_taxable_amount,2);
		$AccountingEntrie->transaction_date = $today;
		$AccountingEntrie->company_id = $company_id;
		$AccountingEntrie->sales_invoice_id = @$SalesInvoiceData->id; //pr($AccountingEntrie); 
		$this->AppCart->SalesInvoices->AccountingEntries->save($AccountingEntrie);
		
		
		
		//pr($taxable_with_gst);
		//pr($y);
		//pr($round_off_amt); exit;
		$roundOffId = $this->AppCart->SalesInvoices->SalesInvoiceRows->Ledgers->find()
		->where(['Ledgers.company_id'=>$company_id, 'Ledgers.round_off'=>1])->first();
		
		// Sales Account Entry
		$AccountingEntrie = $this->AppCart->SalesInvoices->AccountingEntries->newEntity();
		$AccountingEntrie->ledger_id = @$roundOffId->id;
		if($round_off_amt > 0){
			$AccountingEntrie->debit = 0;
			$AccountingEntrie->credit = round($round_off_amt,2);
		}else if($round_off_amt < 0){
			$AccountingEntrie->credit = 0;
			$AccountingEntrie->debit = abs(round($round_off_amt,2));

		}
		$AccountingEntrie->transaction_date = $today;
		$AccountingEntrie->company_id = $company_id;
		$AccountingEntrie->sales_invoice_id = @$SalesInvoiceData->id; //pr($AccountingEntrie); 
		$this->AppCart->SalesInvoices->AccountingEntries->save($AccountingEntrie);
		
		$amount_before_tax=round($total_taxable_amount,2);
		$total_cgst= round($tot_gst,2)/2;
		$total_sgst= round($tot_gst,2)/2;
		$round_off_amt= round($round_off_amt,2);
		
	  $net_total=$y;
		
		$query_update = $this->AppCart->SalesInvoices->query();
		$query_update->update()
		->set(['amount_before_tax' => $amount_before_tax,
						'total_cgst' => $total_cgst,
						'total_sgst' => $total_sgst,
						'party_ledger_id' => @$CashId->id,
						'sales_ledger_id' => $SalesId,
						'discount_amount' => $total_discount,
						'round_off' => $round_off_amt])
		->where(['id'=>@$SalesInvoiceData->id])
		->execute();
		//exit;
		//pr(@$SalesInvoiceData->id); exit;
		$salesInvoice = $this->AppCart->SalesInvoices->get(@$SalesInvoiceData->id, [
            'contain' => ['Companies','SalesInvoiceRows'=>['Items'=>['Shades','Sizes']]]
        ]);
		//exit;
		 //pr($net_total); 
		//$sid=13806;
		/*$salesInvoice = $this->AppCart->SalesInvoices->get($sid, [
            'contain' => ['Customers','Companies','SalesInvoiceRows'=>['Items','GstFigures']]
        ]); */
        
		//exit;
		$query = $this->AppCart->SalesInvoices->SalesInvoiceRows->find();
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
		->where(['SalesInvoiceRows.sales_invoice_id' => $SalesInvoiceData->id])
		->group('gst_figure_id')
		->autoFields(true)
		->contain(['GstFigures']);
        $AllGstAmount = ($query);
		
		
		//pr($AllGstAmount); exit;
		
        if (!empty($app_carts)) {
            //start
            
            $success = true;
            $message = "List Found";
            $this->AppCart->deleteAll(['AppCart.user_id'=>$user_id]);
        }
        else
        {
            $success = false;
            $message = "Unable to find list";
        }
        }
        else
        {
            $success = false;
            $message = "Unable to find list";
        }
        

        $this->set(compact(['success','message','app_carts','salesInvoice','AllGstAmount','total_mrp','net_total','customerData']));
        $this->set('_serialize', ['success','message','salesInvoice','AllGstAmount','total_mrp','net_total','customerData']);
    }
}
