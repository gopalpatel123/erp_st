<?php
namespace App\Model\Table;

use Cake\ORM\Query;
use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\Validation\Validator;
use Cake\Event\Event;
use ArrayObject;

/**
 * PurchaseVoucherRows Model
 *
 * @property \App\Model\Table\PurchaseVouchersTable|\Cake\ORM\Association\BelongsTo $PurchaseVouchers
 * @property \App\Model\Table\LedgersTable|\Cake\ORM\Association\BelongsTo $Ledgers
 *
 * @method \App\Model\Entity\PurchaseVoucherRow get($primaryKey, $options = [])
 * @method \App\Model\Entity\PurchaseVoucherRow newEntity($data = null, array $options = [])
 * @method \App\Model\Entity\PurchaseVoucherRow[] newEntities(array $data, array $options = [])
 * @method \App\Model\Entity\PurchaseVoucherRow|bool save(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\PurchaseVoucherRow patchEntity(\Cake\Datasource\EntityInterface $entity, array $data, array $options = [])
 * @method \App\Model\Entity\PurchaseVoucherRow[] patchEntities($entities, array $data, array $options = [])
 * @method \App\Model\Entity\PurchaseVoucherRow findOrCreate($search, callable $callback = null, $options = [])
 */
class PurchaseVoucherRowsTable extends Table
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

        $this->setTable('purchase_voucher_rows');
        $this->setDisplayField('id');
        $this->setPrimaryKey('id');

        $this->belongsTo('PurchaseVouchers', [
            'foreignKey' => 'purchase_voucher_id',
            'joinType' => 'INNER'
        ]);
		$this->belongsTo('RefPurchaseVouchers', [
			'className' => 'PurchaseVouchers',
            'foreignKey' => 'purchase_voucher_id',
            'joinType' => 'LEFT'
        ]);
        $this->belongsTo('Ledgers', [
            'foreignKey' => 'ledger_id',
            'joinType' => 'INNER'
        ]);
		$this->hasMany('ReferenceDetails', [
            'foreignKey' => 'purchase_voucher_row_id',
			'saveStrategy'=>'replace'
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

        

        return $validator;
    }

	public function beforeMarshal(Event $event, ArrayObject $data)
    {
		if(@$data['cheque_date']!="")
		{
			@$data['cheque_date'] = trim(date('Y-m-d',strtotime(@$data['cheque_date'])));
		}
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
        $rules->add($rules->existsIn(['purchase_voucher_id'], 'PurchaseVouchers'));
        $rules->add($rules->existsIn(['ledger_id'], 'Ledgers'));

        return $rules;
    }
}
