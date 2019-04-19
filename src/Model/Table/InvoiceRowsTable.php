<?php
namespace App\Model\Table;

use Cake\ORM\Query;
use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\Validation\Validator;

/**
 * InvoiceRows Model
 *
 * @property \App\Model\Table\InvoicesTable|\Cake\ORM\Association\BelongsTo $Invoices
 *
 * @method \App\Model\Entity\InvoiceRow get($primaryKey, $options = [])
 * @method \App\Model\Entity\InvoiceRow newEntity($data = null, array $options = [])
 * @method \App\Model\Entity\InvoiceRow[] newEntities(array $data, array $options = [])
 * @method \App\Model\Entity\InvoiceRow|bool save(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\InvoiceRow patchEntity(\Cake\Datasource\EntityInterface $entity, array $data, array $options = [])
 * @method \App\Model\Entity\InvoiceRow[] patchEntities($entities, array $data, array $options = [])
 * @method \App\Model\Entity\InvoiceRow findOrCreate($search, callable $callback = null, $options = [])
 */
class InvoiceRowsTable extends Table
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

        $this->setTable('invoice_rows');
        $this->setDisplayField('id');
        $this->setPrimaryKey('id');

        $this->belongsTo('Invoices', [
            'foreignKey' => 'invoice_id',
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

        $validator
            ->allowEmpty('item');

        $validator
            ->allowEmpty('hsn_code');

        $validator
            ->decimal('quantity')
            ->allowEmpty('quantity');

        $validator
            ->decimal('rate')
            ->allowEmpty('rate');

        $validator
            ->decimal('amount')
            ->allowEmpty('amount');

        $validator
            ->decimal('discount_rate')
            ->allowEmpty('discount_rate');

        $validator
            ->decimal('discount_amount')
            ->allowEmpty('discount_amount');

        $validator
            ->decimal('taxable_value')
            ->allowEmpty('taxable_value');

        $validator
            ->decimal('cgst_rate')
            ->allowEmpty('cgst_rate');

        $validator
            ->decimal('cgst_amount')
            ->allowEmpty('cgst_amount');

        $validator
            ->decimal('sgst_rate')
            ->allowEmpty('sgst_rate');

        $validator
            ->decimal('sgst_amount')
            ->allowEmpty('sgst_amount');

        $validator
            ->decimal('total')
            ->allowEmpty('total');

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
        $rules->add($rules->existsIn(['invoice_id'], 'Invoices'));

        return $rules;
    }
}
