<?php
declare(strict_types=1);

namespace App\Controller;

use App\Controller\AppController;
use Cake\Event\EventInterface;
use Cake\Core\Configure;

/**
 * Users Controller
 *
 * @property \App\Model\Table\UsersTable $Users
 *
 * @method \App\Model\Entity\User[]|\Cake\Datasource\ResultSetInterface paginate($object = null, array $settings = [])
 */
class UsersController extends AppController
{
    /**
     * Index method
     *
     * @return \Cake\Http\Response|null
     */
    public function index()
    {
        $users = $this->paginate($this->Users);

        $this->set(compact('users'));
    }

    /**
     * View method
     *
     * @param string|null $id User id.
     * @return \Cake\Http\Response|null
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function view($id = null)
    {
        $user = $this->Users->get($id, [
            'contain' => ['Posts'],
        ]);

        $this->set('user', $user);
        $this->set('user', $user);
    }

    /**
     * Add method
     *
     * @return \Cake\Http\Response|null Redirects on successful add, renders view otherwise.
     */
    public function add()
    {
        $user = $this->Users->newEmptyEntity();
        if ($this->request->is('post')) {
            $user = $this->Users->patchEntity($user, $this->request->getData());

            if(isset($this->Users->error)){
                $this->Flash->error($this->Users->error);
                $this->redirect($this->referer());
            } else {

                if ($this->Users->save($user)) {
                    $this->Flash->success(__('The user has been saved.'));

                    return $this->redirect(['action' => 'index']);
                }
                $this->Flash->error(__('The user could not be saved. Please, try again.'));
            }
        }
        $this->set(compact('user'));
    }


    /**
     * New User method
     *
     * @return \Cake\Http\Response|null Redirects on successful add, renders view otherwise.
     */
    public function register()
    {
        $user = $this->Users->newEmptyEntity();
        if ($this->request->is('post')) {
            $user = $this->Users->patchEntity($user, $this->request->getData());
            
            if(isset($this->Users->error)){
                $this->Flash->error($this->Users->error);
                $this->redirect($this->referer());
            } else {

                if ($this->Users->save($user)) {
                    $this->Flash->success(__('The user has been saved.'));
    
                    return $this->redirect(['action' => 'index']);
                }
                $this->Flash->error(__('The user could not be saved. Please, try again.'));    
            }
            
            
        }
        $this->set(compact('user'));
    }

    /**
     * Edit method
     *
     * @param string|null $id User id.
     * @return \Cake\Http\Response|null Redirects on successful edit, renders view otherwise.
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function edit($id = null)
    {
        $user = $this->Users->get($id, [
            'contain' => [],
        ]);
        if ($this->request->is(['patch', 'post', 'put'])) {
            $user = $this->Users->patchEntity($user, $this->request->getData());
            if ($this->Users->save($user)) {
                $this->Flash->success(__('The user has been saved.'));

                return $this->redirect(['action' => 'index']);
            }
            $this->Flash->error(__('The user could not be saved. Please, try again.'));
        }
        $this->set(compact('user'));
    }

    /**
     * Delete method
     *
     * @param string|null $id User id.
     * @return \Cake\Http\Response|null Redirects to index.
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function delete($id = null)
    {
        $this->request->allowMethod(['post', 'delete']);

        $user = $this->Users->get($id);
        
        $policiesModel = $this->loadModel('policies');

        $matchedPolicies = $policiesModel->find('all', [
            'conditions' => ['user_id =' => $id  ]
        ]);
        $record = $matchedPolicies->first();
        if ($record != null) {
            $this->Flash->error(__('The user still has policies so can not be deleted'));
        } else {
            if ($this->Users->delete($user)) {
                $this->Flash->success(__('The user has been deleted.'));
            } else {
                $this->Flash->error(__('The user could not be deleted. Please, try again.'));
            }
        }
        
        return $this->redirect(['action' => 'index']);
    }

    //  Login
    public function login() {
        
        if (Configure::read('requireLogin')) {
            if($this->request->is('post')) {

                $user = $this->Auth->identify();
                if ($user){
                    $this->Auth->setUser($user);
                    return $this->redirect(['controller' => 'policies']);
                }
                // Bad Login
                $this->Flash->error('Incorrect Login');
            }
        }   
        else {
            $this->Flash->error('Login System Disabled');
        }
        
    }
   

    //  Logout
    public function logout() {
        if (REQUIRE_LOGIN) {
        $this->Flash->error('You are logged out');
        return $this->redirect($this->Auth->logout());
        } 
        else {
            $this->Flash->error('Login System has been disabled');
        }
    }

    //use Cake\Event\Event;
    public function beforeFilter(EventInterface  $event)
    {
        
        parent::beforeFilter($event);
        if (Configure::read('requireLogin')) {
            $this->Auth->allow(['register']);
        }
    }
}
