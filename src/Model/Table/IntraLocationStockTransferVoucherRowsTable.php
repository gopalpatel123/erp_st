<?php
namespace App\Model\Table;

use Cake\ORM\Query;
use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\Validation\Validator;

/**
 * IntraLocationStockTransferVoucherRows Model
 *
 * @property \App\Model\Table\IntraLocationStockTransferVouchersTable|\Cake\ORM\Association\BelongsTo $IntraLocationStockTransferVouchers
 * @property \App\Model\Table\ItemsTable|\Cake\ORM\Association\BelongsTo $Items
 *
 * @method \App\Model\Entity\IntraLocationStockTransferVoucherRow get($primaryKey, $options = [])
 * @method \App\Model\Entity\IntraLocationStockTransferVoucherRow newEntity($data = null, array $options = [])
 * @method \App\Model\Entity\IntraLocationStockTransferVoucherRow[] newEntities(array $data, array $options = [])
 * @method \App\Model\Entity\IntraLocationStockTransferVoucherRow|bool save(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\IntraLocationStockTransferVoucherRow patchEntity(\Cake\Datasource\EntityInterface $entity, array $data, array $options = [])
 * @method \App\Model\Entity\IntraLocationStockTransferVoucherRow[] patchEntities($entities, array $data, array $options = [])
 * @method \App\Model\Entity\IntraLocationStockTransferVoucherRow findOrCreate($search, callable $callback = null, $options = [])
 */
class IntraLocationStockTransferVoucherRowsTable extends Table
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

        $this->setTable('intra_location_stock_transfer_voucher_rows');
        $this->setDisplayField('id');
        $this->setPrimaryKey('id');

        $this->belongsTo('IntraLocationStockTransferVouchers', [
            'foreignKey' => 'intra_location_stock_transfer_voucher_id',
            'joinType' => 'INNER'
        ]);
        $this->belongsTo('Items', [
            'foreignKey' => 'item_id',
            'joinType' => 'INNER'
        ]);
		$this->hasMany('ItemLedgers', [
            'foreignKey' => 'intra_location_stock_transfer_voucher_row_id',
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
        $rules->add($rules->existsIn(['intra_location_stock_transfer_voucher_id'], 'IntraLocationStockTransferVouchers'));
        $rules->add($rules->existsIn(['item_id'], 'Items'));

        return $rules;
    }
}
