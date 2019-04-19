<?php
namespace App\Model\Table;

use Cake\ORM\Query;
use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\Validation\Validator;
use Cake\Event\Event;
use ArrayObject;
/**
 * Invoices Model
 *
 * @method \App\Model\Entity\Invoice get($primaryKey, $options = [])
 * @method \App\Model\Entity\Invoice newEntity($data = null, array $options = [])
 * @method \App\Model\Entity\Invoice[] newEntities(array $data, array $options = [])
 * @method \App\Model\Entity\Invoice|bool save(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\Invoice patchEntity(\Cake\Datasource\EntityInterface $entity, array $data, array $options = [])
 * @method \App\Model\Entity\Invoice[] patchEntities($entities, array $data, array $options = [])
 * @method \App\Model\Entity\Invoice findOrCreate($search, callable $callback = null, $options = [])
 */
class InvoicesTable extends Table
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

        $this->setTable('invoices');
        $this->setDisplayField('id');
        $this->setPrimaryKey('id');
		
		$this->hasMany('InvoiceRows', [
            'foreignKey' => 'invoice_id',
			'saveStrategy' => 'replace'
        ]);
    }

	public function beforeMarshal(Event $event, ArrayObject $data, ArrayObject $options) {
		$data['invoice_date'] = date('Y-m-d',strtotime($data['invoice_date']));
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
            ->integer('invoice_no')
            ->requirePresence('invoice_no', 'create')
            ->notEmpty('invoice_no');

        $validator
            ->date('invoice_date')
            ->requirePresence('invoice_date', 'create')
            ->notEmpty('invoice_date');

        $validator
            ->allowEmpty('state');

        $validator
            ->requirePresence('party_name', 'create')
            ->notEmpty('party_name');

        $validator
            ->allowEmpty('party_address');

        $validator
            ->allowEmpty('party_mobile');

        $validator
            ->allowEmpty('party_state');

        $validator
            ->allowEmpty('party_gst');

        $validator
            ->decimal('total_amount_before_tax')
            ->requirePresence('total_amount_before_tax', 'create')
            ->allowEmpty('total_amount_before_tax');

        $validator
            ->decimal('total_cgst')
            ->requirePresence('total_cgst', 'create')
            ->allowEmpty('total_cgst');

        $validator
            ->decimal('total_sgst')
            ->requirePresence('total_sgst', 'create')
            ->allowEmpty('total_sgst');

        $validator
            ->decimal('total_amount_after_tax')
            ->requirePresence('total_amount_after_tax', 'create')
            ->allowEmpty('total_amount_after_tax');

        return $validator;
    }
}
