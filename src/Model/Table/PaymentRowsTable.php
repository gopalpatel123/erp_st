<?php
namespace App\Model\Table;

use Cake\ORM\Query;
use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\Validation\Validator;
use Cake\Event\Event;
use ArrayObject;
/**
 * PaymentRows Model
 *
 * @property \App\Model\Table\PaymentsTable|\Cake\ORM\Association\BelongsTo $Payments
 * @property \App\Model\Table\CompaniesTable|\Cake\ORM\Association\BelongsTo $Companies
 * @property \App\Model\Table\LedgersTable|\Cake\ORM\Association\BelongsTo $Ledgers
 *
 * @method \App\Model\Entity\PaymentRow get($primaryKey, $options = [])
 * @method \App\Model\Entity\PaymentRow newEntity($data = null, array $options = [])
 * @method \App\Model\Entity\PaymentRow[] newEntities(array $data, array $options = [])
 * @method \App\Model\Entity\PaymentRow|bool save(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\PaymentRow patchEntity(\Cake\Datasource\EntityInterface $entity, array $data, array $options = [])
 * @method \App\Model\Entity\PaymentRow[] patchEntities($entities, array $data, array $options = [])
 * @method \App\Model\Entity\PaymentRow findOrCreate($search, callable $callback = null, $options = [])
 */
class PaymentRowsTable extends Table
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

        $this->setTable('payment_rows');
        $this->setDisplayField('id');
        $this->setPrimaryKey('id');

        $this->belongsTo('Payments', [
            'foreignKey' => 'payment_id',
            'joinType' => 'INNER'
        ]);
		$this->belongsTo('RefPayments', [
			'className' => 'Payments',
            'foreignKey' => 'payment_id',
            'joinType' => 'LEFT'
        ]);
        $this->belongsTo('Ledgers', [
            'foreignKey' => 'ledger_id',
            'joinType' => 'INNER'
        ]);
		$this->hasMany('ReferenceDetails', [
            'foreignKey' => 'payment_row_id',
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
        $rules->add($rules->existsIn(['payment_id'], 'Payments'));
        $rules->add($rules->existsIn(['ledger_id'], 'Ledgers'));

        return $rules;
    }
}
