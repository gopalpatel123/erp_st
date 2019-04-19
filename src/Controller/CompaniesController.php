<?php
namespace App\Controller;

use App\Controller\AppController;

/**
 * Companies Controller
 *
 * @property \App\Model\Table\CompaniesTable $Companies
 *
 * @method \App\Model\Entity\Company[] paginate($object = null, array $settings = [])
 */
class CompaniesController extends AppController
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
		$this->paginate = [
            'contain' => ['States']
        ];
        $companies = $this->paginate($this->Companies->find()->where(['Companies.id'=>$company_id]));

        $this->set(compact('companies'));
        $this->set('_serialize', ['companies']);
    }

    /**
     * View method
     *
     * @param string|null $id Company id.
     * @return \Cake\Http\Response|void
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function view($id = null)
    {
        $company = $this->Companies->get($id, [
            'contain' => ['States', 'CompanyUsers']
        ]);

        $this->set('company', $company);
        $this->set('_serialize', ['company']);
    }

    /**
     * Add method
     *
     * @return \Cake\Http\Response|null Redirects on successful add, renders view otherwise.
     */
    public function add()
    {
		$this->viewBuilder()->layout('index_layout');
        $company = $this->Companies->newEntity();
        if ($this->request->is('post')) {
			$date = $this->request->data['books_beginning_from'];
			$date = explode("-",$date);
			$this->request->data['financial_year_begins_from'] =$date[2]."-04-01";
			$this->request->data['books_beginning_from'] = date("Y-m-d",strtotime($this->request->data['books_beginning_from']));
			$this->request->data['financial_year_valid_to'] = date("Y-m-d",strtotime($this->request->data['financial_year_valid_to']));
			$company = $this->Companies->patchEntity($company, $this->request->getData());
			if ($this->Companies->save($company)) 
			{
				$StatutoryInfos=[
					['nature_of_group_id'=>2, 'name'=>'Branch / Divisions', 'parent_id'=>NULL],
					['nature_of_group_id'=>2, 'name'=>'Capital Account', 'parent_id'=>NULL],
					['nature_of_group_id'=>1, 'name'=>'Current Assets', 'parent_id'=>NULL],
					['nature_of_group_id'=>2, 'name'=>'Current Liabilities', 'parent_id'=>NULL],
					['nature_of_group_id'=>4, 'name'=>'Direct Expenses', 'parent_id'=>NULL],
					['nature_of_group_id'=>3, 'name'=>'Direct Incomes', 'parent_id'=>NULL],
					['nature_of_group_id'=>1, 'name'=>'Fixed Assets', 'parent_id'=>NULL],
					['nature_of_group_id'=>4, 'name'=>'Indirect Expenses', 'parent_id'=>NULL],
					['nature_of_group_id'=>3, 'name'=>'Indirect Incomes', 'parent_id'=>NULL],
					['nature_of_group_id'=>1, 'name'=>'Investments', 'parent_id'=>NULL],
					['nature_of_group_id'=>2, 'name'=>'Loans (Liability)', 'parent_id'=>NULL],
					['nature_of_group_id'=>1, 'name'=>'Misc. Expenses (ASSET)', 'parent_id'=>NULL],
					['nature_of_group_id'=>4, 'name'=>'Purchase Accounts', 'parent_id'=>NULL],
					['nature_of_group_id'=>3, 'name'=>'Sales Accounts', 'parent_id'=>NULL],
					['nature_of_group_id'=>2, 'name'=>'Suspense A/c', 'parent_id'=>NULL]
				];
				//Statutory Info//
				foreach($StatutoryInfos as $StatutoryInfo){
					$accountingGroup = $this->Companies->AccountingGroups->newEntity();
					$accountingGroup->nature_of_group_id=$StatutoryInfo['nature_of_group_id'];
					$accountingGroup->name=$StatutoryInfo['name'];
					$accountingGroup->parent_id=$StatutoryInfo['parent_id'];
					$accountingGroup->company_id=$company->id;
					if($accountingGroup->name=='Suspense A/c')
					{
						$accountingGroup->credit_note_all_row=1;
						$accountingGroup->receipt_ledger=1;
						$accountingGroup->debit_note_all_row=1;
					}
					if($accountingGroup->name=='Loans (Liability)')
					{
						$accountingGroup->credit_note_all_row=1;
						$accountingGroup->receipt_ledger=1;
						$accountingGroup->debit_note_all_row=1;
					}
					if($accountingGroup->name=='Investments')
					{
						$accountingGroup->credit_note_all_row=1;
						$accountingGroup->receipt_ledger=1;
						$accountingGroup->debit_note_all_row=1;
					}
					if($accountingGroup->name=='Capital Account')
					{
						$accountingGroup->credit_note_all_row=1;
						$accountingGroup->receipt_ledger=1;
						$accountingGroup->debit_note_all_row=1;
					}
					if($accountingGroup->name=='Current Assets')
					{
						$accountingGroup->purchase_voucher_all_ledger=1;
						$accountingGroup->credit_note_all_row=1;
						$accountingGroup->receipt_ledger=1;
						$accountingGroup->debit_note_all_row=1;
						$accountingGroup->sales_voucher_all_ledger=1;
					}
					if($accountingGroup->name=='Current Liabilities')
					{
						$accountingGroup->purchase_voucher_all_ledger=1;
						$accountingGroup->credit_note_all_row=1;
						$accountingGroup->receipt_ledger=1;
						$accountingGroup->debit_note_all_row=1;
						$accountingGroup->sales_voucher_all_ledger=1;
					}
					if($accountingGroup->name=='Direct Incomes')
					{
						$accountingGroup->purchase_voucher_all_ledger=1;
						$accountingGroup->credit_note_all_row=1;
						$accountingGroup->receipt_ledger=1;
						$accountingGroup->debit_note_all_row=1;
						$accountingGroup->sales_voucher_all_ledger=1;
					}
					if($accountingGroup->name=='Direct Expenses')
					{
						$accountingGroup->purchase_voucher_all_ledger=1;
						$accountingGroup->credit_note_all_row=1;
						$accountingGroup->receipt_ledger=1;
						$accountingGroup->debit_note_all_row=1;
						$accountingGroup->sales_voucher_all_ledger=1;
					}
					if($accountingGroup->name=='Indirect Incomes')
					{
						$accountingGroup->purchase_voucher_all_ledger=1;
						$accountingGroup->credit_note_all_row=1;
						$accountingGroup->receipt_ledger=1;
						$accountingGroup->debit_note_all_row=1;
						$accountingGroup->sales_voucher_all_ledger=1;
					}
					if($accountingGroup->name=='Indirect Expenses')
					{
						$accountingGroup->purchase_voucher_all_ledger=1;
						$accountingGroup->credit_note_all_row=1;
						$accountingGroup->receipt_ledger=1;
						$accountingGroup->debit_note_all_row=1;
						$accountingGroup->sales_voucher_all_ledger=1;
					}
					if($accountingGroup->name=='Misc. Expenses (ASSET)')
					{
						$accountingGroup->purchase_voucher_all_ledger=1;
						$accountingGroup->credit_note_all_row=1;
						$accountingGroup->receipt_ledger=1;
						$accountingGroup->debit_note_all_row=1;
					}
					if($accountingGroup->name=='Branch / Divisions')
					{
						$accountingGroup->purchase_voucher_purchase_ledger=1;
						$accountingGroup->credit_note_all_row=1;
						$accountingGroup->credit_note_first_row=1;
						$accountingGroup->receipt_ledger=1;
						$accountingGroup->debit_note_first_row=1;
						$accountingGroup->debit_note_all_row=1;
						$accountingGroup->sales_voucher_first_ledger=1;
					}
					if($accountingGroup->name=='Purchase Accounts')
					{
						$accountingGroup->purchase_voucher_all_ledger=1;
						$accountingGroup->purchase_voucher_purchase_ledger=1;
						$accountingGroup->credit_note_all_row=1;
						$accountingGroup->receipt_ledger=1;
						$accountingGroup->debit_note_all_row=1;
					}
					if($accountingGroup->name=='Sales Accounts')
					{
						$accountingGroup->sale_invoice_sales_account=1;
						$accountingGroup->credit_note_all_row=1;
						$accountingGroup->receipt_ledger=1;
						$accountingGroup->debit_note_all_row=1;
						$accountingGroup->sales_voucher_all_ledger=1;
						$accountingGroup->sales_voucher_sales_ledger=1;
					}
					$this->Companies->AccountingGroups->save($accountingGroup);
				}
				
				$accountingParentGroup=$this->Companies->AccountingGroups->find()->where(['name'=>'Capital Account','company_id'=>$company->id])->first();
				$accountingGroup = $this->Companies->AccountingGroups->newEntity();
				$accountingGroup->nature_of_group_id=NULL;
				$accountingGroup->name='Reserves & Surplus';
				$accountingGroup->parent_id=$accountingParentGroup->id;
				$accountingGroup->company_id=$company->id;
				$accountingGroup->credit_note_all_row=1;
				$accountingGroup->receipt_ledger=1;
				$accountingGroup->debit_note_all_row=1;
				$this->Companies->AccountingGroups->save($accountingGroup);
				
				$accountingParentGroup=$this->Companies->AccountingGroups->find()->where(['name'=>'Current Assets','company_id'=>$company->id])->first();
				$accountingGroup = $this->Companies->AccountingGroups->newEntity();
				$accountingGroup->nature_of_group_id=NULL;
				$accountingGroup->name='Bank Accounts';
				$accountingGroup->parent_id=$accountingParentGroup->id;
				$accountingGroup->company_id=$company->id;
				$accountingGroup->purchase_voucher_first_ledger=1;
				$accountingGroup->credit_note_first_row=1;
				$accountingGroup->receipt_ledger=1;
				$accountingGroup->debit_note_first_row=1;
				$accountingGroup->sales_voucher_first_ledger=1;
				$this->Companies->AccountingGroups->save($accountingGroup);
				
				$accountingGroup = $this->Companies->AccountingGroups->newEntity();
				$accountingGroup->nature_of_group_id=NULL;
				$accountingGroup->name='Cash-in-hand';
				$accountingGroup->parent_id=$accountingParentGroup->id;
				$accountingGroup->company_id=$company->id;
				$accountingGroup->purchase_voucher_party=1;
				$accountingGroup->sale_invoice_party=1;
				$accountingGroup->credit_note_first_row=1;
				$accountingGroup->receipt_ledger=1;
				$accountingGroup->purchase_voucher_first_ledger=1;
				$accountingGroup->debit_note_first_row=1;
				$accountingGroup->sales_voucher_first_ledger=1;
				$this->Companies->AccountingGroups->save($accountingGroup);
				
				$accountingGroup = $this->Companies->AccountingGroups->newEntity();
				$accountingGroup->nature_of_group_id=NULL;
				$accountingGroup->name='Deposits (Asset)';
				$accountingGroup->parent_id=$accountingParentGroup->id;
				$accountingGroup->company_id=$company->id;
				$accountingGroup->credit_note_all_row=1;
				$accountingGroup->receipt_ledger=1;
				$accountingGroup->debit_note_all_row=1;
				$this->Companies->AccountingGroups->save($accountingGroup);
				
				$accountingGroup = $this->Companies->AccountingGroups->newEntity();
				$accountingGroup->nature_of_group_id=NULL;
				$accountingGroup->name='Loans & Advances (Asset)';
				$accountingGroup->parent_id=$accountingParentGroup->id;
				$accountingGroup->company_id=$company->id;
				$accountingGroup->credit_note_all_row=1;
				$accountingGroup->receipt_ledger=1;
				$accountingGroup->debit_note_all_row=1;
				$this->Companies->AccountingGroups->save($accountingGroup);
				
				$accountingGroup = $this->Companies->AccountingGroups->newEntity();
				$accountingGroup->nature_of_group_id=NULL;
				$accountingGroup->name='Stock-in-hand';
				$accountingGroup->parent_id=$accountingParentGroup->id;
				$accountingGroup->company_id=$company->id;
				$this->Companies->AccountingGroups->save($accountingGroup);
				
				$accountingGroup = $this->Companies->AccountingGroups->newEntity();
				$accountingGroup->nature_of_group_id=NULL;
				$accountingGroup->name='Sundry Debtors';
				$accountingGroup->parent_id=$accountingParentGroup->id;
				$accountingGroup->customer=1;
				$accountingGroup->sale_invoice_party=1;
				$accountingGroup->credit_note_party=1;
				$accountingGroup->company_id=$company->id;
				$accountingGroup->purchase_voucher_first_ledger=1;
				$accountingGroup->credit_note_all_row=1;
				$accountingGroup->credit_note_first_row=1;
				$accountingGroup->receipt_ledger=1;
				$accountingGroup->debit_note_first_row=1;
				$accountingGroup->debit_note_all_row=1;
				$accountingGroup->sales_voucher_first_ledger=1;
				$this->Companies->AccountingGroups->save($accountingGroup);
				
				$accountingParentGroup=$this->Companies->AccountingGroups->find()->where(['name'=>'Current Liabilities','company_id'=>$company->id])->first();
				$accountingGroup = $this->Companies->AccountingGroups->newEntity();
				$accountingGroup->nature_of_group_id=NULL;
				$accountingGroup->name='Duties & Taxes';
				$accountingGroup->parent_id=$accountingParentGroup->id;
				$accountingGroup->company_id=$company->id;
				$accountingGroup->purchase_voucher_all_ledger=1;
				$accountingGroup->credit_note_all_row=1;
				$accountingGroup->receipt_ledger=1;
				$accountingGroup->debit_note_all_row=1;
				$accountingGroup->sales_voucher_all_ledger=1;
				$this->Companies->AccountingGroups->save($accountingGroup);
				
				$accountingGroup = $this->Companies->AccountingGroups->newEntity();
				$accountingGroup->nature_of_group_id=NULL;
				$accountingGroup->name='Provisions';
				$accountingGroup->parent_id=$accountingParentGroup->id;
				$accountingGroup->company_id=$company->id;
				$accountingGroup->credit_note_all_row=1;
				$accountingGroup->receipt_ledger=1;
				$accountingGroup->debit_note_all_row=1;
				$this->Companies->AccountingGroups->save($accountingGroup);
				
				$accountingGroup = $this->Companies->AccountingGroups->newEntity();
				$accountingGroup->nature_of_group_id=NULL;
				$accountingGroup->name='Sundry Creditors';
				$accountingGroup->parent_id=$accountingParentGroup->id;
				$accountingGroup->supplier=1;
				$accountingGroup->company_id=$company->id;
				$accountingGroup->purchase_voucher_party=1;
				$accountingGroup->purchase_voucher_first_ledger=1;
				$accountingGroup->credit_note_all_row=1;
				$accountingGroup->credit_note_first_row=1;
				$accountingGroup->receipt_ledger=1;
				$accountingGroup->debit_note_first_row=1;
				$accountingGroup->debit_note_all_row=1;
				$accountingGroup->sales_voucher_first_ledger=1;
				$this->Companies->AccountingGroups->save($accountingGroup);
				
				$accountingParentGroup=$this->Companies->AccountingGroups->find()->where(['name'=>'Loans (Liability)','company_id'=>$company->id])->first();
				$accountingGroup = $this->Companies->AccountingGroups->newEntity();
				$accountingGroup->nature_of_group_id=NULL;
				$accountingGroup->name='Bank OD A/c';
				$accountingGroup->parent_id=$accountingParentGroup->id;
				$accountingGroup->company_id=$company->id;
				$accountingGroup->purchase_voucher_first_ledger=1;
				$accountingGroup->credit_note_first_row=1;
				$accountingGroup->receipt_ledger=1;
				$accountingGroup->debit_note_first_row=1;
				$accountingGroup->sales_voucher_first_ledger=1;
				$this->Companies->AccountingGroups->save($accountingGroup);
				
				$accountingGroup = $this->Companies->AccountingGroups->newEntity();
				$accountingGroup->nature_of_group_id=NULL;
				$accountingGroup->name='Secured Loans';
				$accountingGroup->parent_id=$accountingParentGroup->id;
				$accountingGroup->company_id=$company->id;
				$accountingGroup->credit_note_all_row=1;
				$accountingGroup->receipt_ledger=1;
				$accountingGroup->debit_note_all_row=1;
				$this->Companies->AccountingGroups->save($accountingGroup);
				
				$accountingGroup = $this->Companies->AccountingGroups->newEntity();
				$accountingGroup->nature_of_group_id=NULL;
				$accountingGroup->name='Unsecured Loans';
				$accountingGroup->parent_id=$accountingParentGroup->id;
				$accountingGroup->company_id=$company->id;
				$accountingGroup->credit_note_all_row=1;
				$accountingGroup->receipt_ledger=1;
				$accountingGroup->debit_note_all_row=1;
				$this->Companies->AccountingGroups->save($accountingGroup);
				
				$accountingParentGroup=$this->Companies->AccountingGroups->find()->where(['name'=>'Duties & Taxes','company_id'=>$company->id])->first();
				$accountingGroup = $this->Companies->AccountingGroups->newEntity();
				$accountingGroup->nature_of_group_id=NULL;
				$accountingGroup->name='Input GST';
				$accountingGroup->parent_id=$accountingParentGroup->id;
				$accountingGroup->company_id=$company->id;
				$this->Companies->AccountingGroups->save($accountingGroup);
				
				$accountingGroup = $this->Companies->AccountingGroups->newEntity();
				$accountingGroup->nature_of_group_id=NULL;
				$accountingGroup->name='Output GST';
				$accountingGroup->parent_id=$accountingParentGroup->id;
				$accountingGroup->company_id=$company->id;
				$this->Companies->AccountingGroups->save($accountingGroup);
				
				
				//Financial year entry//
				$financialY=date('Y', strtotime($company->financial_year_begins_from));
				$financialY=$financialY+1;
				$FYendDate=$financialY.'-3-31';
				
				$financialYear = $this->Companies->FinancialYears->newEntity();
				$financialYear->fy_from=date('Y-m-d',strtotime($company->books_beginning_from));
				$financialYear->fy_to=$FYendDate;
				$financialYear->status='open';
				$financialYear->company_id=$company->id;
				$this->Companies->FinancialYears->save($financialYear);
				
				//gst figure add
				$GstFigureDetails=[
					['name'=>'0%',  'tax_percentage'=>0],
					['name'=>'5%',  'tax_percentage'=>5],
					['name'=>'12%', 'tax_percentage'=>12],
					['name'=>'18%', 'tax_percentage'=>18],
					['name'=>'28%', 'tax_percentage'=>28],
				];
				
				$gstFigureId=[];
				foreach($GstFigureDetails as $GstFigureDetail)
				{ 
					$GstFigure = $this->Companies->GstFigures->newEntity();
					$GstFigure->name           = $GstFigureDetail['name'];
					$GstFigure->company_id     = $company->id;
					$GstFigure->tax_percentage = $GstFigureDetail['tax_percentage'];
					$this->Companies->GstFigures->save($GstFigure);
					$gstFigureId[] =$GstFigure->id;
				}
				
				//gst figure ledger entry//
				$gstInput = $this->Companies->AccountingGroups->find()->where(['name'=>'Input GST','company_id'=>$company->id])->first();
				$gstOutput = $this->Companies->AccountingGroups->find()->where(['name'=>'Output GST','company_id'=>$company->id])->first();
				$round_off_id = $this->Companies->AccountingGroups->find()->where(['name'=>'Indirect Expenses','company_id'=>$company->id])->first();
				$cash_id = $this->Companies->AccountingGroups->find()->where(['name'=>'Cash-in-hand','company_id'=>$company->id])->first();
				$gstLedgerEntrys=[
					['name'=>'0% CGST (input)', 'accounting_group_id'=>$gstInput->id,'company_id'=>$company->id,'bill_to_bill_accounting'=>'no','gst_figure_id'=>$gstFigureId[0],'tax_percentage'=>0,'input_output'=>'input','gst_type'=>'CGST','round_off'=>0,'cash'=>0,'flag'=>1],
					['name'=>'0% SGST (input)', 'accounting_group_id'=>$gstInput->id,'company_id'=>$company->id,'bill_to_bill_accounting'=>'no','gst_figure_id'=>$gstFigureId[0],'tax_percentage'=>0,'input_output'=>'input','gst_type'=>'SGST','round_off'=>0,'cash'=>0,'flag'=>1],
					['name'=>'0% IGST (input)', 'accounting_group_id'=>$gstInput->id,'company_id'=>$company->id,'bill_to_bill_accounting'=>'no','gst_figure_id'=>$gstFigureId[0],'tax_percentage'=>0,'input_output'=>'input','gst_type'=>'IGST','round_off'=>0,'cash'=>0,'flag'=>1],
					['name'=>'0% CGST (output)', 'accounting_group_id'=>$gstOutput->id,'company_id'=>$company->id,'bill_to_bill_accounting'=>'no','gst_figure_id'=>$gstFigureId[0],'tax_percentage'=>0,'input_output'=>'output','gst_type'=>'CGST','round_off'=>0,'cash'=>0,'flag'=>1],
					['name'=>'0% SGST (output)', 'accounting_group_id'=>$gstOutput->id,'company_id'=>$company->id,'bill_to_bill_accounting'=>'no','gst_figure_id'=>$gstFigureId[0],'tax_percentage'=>0,'input_output'=>'output','gst_type'=>'SGST','round_off'=>0,'cash'=>0,'flag'=>1],
					['name'=>'0% IGST (output)', 'accounting_group_id'=>$gstOutput->id,'company_id'=>$company->id,'bill_to_bill_accounting'=>'no','gst_figure_id'=>$gstFigureId[0],'tax_percentage'=>0,'input_output'=>'output','gst_type'=>'IGST','round_off'=>0,'cash'=>0,'flag'=>1],
					['name'=>'2.5% CGST (input)', 'accounting_group_id'=>$gstInput->id,'company_id'=>$company->id,'bill_to_bill_accounting'=>'no','gst_figure_id'=>$gstFigureId[1],'tax_percentage'=>0,'input_output'=>'input','gst_type'=>'CGST','round_off'=>0,'cash'=>0,'flag'=>1],
					['name'=>'2.5% SGST (input)', 'accounting_group_id'=>$gstInput->id,'company_id'=>$company->id,'bill_to_bill_accounting'=>'no','gst_figure_id'=>$gstFigureId[1],'tax_percentage'=>0,'input_output'=>'input','gst_type'=>'SGST','round_off'=>0,'cash'=>0,'flag'=>1],
					['name'=>'5% IGST (input)', 'accounting_group_id'=>$gstInput->id,'company_id'=>$company->id,'bill_to_bill_accounting'=>'no','gst_figure_id'=>$gstFigureId[1],'tax_percentage'=>0,'input_output'=>'input','gst_type'=>'IGST','round_off'=>0,'cash'=>0,'flag'=>1],
					['name'=>'2.5% CGST (output)', 'accounting_group_id'=>$gstOutput->id,'company_id'=>$company->id,'bill_to_bill_accounting'=>'no','gst_figure_id'=>$gstFigureId[1],'tax_percentage'=>0,'input_output'=>'output','gst_type'=>'CGST','round_off'=>0,'cash'=>0,'flag'=>1],
					['name'=>'2.5% SGST (output)', 'accounting_group_id'=>$gstOutput->id,'company_id'=>$company->id,'bill_to_bill_accounting'=>'no','gst_figure_id'=>$gstFigureId[1],'tax_percentage'=>0,'input_output'=>'output','gst_type'=>'SGST','round_off'=>0,'cash'=>0,'flag'=>1],
					['name'=>'5% IGST (output)', 'accounting_group_id'=>$gstOutput->id,'company_id'=>$company->id,'bill_to_bill_accounting'=>'no','gst_figure_id'=>$gstFigureId[1],'tax_percentage'=>0,'input_output'=>'output','gst_type'=>'IGST','round_off'=>0,'cash'=>0,'flag'=>1],
					['name'=>'6% CGST (input)', 'accounting_group_id'=>$gstInput->id,'company_id'=>$company->id,'bill_to_bill_accounting'=>'no','gst_figure_id'=>$gstFigureId[2],'tax_percentage'=>0,'input_output'=>'input','gst_type'=>'CGST','round_off'=>0,'cash'=>0,'flag'=>1],
					['name'=>'6% SGST (input)', 'accounting_group_id'=>$gstInput->id,'company_id'=>$company->id,'bill_to_bill_accounting'=>'no','gst_figure_id'=>$gstFigureId[2],'tax_percentage'=>0,'input_output'=>'input','gst_type'=>'SGST','round_off'=>0,'cash'=>0,'flag'=>1],
					['name'=>'12% IGST (input)', 'accounting_group_id'=>$gstInput->id,'company_id'=>$company->id,'bill_to_bill_accounting'=>'no','gst_figure_id'=>$gstFigureId[2],'tax_percentage'=>0,'input_output'=>'input','gst_type'=>'IGST','round_off'=>0,'cash'=>0,'flag'=>1],
					['name'=>'6% CGST (output)', 'accounting_group_id'=>$gstOutput->id,'company_id'=>$company->id,'bill_to_bill_accounting'=>'no','gst_figure_id'=>$gstFigureId[2],'tax_percentage'=>0,'input_output'=>'output','gst_type'=>'CGST','round_off'=>0,'cash'=>0,'flag'=>1],
					['name'=>'6% SGST (output)', 'accounting_group_id'=>$gstOutput->id,'company_id'=>$company->id,'bill_to_bill_accounting'=>'no','gst_figure_id'=>$gstFigureId[2],'tax_percentage'=>0,'input_output'=>'output','gst_type'=>'SGST','round_off'=>0,'cash'=>0,'flag'=>1],
					['name'=>'12% IGST (output)', 'accounting_group_id'=>$gstOutput->id,'company_id'=>$company->id,'bill_to_bill_accounting'=>'no','gst_figure_id'=>$gstFigureId[2],'tax_percentage'=>0,'input_output'=>'output','gst_type'=>'IGST','round_off'=>0,'cash'=>0,'flag'=>1],
					['name'=>'9% CGST (input)', 'accounting_group_id'=>$gstInput->id,'company_id'=>$company->id,'bill_to_bill_accounting'=>'no','gst_figure_id'=>$gstFigureId[3],'tax_percentage'=>0,'input_output'=>'input','gst_type'=>'CGST','round_off'=>0,'cash'=>0,'flag'=>1],
					['name'=>'9% SGST (input)', 'accounting_group_id'=>$gstInput->id,'company_id'=>$company->id,'bill_to_bill_accounting'=>'no','gst_figure_id'=>$gstFigureId[3],'tax_percentage'=>0,'input_output'=>'input','gst_type'=>'SGST','round_off'=>0,'cash'=>0,'flag'=>1],
					['name'=>'18% IGST (input)', 'accounting_group_id'=>$gstInput->id,'company_id'=>$company->id,'bill_to_bill_accounting'=>'no','gst_figure_id'=>$gstFigureId[3],'tax_percentage'=>0,'input_output'=>'input','gst_type'=>'IGST','round_off'=>0,'cash'=>0,'flag'=>1],
					['name'=>'9% CGST (output)', 'accounting_group_id'=>$gstOutput->id,'company_id'=>$company->id,'bill_to_bill_accounting'=>'no','gst_figure_id'=>$gstFigureId[3],'tax_percentage'=>0,'input_output'=>'output','gst_type'=>'CGST','round_off'=>0,'cash'=>0,'flag'=>1],
					['name'=>'9% SGST (output)', 'accounting_group_id'=>$gstOutput->id,'company_id'=>$company->id,'bill_to_bill_accounting'=>'no','gst_figure_id'=>$gstFigureId[3],'tax_percentage'=>0,'input_output'=>'output','gst_type'=>'SGST','round_off'=>0,'cash'=>0,'flag'=>1],
					['name'=>'18% IGST (output)', 'accounting_group_id'=>$gstOutput->id,'company_id'=>$company->id,'bill_to_bill_accounting'=>'no','gst_figure_id'=>$gstFigureId[3],'tax_percentage'=>0,'input_output'=>'output','gst_type'=>'IGST','round_off'=>0,'cash'=>0,'flag'=>1],
					['name'=>'14% CGST (input)', 'accounting_group_id'=>$gstInput->id,'company_id'=>$company->id,'bill_to_bill_accounting'=>'no','gst_figure_id'=>$gstFigureId[4],'tax_percentage'=>0,'input_output'=>'input','gst_type'=>'CGST','round_off'=>0,'cash'=>0,'flag'=>1],
					['name'=>'14% SGST (input)', 'accounting_group_id'=>$gstInput->id,'company_id'=>$company->id,'bill_to_bill_accounting'=>'no','gst_figure_id'=>$gstFigureId[4],'tax_percentage'=>0,'input_output'=>'input','gst_type'=>'SGST','round_off'=>0,'cash'=>0,'flag'=>1],
					['name'=>'28% IGST (input)', 'accounting_group_id'=>$gstInput->id,'company_id'=>$company->id,'bill_to_bill_accounting'=>'no','gst_figure_id'=>$gstFigureId[4],'tax_percentage'=>0,'input_output'=>'input','gst_type'=>'IGST','round_off'=>0,'cash'=>0,'flag'=>1],
					['name'=>'14% CGST (output)', 'accounting_group_id'=>$gstOutput->id,'company_id'=>$company->id,'bill_to_bill_accounting'=>'no','gst_figure_id'=>$gstFigureId[4],'tax_percentage'=>0,'input_output'=>'output','gst_type'=>'CGST','round_off'=>0,'cash'=>0,'flag'=>1],
					['name'=>'14% SGST (output)', 'accounting_group_id'=>$gstOutput->id,'company_id'=>$company->id,'bill_to_bill_accounting'=>'no','gst_figure_id'=>$gstFigureId[4],'tax_percentage'=>0,'input_output'=>'output','gst_type'=>'SGST','round_off'=>0,'cash'=>0,'flag'=>1],
					['name'=>'28% IGST (output)', 'accounting_group_id'=>$gstOutput->id,'company_id'=>$company->id,'bill_to_bill_accounting'=>'no','gst_figure_id'=>$gstFigureId[4],'tax_percentage'=>0,'input_output'=>'output','gst_type'=>'IGST','round_off'=>0,'cash'=>0,'flag'=>1],
					['name'=>'Round off', 'accounting_group_id'=>$round_off_id->id,'company_id'=>$company->id,'bill_to_bill_accounting'=>'no','gst_figure_id'=>null,'tax_percentage'=>0,'input_output'=>null,'gst_type'=>null,'round_off'=>1,'cash'=>0,'flag'=>1],
					['name'=>'Cash', 'accounting_group_id'=>$cash_id->id,'company_id'=>$company->id,'bill_to_bill_accounting'=>'no','gst_figure_id'=>null,'tax_percentage'=>0,'input_output'=>null,'gst_type'=>null,'round_off'=>0,'cash'=>1,'flag'=>1]
				];
				
				foreach($gstLedgerEntrys as $gstLedgerEntry)
				{
					$Ledgers = $this->Companies->Ledgers->newEntity();
					$Ledgers->name                    = $gstLedgerEntry['name'];
					$Ledgers->accounting_group_id     = $gstLedgerEntry['accounting_group_id'];
					$Ledgers->company_id              = $company->id;
					$Ledgers->bill_to_bill_accounting = $gstLedgerEntry['bill_to_bill_accounting'];
					$Ledgers->gst_figure_id           = $gstLedgerEntry['gst_figure_id'];
					$Ledgers->tax_percentage          = $gstLedgerEntry['tax_percentage'];
					$Ledgers->input_output            = $gstLedgerEntry['input_output'];
					$Ledgers->gst_type                = $gstLedgerEntry['gst_type'];
					$Ledgers->round_off               = $gstLedgerEntry['round_off'];
					$Ledgers->cash                    = $gstLedgerEntry['cash'];
					$Ledgers->flag					  = $gstLedgerEntry['flag'];
					$this->Companies->Ledgers->save($Ledgers);
				}
                $this->Flash->success(__('The company has been saved.'));

                return $this->redirect(['action' => 'index']);
            }
			else{
				$this->Flash->error(__('The company could not be saved. Please, try again.'));
			}
    }
		$states = $this->Companies->States->  find('list',
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
        $this->set(compact('company', 'states'));
        $this->set('_serialize', ['company']);
    }

    /**
     * Edit method
     *
     * @param string|null $id Company id.
     * @return \Cake\Http\Response|null Redirects on successful edit, renders view otherwise.
     * @throws \Cake\Network\Exception\NotFoundException When record not found.
     */
    public function edit($id = null)
    {
		$this->viewBuilder()->layout('index_layout');
        $company = $this->Companies->get($id, [
            'contain' => []
        ]);
        if ($this->request->is(['patch', 'post', 'put'])) {
			$date = $this->request->data['books_beginning_from'];
			$date = explode("-",$date);
			$this->request->data['financial_year_begins_from'] =$date[2]."-04-01";
			$this->request->data['books_beginning_from'] = date("Y-m-d",strtotime($this->request->data['books_beginning_from']));
			$this->request->data['financial_year_valid_to'] = date("Y-m-d",strtotime($this->request->data['financial_year_valid_to']));
            $company = $this->Companies->patchEntity($company, $this->request->getData());
            if ($this->Companies->save($company)) {
                $this->Flash->success(__('The company has been saved.'));

                return $this->redirect(['action' => 'index']);
            }
            $this->Flash->error(__('The company could not be saved. Please, try again.'));
        }
        $states = $this->Companies->States->find('list');
        $this->set(compact('company', 'states'));
        $this->set('_serialize', ['company']);
    }

    /**
     * Delete method
     *
     * @param string|null $id Company id.
     * @return \Cake\Http\Response|null Redirects to index.
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function delete($id = null)
    {
        $this->request->allowMethod(['post', 'delete']);
        $company = $this->Companies->get($id);
        if ($this->Companies->delete($company)) {
            $this->Flash->success(__('The company has been deleted.'));
        } else {
            $this->Flash->error(__('The company could not be deleted. Please, try again.'));
        }

        return $this->redirect(['action' => 'index']);
    }
}
