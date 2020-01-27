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
 * Vars Model
 *
 * @property \App\Model\Table\PolicyVarsTable&\Cake\ORM\Association\HasMany $PolicyVars
 *
 * @method \App\Model\Entity\Var get($primaryKey, $options = [])
 * @method \App\Model\Entity\Var newEntity($data = null, array $options = [])
 * @method \App\Model\Entity\Var[] newEntities(array $data, array $options = [])
 * @method \App\Model\Entity\Var|false save(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\Var saveOrFail(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\Var patchEntity(\Cake\Datasource\EntityInterface $entity, array $data, array $options = [])
 * @method \App\Model\Entity\Var[] patchEntities($entities, array $data, array $options = [])
 * @method \App\Model\Entity\Var findOrCreate($search, callable $callback = null, $options = [])
 *
 * @mixin \Cake\ORM\Behavior\TimestampBehavior
 */
class PolicyComponentsTable extends Table
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

        $this->setTable('policy_components');
        $this->setDisplayField('name');
        $this->setPrimaryKey('id');

        $this->addBehavior('Timestamp');

        $this->belongsToMany('Policies', [
            'foreignKey' => 'policy_component_id',
            'targetForeignKey' => 'policy_id',
            'joinTable' => 'policy_components_policies'
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
            ->allowEmptyString('id', null, 'create')
            ->requirePresence([
                'id' => [
                    'mode' => 'update',
                    'message' => __('Primary key is required for update.'),
                ]]);

        $validator
            ->scalar('name')
            ->notEmptyString('name', 'Please fill this field')
            ->maxLength('name', 255)
            ->minLength('name',2);

        $validator
            ->scalar('description')
            ->notEmptyString('description', 'Please fill this field')
            ->maxLength('description', 255)
            ->minLength('description',2);

        $validator
            ->scalar('value')
            ->notEmptyString('value', 'Please fill this field')
            ->maxLength('value', 255)
            ->minLength('value',2);

        $validator
            ->scalar('replacement')
            ->maxLength('replacement', 255)
            ->notEmptyString('replacement', 'Please fill this field')
            ->minLength('replacement',2);
            

        return $validator;
    }

    public function beforeMarshal(EventInterface $event, ArrayObject $data, ArrayObject $options)
    {
        //When saving or updating allways fore the tags to upper case
        if(isset($data->value)) {
            $data->value = strtoupper($data->value);
        }
    }
}
