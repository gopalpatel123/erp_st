<?php
namespace App\Controller;

use App\Controller\AppController;

/**
 * ReferenceDetails Controller
 *
 * @property \App\Model\Table\ReferenceDetailsTable $ReferenceDetails
 *
 * @method \App\Model\Entity\ReferenceDetail[] paginate($object = null, array $settings = [])
 */
class ReferenceDetailsController extends AppController
{

    /**
     * Index method
     *
     * @return \Cake\Http\Response|void
     */
    public function index()
    {
        $this->paginate = [
            'contain' => ['Companies', 'Ledgers', 'Receipts', 'ReceiptRows', 'Payments', 'PaymentRows']
        ];
        $referenceDetails = $this->paginate($this->ReferenceDetails);

        $this->set(compact('referenceDetails'));
        $this->set('_serialize', ['referenceDetails']);
    }

	public function listRef($ledger_id=null)
    {
		$this->viewBuilder()->layout('');
        $query = $this->ReferenceDetails->find();
		$query->select(['total_debit' => $query->func()->sum('ReferenceDetails.debit'),'total_credit' => $query->func()->sum('ReferenceDetails.credit')])
		->where(['ReferenceDetails.ledger_id'=>$ledger_id,'ReferenceDetails.type !='=>'On Account'])
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
				$option[]=['text' =>$referenceDetail->ref_name.' ('.$bal.')', 'value' => $referenceDetail->ref_name,];
			}
		}
		
        $this->set(compact('option'));
        $this->set('_serialize', ['referenceDetails']);
    }
    /**
     * View method
     *
     * @param string|null $id Reference Detail id.
     * @return \Cake\Http\Response|void
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function view($id = null)
    {
        $referenceDetail = $this->ReferenceDetails->get($id, [
            'contain' => ['Companies', 'Ledgers', 'Receipts', 'ReceiptRows', 'Payments', 'PaymentRows']
        ]);

        $this->set('referenceDetail', $referenceDetail);
        $this->set('_serialize', ['referenceDetail']);
    }

    /**
     * Add method
     *
     * @return \Cake\Http\Response|null Redirects on successful add, renders view otherwise.
     */
    public function add()
    {
        $referenceDetail = $this->ReferenceDetails->newEntity();
        if ($this->request->is('post')) {
            $referenceDetail = $this->ReferenceDetails->patchEntity($referenceDetail, $this->request->getData());
            if ($this->ReferenceDetails->save($referenceDetail)) {
                $this->Flash->success(__('The reference detail has been saved.'));

                return $this->redirect(['action' => 'index']);
            }
            $this->Flash->error(__('The reference detail could not be saved. Please, try again.'));
        }
        $companies = $this->ReferenceDetails->Companies->find('list', ['limit' => 200]);
        $ledgers = $this->ReferenceDetails->Ledgers->find('list', ['limit' => 200]);
        $receipts = $this->ReferenceDetails->Receipts->find('list', ['limit' => 200]);
        $receiptRows = $this->ReferenceDetails->ReceiptRows->find('list', ['limit' => 200]);
        $payments = $this->ReferenceDetails->Payments->find('list', ['limit' => 200]);
        $paymentRows = $this->ReferenceDetails->PaymentRows->find('list', ['limit' => 200]);
        $this->set(compact('referenceDetail', 'companies', 'ledgers', 'receipts', 'receiptRows', 'payments', 'paymentRows'));
        $this->set('_serialize', ['referenceDetail']);
    }

    /**
     * Edit method
     *
     * @param string|null $id Reference Detail id.
     * @return \Cake\Http\Response|null Redirects on successful edit, renders view otherwise.
     * @throws \Cake\Network\Exception\NotFoundException When record not found.
     */
    public function edit($id = null)
    {
        $referenceDetail = $this->ReferenceDetails->get($id, [
            'contain' => []
        ]);
        if ($this->request->is(['patch', 'post', 'put'])) {
            $referenceDetail = $this->ReferenceDetails->patchEntity($referenceDetail, $this->request->getData());
            if ($this->ReferenceDetails->save($referenceDetail)) {
                $this->Flash->success(__('The reference detail has been saved.'));

                return $this->redirect(['action' => 'index']);
            }
            $this->Flash->error(__('The reference detail could not be saved. Please, try again.'));
        }
        $companies = $this->ReferenceDetails->Companies->find('list', ['limit' => 200]);
        $ledgers = $this->ReferenceDetails->Ledgers->find('list', ['limit' => 200]);
        $receipts = $this->ReferenceDetails->Receipts->find('list', ['limit' => 200]);
        $receiptRows = $this->ReferenceDetails->ReceiptRows->find('list', ['limit' => 200]);
        $payments = $this->ReferenceDetails->Payments->find('list', ['limit' => 200]);
        $paymentRows = $this->ReferenceDetails->PaymentRows->find('list', ['limit' => 200]);
        $this->set(compact('referenceDetail', 'companies', 'ledgers', 'receipts', 'receiptRows', 'payments', 'paymentRows'));
        $this->set('_serialize', ['referenceDetail']);
    }

    /**
     * Delete method
     *
     * @param string|null $id Reference Detail id.
     * @return \Cake\Http\Response|null Redirects to index.
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function delete($id = null)
    {
        $this->request->allowMethod(['post', 'delete']);
        $referenceDetail = $this->ReferenceDetails->get($id);
        if ($this->ReferenceDetails->delete($referenceDetail)) {
            $this->Flash->success(__('The reference detail has been deleted.'));
        } else {
            $this->Flash->error(__('The reference detail could not be deleted. Please, try again.'));
        }

        return $this->redirect(['action' => 'index']);
    }
	
	 public function details($ledger_id = null,$ref_name = null)
    {
        $this->viewBuilder()->layout('index_layout');
        $query = $this->ReferenceDetails->find()->where(['ReferenceDetails.ledger_id'=>$ledger_id,'ReferenceDetails.type !='=>'On Account','ReferenceDetails.ref_name'=>$ref_name])->contain(['ReceiptRows'=>['RefReceipts'],'PaymentRows'=>['RefPayments'],'PurchaseVoucherRows'=>['RefPurchaseVouchers'],'SalesVoucherRows'=>['RefSalesVouchers'],'CreditNoteRows'=>['RefCreditNotes'],'DebitNoteRows'=>['RefDebitNotes'],'JournalVoucherRows'=>['RefJournalVouchers'],'SalesInvoices','SaleReturns','PurchaseInvoices','PurchaseReturns']);
		$referenceDetails=$query;
		//pr($referenceDetails->toArray());
		//exit;
		$this->set(compact('referenceDetails'));
        $this->set('_serialize', ['referenceDetail']);
    }
}
