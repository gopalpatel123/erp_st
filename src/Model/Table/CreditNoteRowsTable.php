<?php
namespace App\Model\Table;

use Cake\ORM\Query;
use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\Validation\Validator;
use Cake\Event\Event;
use ArrayObject;
/**
 * CreditNoteRows Model
 *
 * @property \App\Model\Table\CreditNotesTable|\Cake\ORM\Association\BelongsTo $CreditNotes
 * @property \App\Model\Table\ItemsTable|\Cake\ORM\Association\BelongsTo $Items
 * @property \App\Model\Table\GstFiguresTable|\Cake\ORM\Association\BelongsTo $GstFigures
 *
 * @method \App\Model\Entity\CreditNoteRow get($primaryKey, $options = [])
 * @method \App\Model\Entity\CreditNoteRow newEntity($data = null, array $options = [])
 * @method \App\Model\Entity\CreditNoteRow[] newEntities(array $data, array $options = [])
 * @method \App\Model\Entity\CreditNoteRow|bool save(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\CreditNoteRow patchEntity(\Cake\Datasource\EntityInterface $entity, array $data, array $options = [])
 * @method \App\Model\Entity\CreditNoteRow[] patchEntities($entities, array $data, array $options = [])
 * @method \App\Model\Entity\CreditNoteRow findOrCreate($search, callable $callback = null, $options = [])
 */
class CreditNoteRowsTable extends Table
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

        $this->setTable('credit_note_rows');
        $this->setDisplayField('id');
        $this->setPrimaryKey('id');

        $this->belongsTo('CreditNotes', [
            'foreignKey' => 'credit_note_id',
            'joinType' => 'INNER'
        ]);
		 $this->belongsTo('RefCreditNotes', [
			'className' => 'CreditNotes',
            'foreignKey' => 'credit_note_id',
            'joinType' => 'LEFT'
        ]);
       $this->belongsTo('Ledgers', [
            'foreignKey' => 'ledger_id',
            'joinType' => 'INNER'
        ]);
		$this->hasMany('ReferenceDetails', [
            'foreignKey' => 'credit_note_row_id',
			'saveStrategy'=>'replace'
        ]);
		 $this->belongsTo('Companies', [
            'foreignKey' => 'company_id',
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
        $rules->add($rules->existsIn(['credit_note_id'], 'CreditNotes'));
		$rules->add($rules->existsIn(['ledger_id'], 'Ledgers'));
        return $rules;
    }
}
