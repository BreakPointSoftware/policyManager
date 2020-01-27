<?php
declare(strict_types=1);

namespace App\Model\Table;

use App\Model\Entity\PolicyComponent;
use App\Utility\TagExtractor\ManagedText;
use Cake\ORM\Query;
use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\Validation\Validator;

//Pulling in custom helper static class #will make it none static next
use App\Utility\TagExtractor\TagExtractor;
use App\Utility\TagExtractor\TagPrefix;
use App\Utility\TagExtractor\TagsCollection;
// Adding these so we can hook the Before Marshal Event,  That allows us to process data before it hits the database #note
use Cake\Event\EventInterface;
use ArrayObject;

/**
 * Policies Model
 *
 * @property \App\Model\Table\UsersTable&\Cake\ORM\Association\BelongsTo $Users
 * @property \App\Model\Table\EditorPoliciesTable&\Cake\ORM\Association\HasMany $EditorPolicies
 * @property \App\Model\Table\PolicyVarsTable&\Cake\ORM\Association\HasMany $PolicyVars
 *
 * @method \App\Model\Entity\Policy get($primaryKey, $options = [])
 * @method \App\Model\Entity\Policy newEntity($data = null, array $options = [])
 * @method \App\Model\Entity\Policy[] newEntities(array $data, array $options = [])
 * @method \App\Model\Entity\Policy|false save(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\Policy saveOrFail(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\Policy patchEntity(\Cake\Datasource\EntityInterface $entity, array $data, array $options = [])
 * @method \App\Model\Entity\Policy[] patchEntities($entities, array $data, array $options = [])
 * @method \App\Model\Entity\Policy findOrCreate($search, callable $callback = null, $options = [])
 *
 * @mixin \Cake\ORM\Behavior\TimestampBehavior
 */
class PoliciesTable extends Table
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
                         
        $this->setTable('policies');
        $this->setDisplayField('title');
        $this->setPrimaryKey('id');

        $this->addBehavior('Timestamp');
        
        //Our Custom Behaviours for this table
        $this->addBehavior('PolicyComponents'); 
        $this->addBehavior('DataRePacker'); 

        $this->belongsTo('Users', [
            'foreignKey' => 'user_id',
        ]);

        /*$this->hasMany('EditorsPolicies', [
            'foreignKey' => 'policy_id',
        ]);*/

        $this->belongsToMany('PolicyComponents', [
            'foreignKey' => 'policy_id',
            'targetForeignKey' => 'policy_component_id',
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
            ->scalar('title')
            ->notEmptyString('title', 'Please fill this field')
            ->maxLength('title', 255)
            ->minLength('title',4);
            

        $validator
            ->scalar('body')
            ->notEmptyString('body', 'Please fill this field')
            ->minLength('title',4);
            

        $validator
            ->scalar('version')
            ->maxLength('version', 10)
            ->allowEmptyString('version');
            


        // #note updating these to allow date rather than date time
        $validator
            ->date('approved')
            ->allowEmptyDateTime('approved');

        $validator
            ->date('expiry')
            ->allowEmptyDateTime('expiry');

        return $validator;
    }

    
    public function beforeMarshal(EventInterface $event, ArrayObject $data, ArrayObject $options)
    {
        // #updated #understanding
        // beforeMarshal event is trigged as part of Marshall merge, and Marshall one -  as part of
        // $this->_prepareDataAndOptions($data, $options);
        //
        // Marshall merge is used in things like newEntity, PatchEntity etc, and part of the process to 
        // run any data alterations in this function before its handled over the to validator
        //
        //  The following Entity Methods Trigger 
        //    newEntity(), newEntities(), patchEntity() or patchEntities():
        // The ArrayObject $data object is not the same as the entity, 
        // Tags are being used as general term for anything prefixed, followed by a string without spaces
        // PolicyComponents is the notion that a policy has areas which can be set by variables - defined by dynamic tagging
        
        $extractedPolicyComponents  = new TagsCollection();

        //Passing by reference so we can edit the item
        foreach($data as $key => &$itemByRef) {
            
            //Repack any ForeignKeys Contained in the Item
            $this->rePackForeignKeys($itemByRef);

            //Lets process the string with Regex and pull out components
            if(!is_array($itemByRef)) {
                if (is_string($itemByRef)) {
                    
                    $tempManagedText= new ManagedText($itemByRef);
                    $tempManagedText->setPrefix(new TagPrefix('#',true));
                    $extractedPolicyComponents->merge($tempManagedText->getAllTags());
                    
                }
            }
        }

        
                
        // ready for refactor
        foreach ($extractedPolicyComponents as $extractedPolicyComponent) {
            
            //see if we have a record for this Policy Component
            $recordId = $this->getPolicyComponentIdByTag($extractedPolicyComponent->getTag());
            

            if ($recordId == null){
                
                $extractedComponentData = $this->createPolicyComponentEnityAndExtractData($extractedPolicyComponent);
           
                //Append a new policy component to the array
                $data['policy_components'][] =  $extractedComponentData;
            } else {
                // The extracted Tag has all ready beed saved, so we only need to
                // establish the relationship 
                $data['policy_components'][] = array('id' => $recordId);
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
        $rules->add($rules->existsIn(['user_id'], 'Users'));
        

        return $rules;
    }
}
