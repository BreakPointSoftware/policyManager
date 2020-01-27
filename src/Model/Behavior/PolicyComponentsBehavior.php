<?php
namespace App\Model\Behavior;

use Cake\ORM\Behavior;
use Cake\ORM\TableRegistry;

//Use our packed Tags Helper
use App\Utility\TagExtractor\ManagedText\PackedTag;
use App\Utility\TagExtractor\PackedTag as TagExtractorPackedTag;
use App\Utility\TagExtractor\TagsCollection;

class PolicyComponentsBehavior extends Behavior {
    
    protected $policyComponents;
    protected $policies;

    public function initialize(array $config) : void
    {
        
    }

    

    
    public function isPolicyComponentPresentByTag(string $aTag) {
        //Can not init Tables in initialize, because it will trigger a call on itself 
        $this->initBehaviourTables();

        $conditions = ['conditions' => ['value =' => $aTag]];

        return (bool)$this->policyComponents->find('all', $conditions)->limit(1)->count();;
    }

    public function isPolicyComponentPresentById(int $aId) {
        //Can not init Tables in initialize, because it will trigger a call on itself 
        $this->initBehaviourTables();

        $conditions = ['conditions' => ['id =' => $aId]];

        return (bool)$this->policyComponents->find('all', $conditions)->limit(1)->count();;
    }

    public function getPolicyComponentByTag(string $aTag)
    {
        //Can not init Tables in initialize, because it will trigger a call on itself 
        $this->initBehaviourTables();

        $conditions = ['conditions' => ['value =' => $aTag]];

        //Check if current policy is present
        $matchedComponent = $this->policyComponents->find('all', $conditions);
        $record = $matchedComponent->first();

        return $record;
    }

    public function getPolicyComponentIdByTag (string $aTag) 
    {
        //Can not init Tables in initialize, because it will trigger a call on itself 
        $this->initBehaviourTables();

        $conditions = ['conditions' => ['value =' => $aTag]];

        if($this->isPolicyComponentPresentByTag($aTag) > 0) {
            //Check if current policy is present
            $matchedComponent = $this->policyComponents->find('all', $conditions)->select(['id']);
            $record = $matchedComponent->first();
            return $record->id;
        }
    }

    //Note we can look into defining Finders to wrap up and consolidate this logic
    public function getPolicyComponentByID (int $aId) {
        //Can not init Tables in initialize, because it will trigger a call on itself 
        $this->initBehaviourTables();

        $conditions = ['conditions' => ['id =' => $aId]];

        if($this->isPolicyComponentPresentById($aId) > 0) {
            //Check if current policy is present
            $matchedComponent = $this->policyComponents->find('all', $conditions);
            $record = $matchedComponent->first();
            return $record;
        }
    }

// 
    public function getAllPolicyComponents () :TagsCollection {
        //Can not init Tables in initialize, because it will trigger a call on itself 
        $this->initBehaviourTables();

        $matchedComponents = $this->policyComponents->find('all');
        
        //Create a new TagCollection to store our components in, as that that what we care about
        $policyComponentsCollection = new TagsCollection();

        foreach ($matchedComponents as $record) {
            $tagToStore = new TagExtractorPackedTag($record->value,$record->replacement);
            $policyComponentsCollection->add($tagToStore);
        }
        
        return $policyComponentsCollection;
    }


    public function  getAllPolicyComponentsForPolicy(int $aPolicyId) {
        //Can not init Tables in initialize, because it will trigger a call on itself 
        $this->initBehaviourTables();

        $conditions = ['contain' => ['PolicyComponents']];
        $policy = $this->policies->get($aPolicyId, $conditions);

        $packedTags = new TagsCollection;
        
        foreach($policy->policy_components as $policyComponent) {
            $packedTags->add(new TagExtractorPackedTag($policyComponent->value,$policyComponent->replacement));
        }
        return $packedTags;
    }

    //policy_components


    public function createPolicyComponentEnityAndExtractData(TagExtractorPackedTag $aPolicyComponentTag) {

        //Can not init Tables in initialize, because it will trigger a call on itself 
        $this->initBehaviourTables();
        
        $policyComponent  = $this->policyComponents->newEmptyEntity();
        $policyComponent->name = __('Unnamed - ') . $aPolicyComponentTag->getTag();
        $policyComponent->description = __('Please describe what this component refers to');
        
        // Now defaulting the policy component to the Tag name so it makes the un-edited 
        // policies cleaner to read and clearer that they need editing

        $policyComponent->replacement = $aPolicyComponentTag->getTag(); 
        $policyComponent->value = $aPolicyComponentTag->getTag();
        
        // Prepare the data to return
        $extractedComponentData = $policyComponent->extract($this->policyComponents->getSchema()->columns(), true);
    
        return $extractedComponentData;
    }

    protected function initBehaviourTables() {
        $this->policyComponents = ($this->policyComponents ==null) ? TableRegistry::getTableLocator()->get('policyComponents') : $this->policyComponents;
        $this->policies = ($this->policies ==null) ? TableRegistry::getTableLocator()->get('policies') : $this->policies;
    }
}
?>