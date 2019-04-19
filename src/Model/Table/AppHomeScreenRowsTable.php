<?php
namespace App\Model\Table;

use Cake\ORM\Query;
use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\Validation\Validator;

/**
 * AppHomeScreenRows Model
 *
 * @property \App\Model\Table\AppHomeScreensTable|\Cake\ORM\Association\BelongsTo $AppHomeScreens
 * @property \App\Model\Table\StockGroupsTable|\Cake\ORM\Association\BelongsTo $StockGroups
 *
 * @method \App\Model\Entity\AppHomeScreenRow get($primaryKey, $options = [])
 * @method \App\Model\Entity\AppHomeScreenRow newEntity($data = null, array $options = [])
 * @method \App\Model\Entity\AppHomeScreenRow[] newEntities(array $data, array $options = [])
 * @method \App\Model\Entity\AppHomeScreenRow|bool save(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\AppHomeScreenRow patchEntity(\Cake\Datasource\EntityInterface $entity, array $data, array $options = [])
 * @method \App\Model\Entity\AppHomeScreenRow[] patchEntities($entities, array $data, array $options = [])
 * @method \App\Model\Entity\AppHomeScreenRow findOrCreate($search, callable $callback = null, $options = [])
 */
class AppHomeScreenRowsTable extends Table
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

        $this->setTable('app_home_screen_rows');
        $this->setDisplayField('name');
        $this->setPrimaryKey('id');

        $this->belongsTo('AppHomeScreens', [
            'foreignKey' => 'app_home_screen_id',
            'joinType' => 'INNER'
        ]);
        $this->belongsTo('StockGroups', [
            'foreignKey' => 'stock_group_id',
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

      /*   $validator
            ->requirePresence('name', 'create')
            ->notEmpty('name');

        $validator
            ->requirePresence('image', 'create')
            ->notEmpty('image');

        $validator
            ->requirePresence('link_name', 'create')
            ->notEmpty('link_name'); */

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
        $rules->add($rules->existsIn(['app_home_screen_id'], 'AppHomeScreens'));
      //  $rules->add($rules->existsIn(['stock_group_id'], 'StockGroups'));

        return $rules;
    }
}
