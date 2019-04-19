<?php
namespace App\Model\Table;

use Cake\ORM\Query;
use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\Validation\Validator;

/**
 * UserRights Model
 *
 * @property \App\Model\Table\PagesTable|\Cake\ORM\Association\BelongsTo $Pages
 * @property \App\Model\Table\UsersTable|\Cake\ORM\Association\BelongsTo $Users
 *
 * @method \App\Model\Entity\UserRight get($primaryKey, $options = [])
 * @method \App\Model\Entity\UserRight newEntity($data = null, array $options = [])
 * @method \App\Model\Entity\UserRight[] newEntities(array $data, array $options = [])
 * @method \App\Model\Entity\UserRight|bool save(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\UserRight patchEntity(\Cake\Datasource\EntityInterface $entity, array $data, array $options = [])
 * @method \App\Model\Entity\UserRight[] patchEntities($entities, array $data, array $options = [])
 * @method \App\Model\Entity\UserRight findOrCreate($search, callable $callback = null, $options = [])
 */
class UserRightsTable extends Table
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

        $this->setTable('user_rights');
        $this->setDisplayField('id');
        $this->setPrimaryKey('id');

        $this->hasMany('Pages', [
            'foreignKey' => 'page_id',
            'joinType' => 'LEFT'
        ]);
        $this->hasMany('Users', [
            'foreignKey' => 'user_id',
            'joinType' => 'LEFT'
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

    /**
     * Returns a rules checker object that will be used for validating
     * application integrity.
     *
     * @param \Cake\ORM\RulesChecker $rules The rules object to be modified.
     * @return \Cake\ORM\RulesChecker
     */
    public function buildRules(RulesChecker $rules)
    {
        $rules->add($rules->existsIn(['page_id'], 'Pages'));
        $rules->add($rules->existsIn(['user_id'], 'Users'));

        return $rules;
    }
}
