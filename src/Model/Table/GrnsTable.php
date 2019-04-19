<?php
namespace App\Model\Table;

use Cake\ORM\Query;
use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\Validation\Validator;

/**
 * Grns Model
 *
 * @property \App\Model\Table\LocationsTable|\Cake\ORM\Association\BelongsTo $Locations
 * @property \App\Model\Table\CompaniesTable|\Cake\ORM\Association\BelongsTo $Companies
 * @property \App\Model\Table\GrnRowsTable|\Cake\ORM\Association\HasMany $GrnRows
 *
 * @method \App\Model\Entity\Grn get($primaryKey, $options = [])
 * @method \App\Model\Entity\Grn newEntity($data = null, array $options = [])
 * @method \App\Model\Entity\Grn[] newEntities(array $data, array $options = [])
 * @method \App\Model\Entity\Grn|bool save(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\Grn patchEntity(\Cake\Datasource\EntityInterface $entity, array $data, array $options = [])
 * @method \App\Model\Entity\Grn[] patchEntities($entities, array $data, array $options = [])
 * @method \App\Model\Entity\Grn findOrCreate($search, callable $callback = null, $options = [])
 */
class GrnsTable extends Table
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

        $this->setTable('grns');
        $this->setDisplayField('id');
        $this->setPrimaryKey('id');

       /*  $this->belongsTo('Locations', [
            'foreignKey' => 'location_id',
            'joinType' => 'INNER'
        ]); */
        $this->belongsTo('Companies', [
            'foreignKey' => 'company_id',
            'joinType' => 'INNER'
        ]);
		$this->belongsTo('SupplierLedgers', [
			'className' => 'Ledgers',
            'foreignKey' => 'supplier_ledger_id',
            'joinType' => 'LEFT'
        ]);
        $this->hasMany('GrnRows', [
            'foreignKey' => 'grn_id',
			'saveStrategy' => 'replace'
        ]);
		
		$this->hasMany('ItemLedgers', [
            'foreignKey' => 'item_ledger_id',
			'joinType' => 'INNER',
			'saveStrategy' => 'replace'
        ]);
		 $this->belongsTo('SecondTampGrnRecords');
		
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
            ->integer('voucher_no')
            ->requirePresence('voucher_no', 'create')
            ->notEmpty('voucher_no');

        $validator
            ->date('transaction_date')
            ->requirePresence('transaction_date', 'create')
            ->notEmpty('transaction_date');

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
        //$rules->add($rules->existsIn(['location_id'], 'Locations'));
        $rules->add($rules->existsIn(['company_id'], 'Companies'));
        $rules->add($rules->existsIn(['supplier_ledger_id'], 'SupplierLedgers'));

        return $rules;
    }
}
