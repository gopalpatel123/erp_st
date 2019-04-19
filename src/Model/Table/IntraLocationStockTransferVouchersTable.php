<?php
namespace App\Model\Table;

use Cake\ORM\Query;
use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\Validation\Validator;

/**
 * IntraLocationStockTransferVouchers Model
 *
 * @property \App\Model\Table\CompaniesTable|\Cake\ORM\Association\BelongsTo $Companies
 * @property \App\Model\Table\LocationsTable|\Cake\ORM\Association\BelongsTo $Locations
 * @property \App\Model\Table\IntraLocationStockTransferVoucherRowsTable|\Cake\ORM\Association\HasMany $IntraLocationStockTransferVoucherRows
 *
 * @method \App\Model\Entity\IntraLocationStockTransferVoucher get($primaryKey, $options = [])
 * @method \App\Model\Entity\IntraLocationStockTransferVoucher newEntity($data = null, array $options = [])
 * @method \App\Model\Entity\IntraLocationStockTransferVoucher[] newEntities(array $data, array $options = [])
 * @method \App\Model\Entity\IntraLocationStockTransferVoucher|bool save(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\IntraLocationStockTransferVoucher patchEntity(\Cake\Datasource\EntityInterface $entity, array $data, array $options = [])
 * @method \App\Model\Entity\IntraLocationStockTransferVoucher[] patchEntities($entities, array $data, array $options = [])
 * @method \App\Model\Entity\IntraLocationStockTransferVoucher findOrCreate($search, callable $callback = null, $options = [])
 */
class IntraLocationStockTransferVouchersTable extends Table
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

        $this->setTable('intra_location_stock_transfer_vouchers');
        $this->setDisplayField('id');
        $this->setPrimaryKey('id');

        $this->belongsTo('Companies', [
            'foreignKey' => 'company_id',
            'joinType' => 'INNER'
        ]);
        $this->belongsTo('Locations', [
            'foreignKey' => 'location_id',
            'joinType' => 'INNER'
        ]);
		$this->belongsTo('Grns', [
            'foreignKey' => 'grn_id',
            'joinType' => 'INNER'
        ]);
        $this->hasMany('IntraLocationStockTransferVoucherRows', [
            'foreignKey' => 'intra_location_stock_transfer_voucher_id',
			'saveStrategy' => 'replace',
        ]);
		$this->belongsTo('TransferFromLocations', [
			'className' => 'Locations',
			'foreignKey' => 'transfer_from_location_id',
			'propertyName' => 'TransferFromLocations',
		]);
		$this->belongsTo('TransferToLocations', [
			'className' => 'Locations',
			'foreignKey' => 'transfer_to_location_id',
			'propertyName' => 'TransferToLocations',
		]);
		$this->hasMany('ItemLedgers', [
            'foreignKey' => 'intra_location_stock_transfer_voucher_id',
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
            ->integer('transfer_from_location_id')
            ->requirePresence('transfer_from_location_id', 'create')
            ->notEmpty('transfer_from_location_id');

        $validator
            ->integer('transfer_to_location_id')
            ->requirePresence('transfer_to_location_id', 'create')
            ->notEmpty('transfer_to_location_id');

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
        $rules->add($rules->existsIn(['company_id'], 'Companies'));
        $rules->add($rules->existsIn(['transfer_from_location_id'], 'TransferFromLocations'));
        $rules->add($rules->existsIn(['transfer_to_location_id'], 'TransferToLocations'));

        return $rules;
    }
}
