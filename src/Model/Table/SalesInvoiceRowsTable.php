<?php
namespace App\Model\Table;

use Cake\ORM\Query;
use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\Validation\Validator;

/**
 * SalesInvoiceRows Model
 *
 * @property \App\Model\Table\SalesInvoicesTable|\Cake\ORM\Association\BelongsTo $SalesInvoices
 * @property \App\Model\Table\ItemsTable|\Cake\ORM\Association\BelongsTo $Items
 * @property \App\Model\Table\GstFiguresTable|\Cake\ORM\Association\BelongsTo $GstFigures
 * @property \App\Model\Table\OutputCgstLedgersTable|\Cake\ORM\Association\BelongsTo $OutputCgstLedgers
 * @property \App\Model\Table\OutputSgstLedgersTable|\Cake\ORM\Association\BelongsTo $OutputSgstLedgers
 * @property \App\Model\Table\OutputIgstLedgersTable|\Cake\ORM\Association\BelongsTo $OutputIgstLedgers
 *
 * @method \App\Model\Entity\SalesInvoiceRow get($primaryKey, $options = [])
 * @method \App\Model\Entity\SalesInvoiceRow newEntity($data = null, array $options = [])
 * @method \App\Model\Entity\SalesInvoiceRow[] newEntities(array $data, array $options = [])
 * @method \App\Model\Entity\SalesInvoiceRow|bool save(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\SalesInvoiceRow patchEntity(\Cake\Datasource\EntityInterface $entity, array $data, array $options = [])
 * @method \App\Model\Entity\SalesInvoiceRow[] patchEntities($entities, array $data, array $options = [])
 * @method \App\Model\Entity\SalesInvoiceRow findOrCreate($search, callable $callback = null, $options = [])
 */
class SalesInvoiceRowsTable extends Table
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

        $this->setTable('sales_invoice_rows');
        $this->setDisplayField('id');
        $this->setPrimaryKey('id');

        $this->belongsTo('SalesInvoices', [
            'foreignKey' => 'sales_invoice_id',
            'joinType' => 'INNER'
        ]);
        $this->belongsTo('Items', [
            'foreignKey' => 'item_id',
            'joinType' => 'INNER'
        ]);
        $this->belongsTo('GstFigures', [
            'foreignKey' => 'gst_figure_id',
            'joinType' => 'INNER'
        ]);
		$this->belongsTo('OutputCgstLedgers', [
			'className' => 'Ledgers',
			'foreignKey' => 'output_cgst_ledger_id',
			'propertyName' => 'OutputCgstLedgers',
		]);
		$this->belongsTo('OutputSgstLedgers', [
			'className' => 'Ledgers',
			'foreignKey' => 'output_sgst_ledger_id',
			'propertyName' => 'OutputSgstLedgers',
		]);
		$this->belongsTo('OutputIgstLedgers', [
			'className' => 'Ledgers',
			'foreignKey' => 'output_igst_ledger_id',
			'propertyName' => 'OutputIgstLedgers',
		]);
		$this->belongsTo('Ledgers', [
            'foreignKey' => 'ledger_id',
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
            ->decimal('quantity')
            ->requirePresence('quantity', 'create')
            ->notEmpty('quantity');

        $validator
            ->decimal('rate')
            ->requirePresence('rate', 'create')
            ->notEmpty('rate');

        
        $validator
            ->decimal('taxable_value')
            ->requirePresence('taxable_value', 'create')
            ->notEmpty('taxable_value');

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
        $rules->add($rules->existsIn(['sales_invoice_id'], 'SalesInvoices'));
        $rules->add($rules->existsIn(['item_id'], 'Items'));
        $rules->add($rules->existsIn(['gst_figure_id'], 'GstFigures'));
        //$rules->add($rules->existsIn(['output_cgst_ledger_id'], 'OutputCgstLedgers'));
        //$rules->add($rules->existsIn(['output_sgst_ledger_id'], 'OutputSgstLedgers'));
        //$rules->add($rules->existsIn(['output_igst_ledger_id'], 'OutputIgstLedgers'));
        return $rules;
    }
}
