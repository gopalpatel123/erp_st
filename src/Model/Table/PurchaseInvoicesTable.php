<?php
namespace App\Model\Table;

use Cake\ORM\Query;
use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\Validation\Validator;

/**
 * PurchaseInvoices Model
 *
 * @property \App\Model\Table\CompaniesTable|\Cake\ORM\Association\BelongsTo $Companies
 * @property \App\Model\Table\SupplierLedgersTable|\Cake\ORM\Association\BelongsTo $SupplierLedgers
 * @property \App\Model\Table\PurchaseInvoiceRowsTable|\Cake\ORM\Association\HasMany $PurchaseInvoiceRows
 *
 * @method \App\Model\Entity\PurchaseInvoice get($primaryKey, $options = [])
 * @method \App\Model\Entity\PurchaseInvoice newEntity($data = null, array $options = [])
 * @method \App\Model\Entity\PurchaseInvoice[] newEntities(array $data, array $options = [])
 * @method \App\Model\Entity\PurchaseInvoice|bool save(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\PurchaseInvoice patchEntity(\Cake\Datasource\EntityInterface $entity, array $data, array $options = [])
 * @method \App\Model\Entity\PurchaseInvoice[] patchEntities($entities, array $data, array $options = [])
 * @method \App\Model\Entity\PurchaseInvoice findOrCreate($search, callable $callback = null, $options = [])
 */
class PurchaseInvoicesTable extends Table
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

        $this->setTable('purchase_invoices');
        $this->setDisplayField('id');
        $this->setPrimaryKey('id');

        $this->belongsTo('Companies', [
            'foreignKey' => 'company_id',
            'joinType' => 'INNER'
        ]);
		 $this->belongsTo('ReferenceDetails');
       $this->belongsTo('SupplierLedgers', [
			'className' => 'Ledgers',
            'foreignKey' => 'supplier_ledger_id',
            'joinType' => 'LEFT'
        ]);
		
		 $this->belongsTo('PurchaseLedgers', [
			'className' => 'Ledgers',
            'foreignKey' => 'purchase_ledger_id',
            'joinType' => 'LEFT'
        ]);
		
        $this->hasMany('PurchaseInvoiceRows', [
            'foreignKey' => 'purchase_invoice_id',
			'saveStrategy'=>'replace'
        ]);
		
		$this->hasMany('PurchaseInvoiceRowsDatas', [
			'className' => 'PurchaseInvoiceRows',
            'foreignKey' => 'purchase_invoice_id',
            'joinType' => 'INNER'
        ]);
		
		$this->belongsTo('Grns', [
            'foreignKey' => 'grn_id',
            'joinType' => 'INNER'
        ]);
		$this->belongsTo('ItemLedgers');
		$this->hasMany('AccountingEntries', [
            'foreignKey' => 'purchase_invoice_id',
            'joinType' => 'INNER'
        ]);
		
		$this->hasMany('PurchaseReturns', [
            'foreignKey' => 'purchase_invoice_id',
            'joinType' => 'INNER'
        ]);
		
		$this->hasMany('ReferenceDetails', [
            'foreignKey' => 'purchase_invoice_id',
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
            ->integer('voucher_no')
            ->requirePresence('voucher_no', 'create')
            ->notEmpty('voucher_no');

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
        $rules->add($rules->existsIn(['company_id'], 'Companies'));
        $rules->add($rules->existsIn(['supplier_ledger_id'], 'SupplierLedgers'));

        return $rules;
    }
}
