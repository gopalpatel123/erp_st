<?php
namespace App\Model\Table;

use Cake\ORM\Query;
use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\Validation\Validator;

/**
 * ChallanRows Model
 *
 * @property \App\Model\Table\ChallansTable|\Cake\ORM\Association\BelongsTo $Challans
 * @property \App\Model\Table\ItemsTable|\Cake\ORM\Association\BelongsTo $Items
 * @property \App\Model\Table\GstFiguresTable|\Cake\ORM\Association\BelongsTo $GstFigures
 *
 * @method \App\Model\Entity\ChallanRow get($primaryKey, $options = [])
 * @method \App\Model\Entity\ChallanRow newEntity($data = null, array $options = [])
 * @method \App\Model\Entity\ChallanRow[] newEntities(array $data, array $options = [])
 * @method \App\Model\Entity\ChallanRow|bool save(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\ChallanRow patchEntity(\Cake\Datasource\EntityInterface $entity, array $data, array $options = [])
 * @method \App\Model\Entity\ChallanRow[] patchEntities($entities, array $data, array $options = [])
 * @method \App\Model\Entity\ChallanRow findOrCreate($search, callable $callback = null, $options = [])
 */
class ChallanRowsTable extends Table
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

        $this->setTable('challan_rows');
        $this->setDisplayField('id');
        $this->setPrimaryKey('id');

        $this->belongsTo('Challans', [
            'foreignKey' => 'challan_id',
            'joinType' => 'INNER'
        ]);
        $this->belongsTo('Items', [
            'foreignKey' => 'item_id',
            'joinType' => 'INNER'
        ]);
		$this->belongsTo('Ledgers');
        $this->belongsTo('GstFigures', [
            'foreignKey' => 'gst_figure_id',
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
            ->decimal('quantity')
            ->requirePresence('quantity', 'create')
            ->notEmpty('quantity');

        $validator
            ->decimal('rate')
            ->requirePresence('rate', 'create')
            ->notEmpty('rate');

        $validator
            ->decimal('discount_percentage')
            ->requirePresence('discount_percentage', 'create')
            ->notEmpty('discount_percentage');

        $validator
            ->decimal('taxable_value')
            ->requirePresence('taxable_value', 'create')
            ->notEmpty('taxable_value');

        $validator
            ->decimal('net_amount')
            ->requirePresence('net_amount', 'create')
            ->notEmpty('net_amount');

        $validator
            ->decimal('gst_value')
            ->requirePresence('gst_value', 'create')
            ->notEmpty('gst_value');

        $validator
            ->integer('is_gst_excluded')
            ->requirePresence('is_gst_excluded', 'create')
            ->notEmpty('is_gst_excluded');

        

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
        $rules->add($rules->existsIn(['challan_id'], 'Challans'));
        $rules->add($rules->existsIn(['item_id'], 'Items'));
        $rules->add($rules->existsIn(['gst_figure_id'], 'GstFigures'));

        return $rules;
    }
}
