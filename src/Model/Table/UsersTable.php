<?php
declare(strict_types=1);

namespace App\Model\Table;

use Cake\ORM\Query;
use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\Validation\Validator;


use Cake\Event\EventInterface;
use ArrayObject;

/**
 * Users Model
 *
 * @property \App\Model\Table\PostsTable&\Cake\ORM\Association\HasMany $Posts
 *
 * @method \App\Model\Entity\User get($primaryKey, $options = [])
 * @method \App\Model\Entity\User newEntity($data = null, array $options = [])
 * @method \App\Model\Entity\User[] newEntities(array $data, array $options = [])
 * @method \App\Model\Entity\User|false save(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\User saveOrFail(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\User patchEntity(\Cake\Datasource\EntityInterface $entity, array $data, array $options = [])
 * @method \App\Model\Entity\User[] patchEntities($entities, array $data, array $options = [])
 * @method \App\Model\Entity\User findOrCreate($search, callable $callback = null, $options = [])
 *
 * @mixin \Cake\ORM\Behavior\TimestampBehavior
 */
class UsersTable extends Table
{
    /**
     * Initialize method
     *
     * @param array $config The configuration for the Table.
     * @return void
     */
    public function initialize(array $config): void
    {
        parent::initialize($config);

        $this->setTable('users');
        $this->setDisplayField('name');
        $this->setPrimaryKey('id');

        $this->addBehavior('Timestamp');

        $this->hasMany('EditorsPolicies', [
            'foreignKey' => 'user_id',
        ]);
        $this->hasMany('Policies', [
            'foreignKey' => 'user_id',
        ]);
        $this->hasMany('Posts', [
            'foreignKey' => 'user_id',
        ]);
    }

    /**
     * Default validation rules.
     *
     * @param \Cake\Validation\Validator $validator Validator instance.
     * @return \Cake\Validation\Validator
     */
    public function validationDefault(Validator $validator): Validator
    {
        $validator
            ->integer('id')
            ->allowEmptyString('id', null, 'create');

        $validator
            ->scalar('name')
            ->notEmptyString('name', 'Please fill this field')
            ->maxLength('name', 255)
            ->minLength('name',2);

        $validator
            ->scalar('username')
            ->notEmptyString('username', 'Please fill this field')
            ->maxLength('username', 255)
            ->minLength('username',2);

        $validator
            ->email('email')
            ->notEmptyString('email', 'Please fill this field')
            ->maxLength('email', 255)
            ->minLength('email',2);
            

        $validator
            ->scalar('password')
            ->maxLength('password', 255)
            ->notEmptyString('name', 'Please fill this field')
            ->minLength('name',2);

        return $validator;
    }

    // Using the beforeMarshal event to check if the password and confirmed passwords are the same
    public function beforeMarshal(EventInterface $event, ArrayObject $data, ArrayObject $options)
    {
        
        if(isset($data['password'])) {
            if (isset($data['password_confirm'])) {
                if($data['password'] != $data['password_confirm']) {
                
                    //using flash to shortcut using the validator, given time - would
                    //investigate using a custom validator - but as password_confirm is not
                    //present in the database I am anticipating that field never hits the validator, making
                    //it none trivial
                    $this->error = __('Your Passwords did not match');
                    return false;
                }
            } else {
                $this->error = __('Your Passwords did not match');
                return false;
            }
        }
    }
    
    /**
     * Returns a rules checker object that will be used for validating
     * application integrity.
     *
     * @param \Cake\ORM\RulesChecker $rules The rules object to be modified.
     * @return \Cake\ORM\RulesChecker
     */
    public function buildRules(RulesChecker $rules): RulesChecker
    {
        $rules->add($rules->isUnique(['username']));
        $rules->add($rules->isUnique(['email']));

        return $rules;
    }
}
