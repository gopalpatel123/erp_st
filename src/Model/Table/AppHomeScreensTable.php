<?php
namespace App\Model\Table;

use Cake\ORM\Query;
use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\Validation\Validator;

/**
 * AppHomeScreens Model
 *
 * @property \App\Model\Table\StockGroupsTable|\Cake\ORM\Association\BelongsTo $StockGroups
 * @property |\Cake\ORM\Association\BelongsTo $SubCategories
 * @property |\Cake\ORM\Association\HasMany $AppHomeScreenRows
 *
 * @method \App\Model\Entity\AppHomeScreen get($primaryKey, $options = [])
 * @method \App\Model\Entity\AppHomeScreen newEntity($data = null, array $options = [])
 * @method \App\Model\Entity\AppHomeScreen[] newEntities(array $data, array $options = [])
 * @method \App\Model\Entity\AppHomeScreen|bool save(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\AppHomeScreen patchEntity(\Cake\Datasource\EntityInterface $entity, array $data, array $options = [])
 * @method \App\Model\Entity\AppHomeScreen[] patchEntities($entities, array $data, array $options = [])
 * @method \App\Model\Entity\AppHomeScreen findOrCreate($search, callable $callback = null, $options = [])
 */
class AppHomeScreensTable extends Table
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

        $this->setTable('app_home_screens');
        $this->setDisplayField('title');
        $this->setPrimaryKey('id');

        $this->belongsTo('StockGroups', [
            'foreignKey' => 'stock_group_id',
            'joinType' => 'INNER'
        ]);
       /*  $this->belongsTo('SubCategories', [
            'foreignKey' => 'sub_category_id',
            'joinType' => 'INNER'
        ]); */
        $this->hasMany('AppHomeScreenRows', [
            'foreignKey' => 'app_home_screen_id'
        ]);
		$this->belongsTo('AppBanners');
		$this->belongsTo('AppBrands');
		$this->belongsTo('AppHomeScreenSeconds');
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
            ->requirePresence('title', 'create')
            ->notEmpty('title');

        $validator
            ->requirePresence('layout', 'create')
            ->notEmpty('layout');

      /*   $validator
            ->requirePresence('section_show', 'create')
            ->notEmpty('section_show');

        $validator
            ->integer('preference')
            ->requirePresence('preference', 'create')
            ->notEmpty('preference'); 
			
		 $validator
            ->requirePresence('model_name', 'create')
            ->notEmpty('model_name');

         $validator
            ->requirePresence('image', 'create')
            ->notEmpty('image');	
			 

        $validator
            ->requirePresence('link_name', 'create')
            ->notEmpty('link_name');

        $validator
            ->requirePresence('sub_title', 'create')
            ->notEmpty('sub_title');
			
			*/

        $validator
            ->requirePresence('screen_type', 'create')
            ->notEmpty('screen_type');

      

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
        $rules->add($rules->existsIn(['stock_group_id'], 'StockGroups'));
       // $rules->add($rules->existsIn(['sub_category_id'], 'SubCategories'));

        return $rules;
    }
}
