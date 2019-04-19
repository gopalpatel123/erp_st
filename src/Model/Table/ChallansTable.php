<?php
namespace App\Model\Table;

use Cake\ORM\Query;
use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\Validation\Validator;

/**
 * Challans Model
 *
 * @property \App\Model\Table\FinancialYearsTable|\Cake\ORM\Association\BelongsTo $FinancialYears
 * @property \App\Model\Table\CompaniesTable|\Cake\ORM\Association\BelongsTo $Companies
 * @property \App\Model\Table\CustomersTable|\Cake\ORM\Association\BelongsTo $Customers
 * @property \App\Model\Table\SalesLedgersTable|\Cake\ORM\Association\BelongsTo $SalesLedgers
 * @property \App\Model\Table\PartyLedgersTable|\Cake\ORM\Association\BelongsTo $PartyLedgers
 * @property \App\Model\Table\LocationsTable|\Cake\ORM\Association\BelongsTo $Locations
 * @property \App\Model\Table\ChallanRowsTable|\Cake\ORM\Association\HasMany $ChallanRows
 *
 * @method \App\Model\Entity\Challan get($primaryKey, $options = [])
 * @method \App\Model\Entity\Challan newEntity($data = null, array $options = [])
 * @method \App\Model\Entity\Challan[] newEntities(array $data, array $options = [])
 * @method \App\Model\Entity\Challan|bool save(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\Challan patchEntity(\Cake\Datasource\EntityInterface $entity, array $data, array $options = [])
 * @method \App\Model\Entity\Challan[] patchEntities($entities, array $data, array $options = [])
 * @method \App\Model\Entity\Challan findOrCreate($search, callable $callback = null, $options = [])
 */
class ChallansTable extends Table
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

        $this->setTable('challans');
        $this->setDisplayField('id');
        $this->setPrimaryKey('id');

		$this->belongsTo('ChallanRows');
		
        $this->belongsTo('FinancialYears', [
            'foreignKey' => 'financial_year_id'
        ]);
		
		 $this->belongsTo('GstFigures', [
            'foreignKey' => 'gst_figure_id',
            'joinType' => 'INNER'
        ]);

		$this->hasMany('ItemLedgers', [
            'foreignKey' => 'challan_id',
			'saveStrategy'=>'replace'
        ]);		
        $this->belongsTo('Companies', [
            'foreignKey' => 'company_id',
            'joinType' => 'INNER'
        ]);
        $this->belongsTo('Customers', [
            'foreignKey' => 'customer_id',
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

        $this->belongsTo('Locations', [
            'foreignKey' => 'location_id',
            'joinType' => 'INNER'
        ]);
        $this->hasMany('ChallanRows', [
            'foreignKey' => 'challan_id'
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

        $validator
            ->requirePresence('invoice_receipt_type', 'create')
            ->notEmpty('invoice_receipt_type');

        $validator
            ->decimal('receipt_amount')
            ->requirePresence('receipt_amount', 'create')
            ->notEmpty('receipt_amount');

        $validator
            ->decimal('discount_amount')
            ->requirePresence('discount_amount', 'create')
            ->notEmpty('discount_amount');

        $validator
            ->requirePresence('status', 'create')
            ->notEmpty('status');

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
        $rules->add($rules->existsIn(['financial_year_id'], 'FinancialYears'));
        $rules->add($rules->existsIn(['company_id'], 'Companies'));
        $rules->add($rules->existsIn(['customer_id'], 'Customers'));
        $rules->add($rules->existsIn(['sales_ledger_id'], 'SalesLedgers'));
        $rules->add($rules->existsIn(['party_ledger_id'], 'PartyLedgers'));
        $rules->add($rules->existsIn(['location_id'], 'Locations'));

        return $rules;
    }
}
