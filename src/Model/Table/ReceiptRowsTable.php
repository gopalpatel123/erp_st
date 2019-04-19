<?php
namespace App\Model\Table;

use Cake\ORM\Query;
use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\Validation\Validator;
use Cake\Event\Event;
use ArrayObject;
/**
 * ReceiptRows Model
 *
 * @property \App\Model\Table\ReceiptsTable|\Cake\ORM\Association\BelongsTo $Receipts
 * @property \App\Model\Table\CompaniesTable|\Cake\ORM\Association\BelongsTo $Companies
 * @property \App\Model\Table\LedgersTable|\Cake\ORM\Association\BelongsTo $Ledgers
 * @property \App\Model\Table\ReferenceDetailsTable|\Cake\ORM\Association\HasMany $ReferenceDetails
 *
 * @method \App\Model\Entity\ReceiptRow get($primaryKey, $options = [])
 * @method \App\Model\Entity\ReceiptRow newEntity($data = null, array $options = [])
 * @method \App\Model\Entity\ReceiptRow[] newEntities(array $data, array $options = [])
 * @method \App\Model\Entity\ReceiptRow|bool save(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\ReceiptRow patchEntity(\Cake\Datasource\EntityInterface $entity, array $data, array $options = [])
 * @method \App\Model\Entity\ReceiptRow[] patchEntities($entities, array $data, array $options = [])
 * @method \App\Model\Entity\ReceiptRow findOrCreate($search, callable $callback = null, $options = [])
 */
class ReceiptRowsTable extends Table
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

        $this->setTable('receipt_rows');
        $this->setDisplayField('id');
        $this->setPrimaryKey('id');

        $this->belongsTo('Receipts', [
            'foreignKey' => 'receipt_id',
            'joinType' => 'INNER'
        ]);
		$this->belongsTo('RefReceipts', [
			'className' => 'Receipts',
            'foreignKey' => 'receipt_id',
            'joinType' => 'LEFT'
        ]);
        $this->belongsTo('Companies', [
            'foreignKey' => 'company_id',
            'joinType' => 'INNER'
        ]);
        $this->belongsTo('Ledgers', [
            'foreignKey' => 'ledger_id',
            'joinType' => 'INNER'
        ]);
       $this->hasMany('ReferenceDetails', [
            'foreignKey' => 'receipt_row_id',
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

     /*    $validator
            ->requirePresence('type', 'create')
            ->notEmpty('type');

        $validator
            ->decimal('debit')
            ->requirePresence('debit', 'create')
            ->notEmpty('debit');

        $validator
            ->decimal('credit')
            ->requirePresence('credit', 'create')
            ->notEmpty('credit');

        $validator
            ->requirePresence('mode_of_payment', 'create')
            ->notEmpty('mode_of_payment');

        $validator
            ->requirePresence('cheque_no', 'create')
            ->notEmpty('cheque_no');

        $validator
            ->date('cheque_date')
            ->requirePresence('cheque_date', 'create')
            ->notEmpty('cheque_date'); */

        return $validator;
    }

	public function beforeMarshal(Event $event, ArrayObject $data)
    {
        @$data['cheque_date'] = trim(date('Y-m-d',strtotime(@$data['cheque_date'])));
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
        $rules->add($rules->existsIn(['receipt_id'], 'Receipts'));
        $rules->add($rules->existsIn(['company_id'], 'Companies'));
        $rules->add($rules->existsIn(['ledger_id'], 'Ledgers'));

        return $rules;
    }
}
