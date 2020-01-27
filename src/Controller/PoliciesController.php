<?php
declare(strict_types=1);

namespace App\Controller;

use App\Utility\TagExtractor\ManagedText;
use App\Utility\TagExtractor\PackedTag;
use Cake\I18n\Time;

//Pulling in TagExtractor
use App\Utility\TagExtractor\TagsCollection;
use Cake\Core\Configure;

/**
 * Policies Controller
 *
 * @property \App\Model\Table\PoliciesTable $Policies
 *
 * @method \App\Model\Entity\Policy[]|\Cake\Datasource\ResultSetInterface paginate($object = null, array $settings = [])
 */
class PoliciesController extends AppController
{
    /**
     * Index method
     *
     * @return \Cake\Http\Response|null
     */
    public function index()
    {
        $this->paginate = [
            'contain' => ['Users'],
        ];
        $policies = $this->paginate($this->Policies);

        $this->set(compact('policies'));
    }

    /**
     * View method
     *
     * @param string|null $id Policy id.
     * @return \Cake\Http\Response|null
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function view($id = null)
    {
        $policy = $this->Policies->get($id, [
            'contain' => ['Users', 'PolicyComponents'],
        ]);
        
        //buffer the policy into a render friendly version
        $renderFriendly = array();

        //new start
        $packedTags = new TagsCollection;

        //Get all the tags for the current ID policy - needs to be moved into a helper
        foreach($policy->policy_components as $policyComponent) {
            $packedTags->add(new PackedTag($policyComponent->value,$policyComponent->replacement));
        }

        $managedTitleText = new ManagedText($policy->title,$packedTags);
        $managedBodyText = new ManagedText($policy->body, $packedTags);
        
        $renderFriendly['title'] = $managedTitleText->getPresentationText();
        $renderFriendly['body'] = $managedBodyText->getPresentationText();

        $this->set('renderFriendly', $renderFriendly);
        $this->set('policy', $policy);
    }

    public function editPolicyComponents($id = null) {
        
        //pull in policy and components, but we will only allow the user to render the components on this screen
        $policy = $this->Policies->get($id, [
            'contain' => ['Users', 'PolicyComponents'],
        ]);
        
        if ($this->request->is(['patch', 'post', 'put'])) {
            $associated = ['PolicyComponents'];
            $policy = $this->Policies->patchEntity($policy, $this->request->getData(), [
                'associated' => $associated
            ]);
            if ($this->Policies->save($policy, ['associated' =>$associated])) {
                $this->Flash->success(__('The policy has been saved.'));

                return $this->redirect(['action' => 'index']);
            }
            $this->Flash->error(__('The policy could not be saved. Please, try again.'));
        } 
        $this->set('policy', $policy);
    }

    /**
     * Add method
     *
     * @return \Cake\Http\Response|null Redirects on successful add, renders view otherwise.
     */

    public function add()
    {
        $policy = $this->Policies->newEmptyEntity();            
        
        //do I need to pass options? 'contain' => ['Users', 'PolicyComponents'],

        //Is the request to post/patch the policy #note
        if ($this->request->is(['patch', 'post', 'put'])) {
            
            // #note Patch Entity only send the information/change needed to the server - question the merits of patch entity when the policy is new
            //$policy = $this->Policies->patchEntity($policy, $this->request->getData(), ['associated'=> ['PolicyComponents']]);
            // I'm not sure you need to pass associated to update - seems to work without it #not sure why?
            
            $policy = $this->Policies->patchEntity($policy, $this->request->getData());
            
            //
            //  hacking in extra component to see if it saves
            /*
            //So the entity will accept the policy being bolted on here - as it but not during patching process                
            $policyComponent  = $this->Policies->PolicyComponents->newEmptyEntity();
            $policyComponent->name = 'Un-configured - component';
            $policyComponent->description = 'Please describe what this component refers  to';
            $policyComponent->value = '#temp';
            $policy->policy_components = [$policyComponent];
            */

            if ($this->Policies->save($policy)) {
                $this->Flash->success(__('The policy has been saved.'));

                return $this->redirect(['action' => 'index']);
            }
            $this->Flash->error(__('The policy could not be saved. Please, try again.'));
        }

        $users = $this->Policies->Users->find('list', ['limit' => 200]);
        
        //Need to confirm - the find('list') method only returns name / id pears
        $policyComponents = $this->Policies->PolicyComponents->find('list',['limit' => 200]);

        $this->set(compact('policy', 'users', 'policyComponents'));
    }

    /**
     * Edit method
     *
     * @param string|null $id Policy id.
     * @return \Cake\Http\Response|null Redirects on successful edit, renders view otherwise.
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function edit($id = null)
    {
        $policy = $this->Policies->get($id, [
            'contain' => ['PolicyComponents'],
        ]);
        if ($this->request->is(['patch', 'post', 'put'])) {
            $policy = $this->Policies->patchEntity($policy, $this->request->getData());
            

           
            

            


            if ($this->Policies->save($policy)) {
                $this->Flash->success(__('The policy has been saved.'));

                return $this->redirect(['action' => 'index']);
            }
            $this->Flash->error(__('The policy could not be saved. Please, try again.'));
        }
        $users = $this->Policies->Users->find('list', ['limit' => 200]);
        
        //Need to confirm - the find('list') method only returns name / id pears
        $policyComponents = $this->Policies->PolicyComponents->find('list',['limit' => 200]);

        //Learning 
        if(Configure::read('learningOutput')) {
            $debugPolicyComponents = $this->Policies->PolicyComponents->find('all');
            //#note CakePHP quires are lazy and only process when called
            $this->set('debugPolicyComponents', $debugPolicyComponents);                                                
        }

        $this->set(compact('policy', 'users', 'policyComponents'));
    }

    public function approve($id = null)
    {
        $policy = $this->Policies->get($id, [
            'contain' => ['PolicyComponents'],
        ]);
        $todayDate = Time::Now();
        $policy->approved = $todayDate->format('Y-m-d');

        
        //Attempt the save
        if($this->Policies->save($policy)) {
            $this->Flash->success(__('The policy has been approved on '. $todayDate->format('Y-m-d')));
            return $this->redirect(['action' => 'index']);
        }
        $this->Flash->error(__('The policy could not be saved and approved'));

    }
    /**
     * Delete method
     *
     * @param string|null $id Policy id.
     * @return \Cake\Http\Response|null Redirects to index.
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function delete($id = null)
    {
        $this->request->allowMethod(['post', 'delete']);
        $policy = $this->Policies->get($id);
        if ($this->Policies->delete($policy)) {
            $this->Flash->success(__('The policy has been deleted.'));
        } else {
            $this->Flash->error(__('The policy could not be deleted. Please, try again.'));
        }

        return $this->redirect(['action' => 'index']);
    }
}
