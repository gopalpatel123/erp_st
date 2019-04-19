<?php
namespace App\Model\Table;

use Cake\ORM\Query;
use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\Validation\Validator;

/**
 * Companies Model
 *
 * @property \App\Model\Table\StatesTable|\Cake\ORM\Association\BelongsTo $States
 * @property \App\Model\Table\CompanyUsersTable|\Cake\ORM\Association\HasMany $CompanyUsers
 *
 * @method \App\Model\Entity\Company get($primaryKey, $options = [])
 * @method \App\Model\Entity\Company newEntity($data = null, array $options = [])
 * @method \App\Model\Entity\Company[] newEntities(array $data, array $options = [])
 * @method \App\Model\Entity\Company|bool save(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\Company patchEntity(\Cake\Datasource\EntityInterface $entity, array $data, array $options = [])
 * @method \App\Model\Entity\Company[] patchEntities($entities, array $data, array $options = [])
 * @method \App\Model\Entity\Company findOrCreate($search, callable $callback = null, $options = [])
 */
class CompaniesTable extends Table
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

        $this->setTable('companies');
        $this->setDisplayField('name');
        $this->setPrimaryKey('id');

        $this->belongsTo('States', [
            'foreignKey' => 'state_id',
            'joinType' => 'INNER'
        ]);
        $this->hasMany('CompanyUsers', [
            'foreignKey' => 'company_id'
        ]);
		$this->hasMany('FinancialYears', [
            'foreignKey' => 'company_id'
        ]);
		$this->hasMany('ItemLedgers', [
            'foreignKey' => 'company_id'
        ]);
		$this->hasMany('Locations', [
            'foreignKey' => 'location_id'
        ]);
		$this->hasMany('FirstTampGrnRecords', [
            'foreignKey' => 'company_id'
        ]);
		$this->hasMany('Items', [
            'foreignKey' => 'company_id'
        ]);
		$this->hasMany('SecondTampGrnRecords', [
            'foreignKey' => 'company_id'
        ]);
		$this->hasMany('GstFigures', [
            'foreignKey' => 'company_id'
        ]);
		$this->hasMany('Ledgers', [
            'foreignKey' => 'company_id'
        ]);
		$this->hasMany('AccountingGroups', [
            'foreignKey' => 'company_id'
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
            ->date('financial_year_begins_from')
            ->requirePresence('financial_year_begins_from', 'create')
            ->notEmpty('financial_year_begins_from');

        $validator
            ->date('books_beginning_from')
            ->requirePresence('books_beginning_from', 'create')
            ->notEmpty('books_beginning_from');

        /* $validator
            ->requirePresence('address', 'create')
            ->notEmpty('address');

        $validator
            ->requirePresence('phone_no', 'create')
            ->notEmpty('phone_no');

        $validator
            ->requirePresence('mobile', 'create')
            ->notEmpty('mobile');

        $validator
            ->requirePresence('fax_no', 'create')
            ->notEmpty('fax_no');

        $validator
            ->email('email')
            ->requirePresence('email', 'create')
            ->notEmpty('email');

        $validator
            ->requirePresence('gstin', 'create')
            ->notEmpty('gstin');

        $validator
            ->requirePresence('pan', 'create')
            ->notEmpty('pan'); */

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
        $rules->add($rules->isUnique(['email']));
        $rules->add($rules->existsIn(['state_id'], 'States'));

        return $rules;
    }
}
