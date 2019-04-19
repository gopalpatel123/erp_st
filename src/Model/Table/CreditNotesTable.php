<?php
namespace App\Model\Table;

use Cake\ORM\Query;
use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\Validation\Validator;
use Cake\Event\Event;
use ArrayObject;
/**
 * CreditNotes Model
 *
 * @property \App\Model\Table\CompaniesTable|\Cake\ORM\Association\BelongsTo $Companies
 * @property \App\Model\Table\AccountingEntriesTable|\Cake\ORM\Association\HasMany $AccountingEntries
 * @property \App\Model\Table\CreditNoteRowsTable|\Cake\ORM\Association\HasMany $CreditNoteRows
 * @property \App\Model\Table\ItemLedgersTable|\Cake\ORM\Association\HasMany $ItemLedgers
 *
 * @method \App\Model\Entity\CreditNote get($primaryKey, $options = [])
 * @method \App\Model\Entity\CreditNote newEntity($data = null, array $options = [])
 * @method \App\Model\Entity\CreditNote[] newEntities(array $data, array $options = [])
 * @method \App\Model\Entity\CreditNote|bool save(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\CreditNote patchEntity(\Cake\Datasource\EntityInterface $entity, array $data, array $options = [])
 * @method \App\Model\Entity\CreditNote[] patchEntities($entities, array $data, array $options = [])
 * @method \App\Model\Entity\CreditNote findOrCreate($search, callable $callback = null, $options = [])
 */
class CreditNotesTable extends Table
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

        $this->setTable('credit_notes');
        $this->setDisplayField('id');
        $this->setPrimaryKey('id');

        $this->belongsTo('Companies', [
            'foreignKey' => 'company_id',
            'joinType' => 'INNER'
        ]);
   
        $this->hasMany('CreditNoteRows', [
            'foreignKey' => 'credit_note_id',
			'saveStrategy'=>'replace'
        ]);
        $this->hasMany('ReferenceDetails', [
            'foreignKey' => 'credit_note_id'
        ]);
		$this->hasMany('AccountingEntries', [
            'foreignKey' => 'credit_note_id',
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
        @$data['transaction_date'] = trim(date('Y-m-d',strtotime(@$data['transaction_date'])));
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
