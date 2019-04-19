<?php
namespace App\Model\Table;

use Cake\ORM\Query;
use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\Validation\Validator;

/**
 * PurchaseInvoiceRows Model
 *
 * @property \App\Model\Table\PurchaseInvoicesTable|\Cake\ORM\Association\BelongsTo $PurchaseInvoices
 * @property \App\Model\Table\ItemsTable|\Cake\ORM\Association\BelongsTo $Items
 * @property \App\Model\Table\GstFiguresTable|\Cake\ORM\Association\BelongsTo $GstFigures
 *
 * @method \App\Model\Entity\PurchaseInvoiceRow get($primaryKey, $options = [])
 * @method \App\Model\Entity\PurchaseInvoiceRow newEntity($data = null, array $options = [])
 * @method \App\Model\Entity\PurchaseInvoiceRow[] newEntities(array $data, array $options = [])
 * @method \App\Model\Entity\PurchaseInvoiceRow|bool save(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\PurchaseInvoiceRow patchEntity(\Cake\Datasource\EntityInterface $entity, array $data, array $options = [])
 * @method \App\Model\Entity\PurchaseInvoiceRow[] patchEntities($entities, array $data, array $options = [])
 * @method \App\Model\Entity\PurchaseInvoiceRow findOrCreate($search, callable $callback = null, $options = [])
 */
class PurchaseInvoiceRowsTable extends Table
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

        $this->setTable('purchase_invoice_rows');
        $this->setDisplayField('id');
        $this->setPrimaryKey('id');

        $this->belongsTo('PurchaseInvoices', [
            'foreignKey' => 'purchase_invoice_id',
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
		
		 $this->belongsTo('GstFigures', [
            'foreignKey' => 'item_gst_figure_id',
            'joinType' => 'INNER'
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
        $rules->add($rules->existsIn(['purchase_invoice_id'], 'PurchaseInvoices'));
        $rules->add($rules->existsIn(['item_id'], 'Items'));
        $rules->add($rules->existsIn(['gst_figure_id'], 'GstFigures'));

        return $rules;
    }
}
