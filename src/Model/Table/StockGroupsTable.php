<?php
namespace App\Model\Table;

use Cake\ORM\Query;
use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\Validation\Validator;

/**
 * StockGroups Model
 *
 * @property \App\Model\Table\StockGroupsTable|\Cake\ORM\Association\BelongsTo $ParentStockGroups
 * @property \App\Model\Table\CompaniesTable|\Cake\ORM\Association\BelongsTo $Companies
 * @property \App\Model\Table\ItemsTable|\Cake\ORM\Association\HasMany $Items
 * @property \App\Model\Table\StockGroupsTable|\Cake\ORM\Association\HasMany $ChildStockGroups
 *
 * @method \App\Model\Entity\StockGroup get($primaryKey, $options = [])
 * @method \App\Model\Entity\StockGroup newEntity($data = null, array $options = [])
 * @method \App\Model\Entity\StockGroup[] newEntities(array $data, array $options = [])
 * @method \App\Model\Entity\StockGroup|bool save(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\StockGroup patchEntity(\Cake\Datasource\EntityInterface $entity, array $data, array $options = [])
 * @method \App\Model\Entity\StockGroup[] patchEntities($entities, array $data, array $options = [])
 * @method \App\Model\Entity\StockGroup findOrCreate($search, callable $callback = null, $options = [])
 *
 * @mixin \Cake\ORM\Behavior\TreeBehavior
 */
class StockGroupsTable extends Table
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

        $this->setTable('stock_groups');
        $this->setDisplayField('name');
        $this->setPrimaryKey('id');

        $this->addBehavior('Tree');

        $this->belongsTo('ParentStockGroups', [
            'className' => 'StockGroups',
            'foreignKey' => 'parent_id'
        ]);
        $this->belongsTo('Companies', [
            'foreignKey' => 'company_id',
            'joinType' => 'INNER'
        ]);
        $this->hasMany('Items', [
            'foreignKey' => 'stock_group_id',
			'joinType' => 'LEFT'
        ]);
        $this->hasMany('ChildStockGroups', [
            'className' => 'StockGroups',
            'foreignKey' => 'parent_id'
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
        $rules->add($rules->existsIn(['parent_id'], 'ParentStockGroups'));
        $rules->add($rules->existsIn(['company_id'], 'Companies'));

        return $rules;
    }
}
