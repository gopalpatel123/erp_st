<?php
namespace App\Model\Table;

use Cake\ORM\Query;
use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\Validation\Validator;

/**
 * SecondTampGrnRecords Model
 *
 * @property \App\Model\Table\UsersTable|\Cake\ORM\Association\BelongsTo $Users
 *
 * @method \App\Model\Entity\SecondTampGrnRecord get($primaryKey, $options = [])
 * @method \App\Model\Entity\SecondTampGrnRecord newEntity($data = null, array $options = [])
 * @method \App\Model\Entity\SecondTampGrnRecord[] newEntities(array $data, array $options = [])
 * @method \App\Model\Entity\SecondTampGrnRecord|bool save(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\SecondTampGrnRecord patchEntity(\Cake\Datasource\EntityInterface $entity, array $data, array $options = [])
 * @method \App\Model\Entity\SecondTampGrnRecord[] patchEntities($entities, array $data, array $options = [])
 * @method \App\Model\Entity\SecondTampGrnRecord findOrCreate($search, callable $callback = null, $options = [])
 */
class SecondTampGrnRecordsTable extends Table
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

        $this->setTable('second_tamp_grn_records');
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
		
		$this->belongsTo('Units', [
            'foreignKey' => 'unit_id',
            'joinType' => 'LEFT'
        ]);
		$this->belongsTo('Sizes', [
            'foreignKey' => 'size_id',
            'joinType' => 'LEFT'
        ]);
		$this->belongsTo('Shades', [
            'foreignKey' => 'shade_id',
            'joinType' => 'LEFT'
        ]);
		$this->belongsTo('GstFigures', [
            'foreignKey' => 'gst_figure_id',
            'joinType' => 'LEFT'
		]);
		$this->belongsTo('FirstGstFigures', [
			'className' => 'GstFigures',
			'foreignKey' => 'first_gst_figure_id',
			'propertyName' => 'FirstGstFigures',
		]);
		$this->belongsTo('SecondGstFigures', [
			'className' => 'GstFigures',
			'foreignKey' => 'second_gst_figure_id',
			'propertyName' => 'SecondGstFigures',
		]);

		$this->belongsTo('Grns');
		
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
