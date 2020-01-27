<?php
namespace App\Model\Behavior;

use Cake\ORM\Behavior;
use Cake\ORM\TableRegistry;

class PolicyComponentsBehavior extends Behavior {
    
    public function initialize(array $config) : void
    {
       
    }
    public function getPolicyComponentByTag(string $aTag)
    {
        
        $policyComponents = TableRegistry::getTableLocator()->get('policyComponents');

    //check if we have stored this component before #
    $matchedComponent = $policyComponents->find('all', [
        'conditions' => ['value =' => $aTag]
    ]);
    $record = $matchedComponent->first();

    return $record;
    }
}
?>