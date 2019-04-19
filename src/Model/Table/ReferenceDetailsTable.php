<?php
namespace App\Model\Table;

use Cake\ORM\Query;
use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\Validation\Validator;
use Cake\Event\Event;
use ArrayObject;
/**
 * ReferenceDetails Model
 *
 * @property \App\Model\Table\CompaniesTable|\Cake\ORM\Association\BelongsTo $Companies
 * @property \App\Model\Table\LedgersTable|\Cake\ORM\Association\BelongsTo $Ledgers
 * @property \App\Model\Table\ReceiptsTable|\Cake\ORM\Association\BelongsTo $Receipts
 * @property \App\Model\Table\ReceiptRowsTable|\Cake\ORM\Association\BelongsTo $ReceiptRows
 * @property \App\Model\Table\PaymentsTable|\Cake\ORM\Association\BelongsTo $Payments
 * @property \App\Model\Table\PaymentRowsTable|\Cake\ORM\Association\BelongsTo $PaymentRows
 *
 * @method \App\Model\Entity\ReferenceDetail get($primaryKey, $options = [])
 * @method \App\Model\Entity\ReferenceDetail newEntity($data = null, array $options = [])
 * @method \App\Model\Entity\ReferenceDetail[] newEntities(array $data, array $options = [])
 * @method \App\Model\Entity\ReferenceDetail|bool save(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\ReferenceDetail patchEntity(\Cake\Datasource\EntityInterface $entity, array $data, array $options = [])
 * @method \App\Model\Entity\ReferenceDetail[] patchEntities($entities, array $data, array $options = [])
 * @method \App\Model\Entity\ReferenceDetail findOrCreate($search, callable $callback = null, $options = [])
 */
class ReferenceDetailsTable extends Table
{

    /**
     * Initialize method
     *
     * @param array $config The configuration for the Table.
     * @return void
     */
    public function initialize(array $config)
    {
        parent::initialize($config);

        $this->setTable('reference_details');
        $this->setDisplayField('ref_name');
        $this->setPrimaryKey('id');

        $this->belongsTo('Companies', [
            'foreignKey' => 'company_id',
            'joinType' => 'INNER'
        ]);
        $this->belongsTo('Ledgers', [
            'foreignKey' => 'ledger_id',
            'joinType' => 'INNER'
        ]);
		$this->belongsTo('Customers', [
            'foreignKey' => 'customer_id',
            'joinType' => 'LEFT'
        ]);
		$this->belongsTo('Suppliers', [
            'foreignKey' => 'supplier_id',
            'joinType' => 'LEFT'
        ]);
		
        $this->belongsTo('Receipts', [
            'foreignKey' => 'receipt_id',
            'joinType' => 'LEFT'
        ]);
        $this->belongsTo('ReceiptRows', [
            'foreignKey' => 'receipt_row_id',
            'joinType' => 'LEFT'
        ]);
        $this->belongsTo('Payments', [
            'foreignKey' => 'payment_id',
            'joinType' => 'LEFT'
        ]);
        $this->belongsTo('PaymentRows', [
            'foreignKey' => 'payment_row_id',
            'joinType' => 'LEFT'
        ]);

		$this->belongsTo('CreditNoteRows', [
            'foreignKey' => 'credit_note_row_id',
			   'joinType' => 'LEFT'
        ]);

		$this->belongsTo('DebitNoteRows', [
            'foreignKey' => 'debit_note_row_id',
			   'joinType' => 'LEFT'
        ]);
		
		$this->belongsTo('SalesVoucherRows', [
            'foreignKey' => 'sales_voucher_row_id',
			'joinType' => 'LEFT'
        ]);
		
		$this->belongsTo('PurchaseVoucherRows', [
            'foreignKey' => 'purchase_voucher_row_id',
			'joinType' => 'LEFT'
        ]);
		
		$this->belongsTo('JournalVoucherRows', [
            'foreignKey' => 'journal_voucher_row_id',
			'joinType' => 'LEFT'
        ]);
		$this->belongsTo('SalesInvoices', [
            'foreignKey' => 'sales_invoice_id',
			'joinType' => 'LEFT'
        ]);
		$this->belongsTo('SaleReturns',[
            'foreignKey' => 'sale_return_id',
			'joinType' => 'LEFT'
        ]);
		$this->belongsTo('PurchaseInvoices', [
            'foreignKey' => 'purchase_invoice_id',
			'joinType' => 'LEFT'
        ]);
		$this->belongsTo('PurchaseReturns', [
            'foreignKey' => 'purchase_return_id',
			'joinType' => 'LEFT'
        ]);
    }

    /**
     * Default validation rules.
     *
     * @param \Cake\Validation\Validator $validator Validator instance.
     * @return \Cake\Validation\Validator
     */
    public function validationDefault(Validator $validator)
    {
        $validator
            ->integer('id')
            ->allowEmpty('id', 'create');

        $validator
            ->requirePresence('type', 'create')
            ->notEmpty('type');

		return $validator;
    }

	public function beforeMarshal(Event $event, ArrayObject $data)
    {
		if(@$data['transaction_date']!="")
		{
			@$data['transaction_date'] = trim(date('Y-m-d',strtotime(@$data['transaction_date'])));
		}
    }
    /**
     * Returns a rules checker object that will be used for validating
     * application integrity.
     *
     * @param \Cake\ORM\RulesChecker $rules The rules object to be modified.
     * @return \Cake\ORM\RulesChecker
     */
    public function buildRules(RulesChecker $rules)
    {
        $rules->add($rules->existsIn(['company_id'], 'Companies'));
        $rules->add($rules->existsIn(['ledger_id'], 'Ledgers'));

        return $rules;
    }
}
