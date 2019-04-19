<?php
namespace App\Model\Table;

use Cake\ORM\Query;
use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\Validation\Validator;

/**
 * AccountingEntries Model
 *
 * @property \App\Model\Table\LedgersTable|\Cake\ORM\Association\BelongsTo $Ledgers
 * @property \App\Model\Table\CompaniesTable|\Cake\ORM\Association\BelongsTo $Companies
 *
 * @method \App\Model\Entity\AccountingEntry get($primaryKey, $options = [])
 * @method \App\Model\Entity\AccountingEntry newEntity($data = null, array $options = [])
 * @method \App\Model\Entity\AccountingEntry[] newEntities(array $data, array $options = [])
 * @method \App\Model\Entity\AccountingEntry|bool save(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\AccountingEntry patchEntity(\Cake\Datasource\EntityInterface $entity, array $data, array $options = [])
 * @method \App\Model\Entity\AccountingEntry[] patchEntities($entities, array $data, array $options = [])
 * @method \App\Model\Entity\AccountingEntry findOrCreate($search, callable $callback = null, $options = [])
 */
class AccountingEntriesTable extends Table
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

        $this->setTable('accounting_entries');
        $this->setDisplayField('id');
        $this->setPrimaryKey('id');

        $this->belongsTo('Ledgers', [
            'foreignKey' => 'ledger_id',
            'joinType' => 'INNER'
        ]);
        $this->belongsTo('Companies', [
            'foreignKey' => 'company_id',
            'joinType' => 'INNER'
        ]);
		$this->belongsTo('PurchaseVouchers', [
            'foreignKey' => 'purchase_voucher_id',
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
		$this->belongsTo('SalesInvoices', [
            'foreignKey' => 'sales_invoice_id',
            'joinType' => 'LEFT'
        ]);
		
		$this->belongsTo('SalesInvoicesData', [
            'foreignKey' => 'sales_invoice_id',
            'joinType' => 'INNER'
        ]);
		
		$this->belongsTo('SaleReturns', [
            'foreignKey' => 'sale_return_id',
            'joinType' => 'LEFT'
        ]);
		
		
		$this->belongsTo('SalesVouchers', [
            'foreignKey' => 'sales_voucher_id',
            'joinType' => 'LEFT'
        ]);
		
		$this->belongsTo('JournalVouchers', [
            'foreignKey' => 'journal_voucher_id',
            'joinType' => 'LEFT'
        ]);
		
		$this->belongsTo('ContraVouchers', [
            'foreignKey' => 'contra_voucher_id',
            'joinType' => 'LEFT'
        ]);
		
		$this->belongsTo('Receipts', [
            'foreignKey' => 'receipt_id',
            'joinType' => 'LEFT'
        ]);
		
		$this->belongsTo('Payments', [
            'foreignKey' => 'payment_id',
            'joinType' => 'LEFT'
        ]);
		
		$this->belongsTo('CreditNotes', [
            'foreignKey' => 'credit_note_id',
            'joinType' => 'LEFT'
        ]);
		
		$this->belongsTo('DebitNotes', [
            'foreignKey' => 'debit_note_id',
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
            ->decimal('debit')
            ->requirePresence('debit', 'create')
            ->notEmpty('debit');

        $validator
            ->decimal('credit')
            ->requirePresence('credit', 'create')
            ->notEmpty('credit');

        $validator
            ->date('transaction_date')
            ->requirePresence('transaction_date', 'create')
            ->notEmpty('transaction_date');

        return $validator;
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
        $rules->add($rules->existsIn(['ledger_id'], 'Ledgers'));
        $rules->add($rules->existsIn(['company_id'], 'Companies'));

        return $rules;
    }
}
