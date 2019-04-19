<?php
namespace App\Model\Table;

use Cake\ORM\Query;
use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\Validation\Validator;

/**
 * PurchaseVouchers Model
 *
 * @property \App\Model\Table\CompaniesTable|\Cake\ORM\Association\BelongsTo $Companies
 * @property \App\Model\Table\PurchaseVoucherRowsTable|\Cake\ORM\Association\HasMany $PurchaseVoucherRows
 *
 * @method \App\Model\Entity\PurchaseVoucher get($primaryKey, $options = [])
 * @method \App\Model\Entity\PurchaseVoucher newEntity($data = null, array $options = [])
 * @method \App\Model\Entity\PurchaseVoucher[] newEntities(array $data, array $options = [])
 * @method \App\Model\Entity\PurchaseVoucher|bool save(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\PurchaseVoucher patchEntity(\Cake\Datasource\EntityInterface $entity, array $data, array $options = [])
 * @method \App\Model\Entity\PurchaseVoucher[] patchEntities($entities, array $data, array $options = [])
 * @method \App\Model\Entity\PurchaseVoucher findOrCreate($search, callable $callback = null, $options = [])
 */
class PurchaseVouchersTable extends Table
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

        $this->setTable('purchase_vouchers');
        $this->setDisplayField('id');
        $this->setPrimaryKey('id');

        $this->belongsTo('Companies', [
            'foreignKey' => 'company_id',
            'joinType' => 'INNER'
        ]);
        $this->hasMany('PurchaseVoucherRows', [
            'foreignKey' => 'purchase_voucher_id',
			'saveStrategy' => 'replace'
        ]);
		$this->hasMany('AccountingEntries', [
            'foreignKey' => 'purchase_voucher_id',
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


        /*$validator
            ->requirePresence('supplier_invoice_no', 'create')
            ->notEmpty('supplier_invoice_no');*/

        

        /*$validator
            ->decimal('voucher_amount')
            ->requirePresence('voucher_amount', 'create')
            ->notEmpty('voucher_amount');*/

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

        return $rules;
    }
}
