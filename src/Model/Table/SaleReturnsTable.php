<?php
namespace App\Model\Table;

use Cake\ORM\Query;
use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\Validation\Validator;

/**
 * SaleReturns Model
 *
 * @property \App\Model\Table\CompaniesTable|\Cake\ORM\Association\BelongsTo $Companies
 * @property \App\Model\Table\CustomersTable|\Cake\ORM\Association\BelongsTo $Customers
 * @property \App\Model\Table\SalesLedgersTable|\Cake\ORM\Association\BelongsTo $SalesLedgers
 * @property \App\Model\Table\PartyLedgersTable|\Cake\ORM\Association\BelongsTo $PartyLedgers
 * @property \App\Model\Table\LocationsTable|\Cake\ORM\Association\BelongsTo $Locations
 * @property \App\Model\Table\SalesInvoicesTable|\Cake\ORM\Association\BelongsTo $SalesInvoices
 * @property \App\Model\Table\SaleReturnRowsTable|\Cake\ORM\Association\HasMany $SaleReturnRows
 *
 * @method \App\Model\Entity\SaleReturn get($primaryKey, $options = [])
 * @method \App\Model\Entity\SaleReturn newEntity($data = null, array $options = [])
 * @method \App\Model\Entity\SaleReturn[] newEntities(array $data, array $options = [])
 * @method \App\Model\Entity\SaleReturn|bool save(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\SaleReturn patchEntity(\Cake\Datasource\EntityInterface $entity, array $data, array $options = [])
 * @method \App\Model\Entity\SaleReturn[] patchEntities($entities, array $data, array $options = [])
 * @method \App\Model\Entity\SaleReturn findOrCreate($search, callable $callback = null, $options = [])
 */
class SaleReturnsTable extends Table
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

        $this->setTable('sale_returns');
        $this->setDisplayField('id');
        $this->setPrimaryKey('id');

        $this->belongsTo('Companies', [
            'foreignKey' => 'company_id',
            'joinType' => 'INNER'
        ]);
        $this->belongsTo('Customers', [
            'foreignKey' => 'customer_id',
            'joinType' => 'INNER'
        ]);
        
        $this->belongsTo('Locations', [
            'foreignKey' => 'location_id',
            'joinType' => 'INNER'
        ]);
		
		 $this->belongsTo('SalesInvoices', [
            'foreignKey' => 'sales_invoice_id',
            'joinType' => 'INNER'
        ]);
		
		$this->belongsTo('PartyLedgers', [
			'className' => 'Ledgers',
            'foreignKey' => 'party_ledger_id',
            'joinType' => 'INNER'
        ]);
		$this->belongsTo('SalesLedgers', [
			'className' => 'Ledgers',
            'foreignKey' => 'sales_ledger_id',
            'joinType' => 'INNER'
        ]);
		$this->belongsTo('Grns');
		 $this->hasMany('SaleReturnRows', [
            'foreignKey' => 'sale_return_id',
			'saveStrategy'=>'replace'
        ]);
		
		$this->hasMany('ReferenceDetails', [
            'foreignKey' => 'sale_return_id',
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

        $validator
            ->decimal('amount_before_tax')
            ->requirePresence('amount_before_tax', 'create')
            ->notEmpty('amount_before_tax');

        $validator
            ->decimal('total_cgst')
            ->requirePresence('total_cgst', 'create')
            ->notEmpty('total_cgst');

        $validator
            ->numeric('total_sgst')
            ->requirePresence('total_sgst', 'create')
            ->notEmpty('total_sgst');

        $validator
            ->decimal('total_igst')
            ->requirePresence('total_igst', 'create')
            ->notEmpty('total_igst');

        $validator
            ->decimal('amount_after_tax')
            ->requirePresence('amount_after_tax', 'create')
            ->notEmpty('amount_after_tax');

        $validator
            ->decimal('round_off')
            ->requirePresence('round_off', 'create')
            ->notEmpty('round_off');

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
        $rules->add($rules->existsIn(['customer_id'], 'Customers'));
       // $rules->add($rules->existsIn(['sales_ledger_id'], 'SalesLedgers'));
        $rules->add($rules->existsIn(['party_ledger_id'], 'PartyLedgers'));
        $rules->add($rules->existsIn(['location_id'], 'Locations'));
        $rules->add($rules->existsIn(['sales_invoice_id'], 'SalesInvoices'));

        return $rules;
    }
}
