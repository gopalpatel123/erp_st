<?php
namespace App\Model\Table;

use Cake\ORM\Query;
use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\Validation\Validator;

/**
 * JournalVouchers Model
 *
 * @property \App\Model\Table\CompaniesTable|\Cake\ORM\Association\BelongsTo $Companies
 * @property \App\Model\Table\JournalVoucherRowsTable|\Cake\ORM\Association\HasMany $JournalVoucherRows
 *
 * @method \App\Model\Entity\JournalVoucher get($primaryKey, $options = [])
 * @method \App\Model\Entity\JournalVoucher newEntity($data = null, array $options = [])
 * @method \App\Model\Entity\JournalVoucher[] newEntities(array $data, array $options = [])
 * @method \App\Model\Entity\JournalVoucher|bool save(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\JournalVoucher patchEntity(\Cake\Datasource\EntityInterface $entity, array $data, array $options = [])
 * @method \App\Model\Entity\JournalVoucher[] patchEntities($entities, array $data, array $options = [])
 * @method \App\Model\Entity\JournalVoucher findOrCreate($search, callable $callback = null, $options = [])
 */
class JournalVouchersTable extends Table
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

        $this->setTable('journal_vouchers');
        $this->setDisplayField('id');
        $this->setPrimaryKey('id');

        $this->belongsTo('Companies', [
            'foreignKey' => 'company_id',
            'joinType' => 'INNER'
        ]);
        $this->hasMany('JournalVoucherRows', [
            'foreignKey' => 'journal_voucher_id',
			'saveStrategy' => 'replace'
		]);
		$this->hasMany('AccountingEntries', [
            'foreignKey' => 'journal_voucher_id',
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
