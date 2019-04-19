<?php
namespace App\Model\Table;

use Cake\ORM\Query;
use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\Validation\Validator;

/**
 * FinancialYears Model
 *
 * @property |\Cake\ORM\Association\BelongsTo $Companies
 *
 * @method \App\Model\Entity\FinancialYear get($primaryKey, $options = [])
 * @method \App\Model\Entity\FinancialYear newEntity($data = null, array $options = [])
 * @method \App\Model\Entity\FinancialYear[] newEntities(array $data, array $options = [])
 * @method \App\Model\Entity\FinancialYear|bool save(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\FinancialYear patchEntity(\Cake\Datasource\EntityInterface $entity, array $data, array $options = [])
 * @method \App\Model\Entity\FinancialYear[] patchEntities($entities, array $data, array $options = [])
 * @method \App\Model\Entity\FinancialYear findOrCreate($search, callable $callback = null, $options = [])
 */
class FinancialYearsTable extends Table
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

        $this->setTable('financial_years');
        $this->setDisplayField('id');
        $this->setPrimaryKey('id');

        $this->belongsTo('Companies', [
            'foreignKey' => 'company_id',
            'joinType' => 'INNER'
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
            ->date('fy_from')
            ->requirePresence('fy_from', 'create')
            ->notEmpty('fy_from');

        $validator
            ->date('fy_to')
            ->requirePresence('fy_to', 'create')
            ->notEmpty('fy_to');

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
        $rules->add($rules->existsIn(['company_id'], 'Companies'));

        return $rules;
    }
}
