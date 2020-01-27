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
        
        //#return
        $this->addBehavior('PolicyComponents'); //no config passed

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

    // #note  #wrong The Before Marshall event is triggered before data is saved to the database  
    // it actually trigged as part of Merging / Creating Entities

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
        
        //#note #wrong I think beforeMarshal is called only on data which is patched from the entity

        // Tags are being used as general term for anything prefixed, followed by a string without spaces
        // PolicyComponents is the notion that a policy has areas which can be set by variables - defined by dynamic tagging
        $extractedPolicyComponents  = new TagsCollection();

       
        //Passing by reference so we can edit the item
        foreach($data as $key => &$item) {
            
            //writing Logic to flatten _ids to actual fields
            //Check to see if the item is an array of keys
            $keysShortHandIdentifier = '_ids';
            $newlyFormattedAssociateData = Array();

            // Move to helper method
            if(is_array($item)){
                foreach($item as $associatedRecordKey => &$associatedRecordItem) { 
                    // compare to the variable, so we can plug in changes if this formating of the array changes later
                    if(is_array($associatedRecordItem)) {
                        if ($associatedRecordKey === $keysShortHandIdentifier) {
                            //we have found a shorthand Array, we need to do something with this
                            foreach($associatedRecordItem as $shortHandKey) {
                                // #note Move the keys into new array structure
                                // old structure was table->_ids->[1,2,3,4]  // which where the ids to update
                                //
                                // the new structure is
                                // table->element->id->value
                                // this means on update we can pass a mixture of associations and new data to update
                                $newlyFormattedAssociateData[] = array('id' => $shortHandKey);
                            }
                            $item = $newlyFormattedAssociateData;
                        }
                    }
                    else {
                        // If there are no associated items, then we need to turn this into an array so we can add items later
                        // #Passing an empty array I think is mostly harmless - but we need to test
                        if ($associatedRecordKey == $keysShortHandIdentifier) {
                            $item = array(); 
                        }
                    }
                }
            }
            

            //Lets process the string with Regex and pull out components
            if(!is_array($item)) {
                if (is_string($item)) {
                    
                    
                    $tempManagedText= new ManagedText($item);
                    $tempManagedText->setPrefix(new TagPrefix('#',true));
                    $extractedPolicyComponents->merge($tempManagedText->getAllTags());
                    
                }
            }
        }

        // #wrong #note - the data object is the same data that is stored inside the entity
        // The $data object is not the same as the entity, so I can either hook generating the data (ideal)
        // or build the data by hand #note that this would mean the data wouldn't necessarily go through he same verification flow
        // which would generate a risk

        //This will need to be refactoed into it own helper - here for testing
        // Note wrapping the boilerplate record creation in __('') so it can be localised
        
        
        foreach ($extractedPolicyComponents as $extractedPolicyComponent) {
            
            //test
            $recodfrombehaviour = $this->getPolicyComponentByTag($extractedPolicyComponent->getTag());
            var_dump($recodfrombehaviour);
            die();

            //check if we have stored this component before #
            $matchedComponent = $this->PolicyComponents->find('all', [
                'conditions' => ['value =' => $extractedPolicyComponent->getTag()]
            ]);
            $record = $matchedComponent->first();
            

            // Testing the hasAny to see if that faster way to check if record is present
            // - Need to #return #note need to reseach if CakePHP escapes values here. 

            //Check if the component is new, if so store it, otherwise ignore
            //depreciated in 2.1!  Removing
            //$conditions = ['value' => $extractedPolicyComponent];
            //$this->PolicyComponents->hasAny($conditions))

            if ($record == null){
            
                $policyComponent  = $this->PolicyComponents->newEmptyEntity();
                $policyComponent->name = __('Unnamed - ') . $extractedPolicyComponent->getTag();
                $policyComponent->description = __('Please describe what this component refers to');
                
                // Now defaulting the policy component to the Tag name so it makes the un-edited 
                // policies cleaner to read and clearer that they need editing

                $policyComponent->replacement = $extractedPolicyComponent->getTag(); 
                $policyComponent->value = $extractedPolicyComponent->getTag();
                
                // Prepare the data to add to the current entity
                $extractedComponentData = $policyComponent->extract($this->PolicyComponents->getSchema()->columns(), true);
                
                //Append a new policy component to the array
                $data['policy_components'][] =  $extractedComponentData;
            } else {
                // The extracted Tag has all ready beed saved, so we only need to
                // establish the relationship 
                $data['policy_components'][] =  array('id' => $record->id);
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
