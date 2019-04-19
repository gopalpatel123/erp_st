<?php
namespace App\Model\Table;

use Cake\ORM\Query;
use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\Validation\Validator;

/**
 * AccountingGroups Model
 *
 * @property \App\Model\Table\NatureOfGroupsTable|\Cake\ORM\Association\BelongsTo $NatureOfGroups
 * @property \App\Model\Table\AccountingGroupsTable|\Cake\ORM\Association\BelongsTo $ParentAccountingGroups
 * @property \App\Model\Table\CompaniesTable|\Cake\ORM\Association\BelongsTo $Companies
 * @property \App\Model\Table\AccountingGroupsTable|\Cake\ORM\Association\HasMany $ChildAccountingGroups
 * @property \App\Model\Table\LedgersTable|\Cake\ORM\Association\HasMany $Ledgers
 *
 * @method \App\Model\Entity\AccountingGroup get($primaryKey, $options = [])
 * @method \App\Model\Entity\AccountingGroup newEntity($data = null, array $options = [])
 * @method \App\Model\Entity\AccountingGroup[] newEntities(array $data, array $options = [])
 * @method \App\Model\Entity\AccountingGroup|bool save(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\AccountingGroup patchEntity(\Cake\Datasource\EntityInterface $entity, array $data, array $options = [])
 * @method \App\Model\Entity\AccountingGroup[] patchEntities($entities, array $data, array $options = [])
 * @method \App\Model\Entity\AccountingGroup findOrCreate($search, callable $callback = null, $options = [])
 *
 * @mixin \Cake\ORM\Behavior\TreeBehavior
 */
class AccountingGroupsTable extends Table
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

        $this->setTable('accounting_groups');
        $this->setDisplayField('name');
        $this->setPrimaryKey('id');

        $this->addBehavior('Tree');

        $this->belongsTo('NatureOfGroups', [
            'foreignKey' => 'nature_of_group_id'
        ]);
        $this->belongsTo('ParentAccountingGroups', [
            'className' => 'AccountingGroups',
            'foreignKey' => 'parent_id'
        ]);
        $this->belongsTo('Companies', [
            'foreignKey' => 'company_id',
            'joinType' => 'INNER'
        ]);
        $this->hasMany('ChildAccountingGroups', [
            'className' => 'AccountingGroups',
            'foreignKey' => 'parent_id'
        ]);
        $this->hasMany('Ledgers', [
            'foreignKey' => 'accounting_group_id'
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
            ->boolean('customer')
            ->allowEmpty('customer');

        $validator
            ->boolean('supplier')
            ->allowEmpty('supplier');

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
        $rules->add($rules->existsIn(['nature_of_group_id'], 'NatureOfGroups'));
        $rules->add($rules->existsIn(['parent_id'], 'ParentAccountingGroups'));
        $rules->add($rules->existsIn(['company_id'], 'Companies'));

        return $rules;
    }
}
