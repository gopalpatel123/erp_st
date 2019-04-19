<?php
namespace App\Model\Table;

use Cake\ORM\Query;
use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\Validation\Validator;

/**
 * DebitNotes Model
 *
 * @property \App\Model\Table\CompaniesTable|\Cake\ORM\Association\BelongsTo $Companies
 * @property \App\Model\Table\AccountingEntriesTable|\Cake\ORM\Association\HasMany $AccountingEntries
 * @property \App\Model\Table\ReferenceDetailsTable|\Cake\ORM\Association\HasMany $ReferenceDetails
 *
 * @method \App\Model\Entity\DebitNote get($primaryKey, $options = [])
 * @method \App\Model\Entity\DebitNote newEntity($data = null, array $options = [])
 * @method \App\Model\Entity\DebitNote[] newEntities(array $data, array $options = [])
 * @method \App\Model\Entity\DebitNote|bool save(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\DebitNote patchEntity(\Cake\Datasource\EntityInterface $entity, array $data, array $options = [])
 * @method \App\Model\Entity\DebitNote[] patchEntities($entities, array $data, array $options = [])
 * @method \App\Model\Entity\DebitNote findOrCreate($search, callable $callback = null, $options = [])
 */
class DebitNotesTable extends Table
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

        $this->setTable('debit_notes');
        $this->setDisplayField('id');
        $this->setPrimaryKey('id');

        $this->belongsTo('Companies', [
            'foreignKey' => 'company_id',
            'joinType' => 'INNER'
        ]);
       
        $this->hasMany('ReferenceDetails', [
            'foreignKey' => 'debit_note_id'
        ]);
		$this->hasMany('AccountingEntries', [
            'foreignKey' => 'debit_note_id',
            'joinType' => 'INNER'
        ]);
		
		 $this->hasMany('DebitNoteRows', [
            'foreignKey' => 'debit_note_id',
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

        $validator
            ->integer('voucher_no')
            ->requirePresence('voucher_no', 'create')
            ->notEmpty('voucher_no');

        $validator
            ->date('transaction_date')
            ->requirePresence('transaction_date', 'create')
            ->notEmpty('transaction_date');

        /*$validator
            ->requirePresence('narration', 'create')
            ->notEmpty('narration');*/

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
