<?php
namespace App\Model\Table;

use Cake\ORM\Query;
use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\Validation\Validator;
use Cake\Event\Event;
use ArrayObject;
/**
 * JournalVoucherRows Model
 *
 * @property \App\Model\Table\JournalVouchersTable|\Cake\ORM\Association\BelongsTo $JournalVouchers
 * @property \App\Model\Table\LedgersTable|\Cake\ORM\Association\BelongsTo $Ledgers
 *
 * @method \App\Model\Entity\JournalVoucherRow get($primaryKey, $options = [])
 * @method \App\Model\Entity\JournalVoucherRow newEntity($data = null, array $options = [])
 * @method \App\Model\Entity\JournalVoucherRow[] newEntities(array $data, array $options = [])
 * @method \App\Model\Entity\JournalVoucherRow|bool save(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\JournalVoucherRow patchEntity(\Cake\Datasource\EntityInterface $entity, array $data, array $options = [])
 * @method \App\Model\Entity\JournalVoucherRow[] patchEntities($entities, array $data, array $options = [])
 * @method \App\Model\Entity\JournalVoucherRow findOrCreate($search, callable $callback = null, $options = [])
 */
class JournalVoucherRowsTable extends Table
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

        $this->setTable('journal_voucher_rows');
        $this->setDisplayField('id');
        $this->setPrimaryKey('id');

        $this->belongsTo('JournalVouchers', [
            'foreignKey' => 'journal_voucher_id',
            'joinType' => 'INNER'
        ]);
		$this->belongsTo('RefJournalVouchers', [
			'className' => 'JournalVouchers',
            'foreignKey' => 'journal_voucher_id',
            'joinType' => 'LEFT'
        ]);
        $this->belongsTo('Ledgers', [
            'foreignKey' => 'ledger_id',
            'joinType' => 'INNER'
        ]);
		$this->hasMany('ReferenceDetails', [
            'foreignKey' => 'journal_voucher_row_id',
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
        $rules->add($rules->existsIn(['journal_voucher_id'], 'JournalVouchers'));
        $rules->add($rules->existsIn(['ledger_id'], 'Ledgers'));

        return $rules;
    }
}
