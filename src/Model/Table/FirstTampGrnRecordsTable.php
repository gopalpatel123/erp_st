<?php
namespace App\Model\Table;

use Cake\ORM\Query;
use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\Validation\Validator;

/**
 * FirstTampGrnRecords Model
 *
 * @property \App\Model\Table\UsersTable|\Cake\ORM\Association\BelongsTo $Users
 *
 * @method \App\Model\Entity\FirstTampGrnRecord get($primaryKey, $options = [])
 * @method \App\Model\Entity\FirstTampGrnRecord newEntity($data = null, array $options = [])
 * @method \App\Model\Entity\FirstTampGrnRecord[] newEntities(array $data, array $options = [])
 * @method \App\Model\Entity\FirstTampGrnRecord|bool save(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\FirstTampGrnRecord patchEntity(\Cake\Datasource\EntityInterface $entity, array $data, array $options = [])
 * @method \App\Model\Entity\FirstTampGrnRecord[] patchEntities($entities, array $data, array $options = [])
 * @method \App\Model\Entity\FirstTampGrnRecord findOrCreate($search, callable $callback = null, $options = [])
 */
class FirstTampGrnRecordsTable extends Table
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

        $this->setTable('first_tamp_grn_records');
        $this->setDisplayField('id');
        $this->setPrimaryKey('id');

        $this->belongsTo('Users', [
            'foreignKey' => 'user_id',
            'joinType' => 'INNER'
        ]);
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

        /* $validator
            ->requirePresence('item_code', 'create')
            ->notEmpty('item_code'); */

        $validator
            ->decimal('quantity')
            ->requirePresence('quantity', 'create')
            ->notEmpty('quantity');

        $validator
            ->decimal('purchase_rate')
            ->requirePresence('purchase_rate', 'create')
            ->notEmpty('purchase_rate');

        $validator
            ->decimal('sales_rate')
            ->requirePresence('sales_rate', 'create')
            ->notEmpty('sales_rate');

        $validator
            ->requirePresence('processed', 'create')
            ->notEmpty('processed');

        $validator
            ->requirePresence('is_addition_item_data_required', 'create')
            ->notEmpty('is_addition_item_data_required');

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
        $rules->add($rules->existsIn(['user_id'], 'Users'));

        return $rules;
    }
}
