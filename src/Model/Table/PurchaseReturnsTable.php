<?php
namespace App\Model\Table;

use Cake\ORM\Query;
use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\Validation\Validator;

/**
 * PurchaseReturns Model
 *
 * @property \App\Model\Table\PurchaseInvoicesTable|\Cake\ORM\Association\BelongsTo $PurchaseInvoices
 * @property \App\Model\Table\CompaniesTable|\Cake\ORM\Association\BelongsTo $Companies
 * @property \App\Model\Table\PurchaseReturnRowsTable|\Cake\ORM\Association\HasMany $PurchaseReturnRows
 *
 * @method \App\Model\Entity\PurchaseReturn get($primaryKey, $options = [])
 * @method \App\Model\Entity\PurchaseReturn newEntity($data = null, array $options = [])
 * @method \App\Model\Entity\PurchaseReturn[] newEntities(array $data, array $options = [])
 * @method \App\Model\Entity\PurchaseReturn|bool save(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\PurchaseReturn patchEntity(\Cake\Datasource\EntityInterface $entity, array $data, array $options = [])
 * @method \App\Model\Entity\PurchaseReturn[] patchEntities($entities, array $data, array $options = [])
 * @method \App\Model\Entity\PurchaseReturn findOrCreate($search, callable $callback = null, $options = [])
 */
class PurchaseReturnsTable extends Table
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

        $this->setTable('purchase_returns');
        $this->setDisplayField('id');
        $this->setPrimaryKey('id');

        $this->belongsTo('PurchaseInvoices', [
            'foreignKey' => 'purchase_invoice_id',
            'joinType' => 'INNER'
        ]);
        $this->belongsTo('Companies', [
            'foreignKey' => 'company_id',
            'joinType' => 'INNER'
        ]);
        $this->hasMany('PurchaseReturnRows', [
            'foreignKey' => 'purchase_return_id',
			'saveStrategy'=>'replace'
        ]);
		
		$this->belongsTo('ItemLedgers');
		$this->belongsTo('Grns');
		$this->hasMany('AccountingEntries', [
            'foreignKey' => 'purchase_invoice_id',
            'joinType' => 'INNER'
        ]);
		
		$this->hasMany('AccountingEntries', [
            'foreignKey' => 'purchase_invoice_id',
            'joinType' => 'INNER'
        ]);
		
		$this->hasMany('ReferenceDetails', [
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
        $rules->add($rules->existsIn(['purchase_invoice_id'], 'PurchaseInvoices'));
        $rules->add($rules->existsIn(['company_id'], 'Companies'));

        return $rules;
    }
}
