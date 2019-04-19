<?php
namespace App\Model\Table;

use Cake\ORM\Query;
use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\Validation\Validator;

/**
 * SaleReturnRows Model
 *
 * @property \App\Model\Table\SaleReturnsTable|\Cake\ORM\Association\BelongsTo $SaleReturns
 * @property \App\Model\Table\ItemsTable|\Cake\ORM\Association\BelongsTo $Items
 * @property \App\Model\Table\GstFiguresTable|\Cake\ORM\Association\BelongsTo $GstFigures
 *
 * @method \App\Model\Entity\SaleReturnRow get($primaryKey, $options = [])
 * @method \App\Model\Entity\SaleReturnRow newEntity($data = null, array $options = [])
 * @method \App\Model\Entity\SaleReturnRow[] newEntities(array $data, array $options = [])
 * @method \App\Model\Entity\SaleReturnRow|bool save(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\SaleReturnRow patchEntity(\Cake\Datasource\EntityInterface $entity, array $data, array $options = [])
 * @method \App\Model\Entity\SaleReturnRow[] patchEntities($entities, array $data, array $options = [])
 * @method \App\Model\Entity\SaleReturnRow findOrCreate($search, callable $callback = null, $options = [])
 */
class SaleReturnRowsTable extends Table
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

        $this->setTable('sale_return_rows');
        $this->setDisplayField('id');
        $this->setPrimaryKey('id');

        $this->belongsTo('SaleReturns', [
            'foreignKey' => 'sale_return_id',
            'joinType' => 'INNER'
        ]);
        $this->belongsTo('Items', [
            'foreignKey' => 'item_id',
            'joinType' => 'INNER'
        ]);
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
            ->decimal('rate')
            ->requirePresence('rate', 'create')
            ->notEmpty('rate');

       

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
        $rules->add($rules->existsIn(['sale_return_id'], 'SaleReturns'));
        $rules->add($rules->existsIn(['item_id'], 'Items'));
        $rules->add($rules->existsIn(['gst_figure_id'], 'GstFigures'));

        return $rules;
    }
}
