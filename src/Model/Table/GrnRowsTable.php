<?php
namespace App\Model\Table;

use Cake\ORM\Query;
use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\Validation\Validator;

/**
 * GrnRows Model
 *
 * @property \App\Model\Table\GrnsTable|\Cake\ORM\Association\BelongsTo $Grns
 * @property \App\Model\Table\ItemsTable|\Cake\ORM\Association\BelongsTo $Items
 *
 * @method \App\Model\Entity\GrnRow get($primaryKey, $options = [])
 * @method \App\Model\Entity\GrnRow newEntity($data = null, array $options = [])
 * @method \App\Model\Entity\GrnRow[] newEntities(array $data, array $options = [])
 * @method \App\Model\Entity\GrnRow|bool save(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\GrnRow patchEntity(\Cake\Datasource\EntityInterface $entity, array $data, array $options = [])
 * @method \App\Model\Entity\GrnRow[] patchEntities($entities, array $data, array $options = [])
 * @method \App\Model\Entity\GrnRow findOrCreate($search, callable $callback = null, $options = [])
 */
class GrnRowsTable extends Table
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

        $this->setTable('grn_rows');
        $this->setDisplayField('id');
        $this->setPrimaryKey('id');

        $this->belongsTo('Grns', [
            'foreignKey' => 'grn_id',
            'joinType' => 'INNER'
        ]);
        $this->belongsTo('Items', [
            'foreignKey' => 'item_id',
            'joinType' => 'INNER'
        ]);
		
		$this->hasMany('ItemLedgers', [
            'foreignKey' => 'item_ledger_id',
			'joinType' => 'INNER',
			'saveStrategy' => 'replace'
        ]);
		$this->belongsTo('Ledgers', [
            'foreignKey' => 'ledger_id',
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
            ->decimal('purchase_rate')
            ->requirePresence('purchase_rate', 'create')
            ->notEmpty('purchase_rate');

        $validator
            ->decimal('sale_rate')
            ->requirePresence('sale_rate', 'create')
            ->notEmpty('sale_rate');

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
        $rules->add($rules->existsIn(['grn_id'], 'Grns'));
        $rules->add($rules->existsIn(['item_id'], 'Items'));

        return $rules;
    }
}
