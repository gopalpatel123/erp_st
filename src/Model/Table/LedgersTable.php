<?php
namespace App\Model\Table;

use Cake\ORM\Query;
use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\Validation\Validator;

/**
 * Ledgers Model
 *
 * @property \App\Model\Table\AccountingGroupsTable|\Cake\ORM\Association\BelongsTo $AccountingGroups
 * @property \App\Model\Table\CompaniesTable|\Cake\ORM\Association\BelongsTo $Companies
 * @property \App\Model\Table\SuppliersTable|\Cake\ORM\Association\BelongsTo $Suppliers
 * @property \App\Model\Table\CustomersTable|\Cake\ORM\Association\BelongsTo $Customers
 * @property \App\Model\Table\AccountingEntriesTable|\Cake\ORM\Association\HasMany $AccountingEntries
 *
 * @method \App\Model\Entity\Ledger get($primaryKey, $options = [])
 * @method \App\Model\Entity\Ledger newEntity($data = null, array $options = [])
 * @method \App\Model\Entity\Ledger[] newEntities(array $data, array $options = [])
 * @method \App\Model\Entity\Ledger|bool save(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\Ledger patchEntity(\Cake\Datasource\EntityInterface $entity, array $data, array $options = [])
 * @method \App\Model\Entity\Ledger[] patchEntities($entities, array $data, array $options = [])
 * @method \App\Model\Entity\Ledger findOrCreate($search, callable $callback = null, $options = [])
 */
class LedgersTable extends Table
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

        $this->setTable('ledgers');
        $this->setDisplayField('name');
        $this->setPrimaryKey('id');

        $this->belongsTo('AccountingGroups', [
            'foreignKey' => 'accounting_group_id',
            'joinType' => 'INNER'
        ]);
        $this->belongsTo('Companies', [
            'foreignKey' => 'company_id',
            'joinType' => 'INNER'
        ]);
        $this->belongsTo('Suppliers', [
            'foreignKey' => 'supplier_id',
            'joinType' => 'LEFT'
        ]);
        $this->belongsTo('Customers', [
            'foreignKey' => 'customer_id',
            'joinType' => 'LEFT'
        ]);
        $this->hasMany('AccountingEntries', [
            'foreignKey' => 'ledger_id'
        ]);
		$this->hasMany('ReferenceDetails', [
            'foreignKey' => 'ledger_id',
			'joinType' => 'LEFT'
        ]);
		$this->hasMany('GstFigures', [
            'foreignKey' => 'gst_figure_id',
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
            ->requirePresence('name', 'create')
            ->notEmpty('name');

        $validator
            ->allowEmpty('gst_type');

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
        $rules->add($rules->existsIn(['accounting_group_id'], 'AccountingGroups'));
       // $rules->add($rules->existsIn(['company_id'], 'Companies'));
        $rules->add($rules->existsIn(['supplier_id'], 'Suppliers'));
        $rules->add($rules->existsIn(['customer_id'], 'Customers'));

        return $rules;
    }
}
