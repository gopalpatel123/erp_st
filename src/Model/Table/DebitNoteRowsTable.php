<?php
namespace App\Model\Table;

use Cake\ORM\Query;
use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\Validation\Validator;

/**
 * DebitNoteRows Model
 *
 * @property \App\Model\Table\CreditNotesTable|\Cake\ORM\Association\BelongsTo $CreditNotes
 * @property \App\Model\Table\LedgersTable|\Cake\ORM\Association\BelongsTo $Ledgers
 * @property \App\Model\Table\AccountingEntriesTable|\Cake\ORM\Association\HasMany $AccountingEntries
 * @property \App\Model\Table\ReferenceDetailsTable|\Cake\ORM\Association\HasMany $ReferenceDetails
 *
 * @method \App\Model\Entity\DebitNoteRow get($primaryKey, $options = [])
 * @method \App\Model\Entity\DebitNoteRow newEntity($data = null, array $options = [])
 * @method \App\Model\Entity\DebitNoteRow[] newEntities(array $data, array $options = [])
 * @method \App\Model\Entity\DebitNoteRow|bool save(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\DebitNoteRow patchEntity(\Cake\Datasource\EntityInterface $entity, array $data, array $options = [])
 * @method \App\Model\Entity\DebitNoteRow[] patchEntities($entities, array $data, array $options = [])
 * @method \App\Model\Entity\DebitNoteRow findOrCreate($search, callable $callback = null, $options = [])
 */
class DebitNoteRowsTable extends Table
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

        $this->setTable('debit_note_rows');
        $this->setDisplayField('id');
        $this->setPrimaryKey('id');

        $this->belongsTo('DebitNotes', [
            'foreignKey' => 'debit_note_id',
            'joinType' => 'INNER'
        ]);
		 $this->belongsTo('RefDebitNotes', [
			'className' => 'DebitNotes',
            'foreignKey' => 'debit_note_id',
            'joinType' => 'LEFT'
        ]);
        $this->belongsTo('Ledgers', [
            'foreignKey' => 'ledger_id',
            'joinType' => 'INNER'
        ]);
        $this->hasMany('AccountingEntries', [
            'foreignKey' => 'debit_note_row_id'
        ]);
        $this->hasMany('ReferenceDetails', [
            'foreignKey' => 'debit_note_row_id',
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

       /*  $validator
            ->requirePresence('cr_dr', 'create')
            ->notEmpty('cr_dr');

        $validator
            ->decimal('debit')
            ->allowEmpty('debit');

        $validator
            ->decimal('credit')
            ->allowEmpty('credit'); */

        /* $validator
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

    /**
     * Returns a rules checker object that will be used for validating
     * application integrity.
     *
     * @param \Cake\ORM\RulesChecker $rules The rules object to be modified.
     * @return \Cake\ORM\RulesChecker
     */
    public function buildRules(RulesChecker $rules)
    {
        $rules->add($rules->existsIn(['debit_note_id'], 'DebitNotes'));
        $rules->add($rules->existsIn(['ledger_id'], 'Ledgers'));

        return $rules;
    }
}
