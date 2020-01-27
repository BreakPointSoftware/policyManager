<?php
declare(strict_types=1);

namespace App\Controller;


/**
 * Vars Controller
 *
 * @property \App\Model\Table\VarsTable $Vars
 *
 * @method \App\Model\Entity\Var[]|\Cake\Datasource\ResultSetInterface paginate($object = null, array $settings = [])
 */
class PolicyComponentsController extends AppController
{
    /**
     * Index method
     *
     * @return \Cake\Http\Response|null
     */
    public function index()
    {
        $policyComponents = $this->paginate($this->PolicyComponents);

        $this->set(compact('policyComponents'));
    }

    /**
     * View method
     *
     * @param string|null $id Var id.
     * @return \Cake\Http\Response|null
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function view($id = null)
    {
        $policyComponent = $this->PolicyComponents->get($id, [
            'contain' => ['Policies'],
        ]);

        
        //$policyComponent = $this->PolicyComponents->get($id);

        $this->set('policyComponent', $policyComponent);
        
    }

    /**
     * Add method
     *
     * @return \Cake\Http\Response|null Redirects on successful add, renders view otherwise.
     */
    public function add()
    {
        $policyComponent = $this->PolicyComponents->newEmptyEntity();
        if ($this->request->is('post')) {
            $policyComponent = $this->PolicyComponents->patchEntity($policyComponent, $this->request->getData());
            if ($this->PolicyComponents->save($policyComponent)) {
                $this->Flash->success(__('The var has been saved.'));

                return $this->redirect(['action' => 'index']);
            }
            $this->Flash->error(__('The var could not be saved. Please, try again.'));
        }
        $this->set(compact('policyComponent'));
    }

    /**
     * Edit method
     *
     * @param string|null $id Var id.
     * @return \Cake\Http\Response|null Redirects on successful edit, renders view otherwise.
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function edit($id = null)
    {
        $policyComponent = $this->PolicyComponents->get($id, [
            'contain' => [],
        ]);
        if ($this->request->is(['patch', 'post', 'put'])) {
            $policyComponent = $this->PolicyComponents->patchEntity($policyComponent, $this->request->getData());
            if ($this->PolicyComponents->save($policyComponent)) {
                $this->Flash->success(__('The var has been saved.'));

                return $this->redirect(['action' => 'index']);
            }
            $this->Flash->error(__('The var could not be saved. Please, try again.'));
        }
        $this->set(compact('policyComponent'));
    }

    /**
     * Delete method
     *
     * @param string|null $id Var id.
     * @return \Cake\Http\Response|null Redirects to index.
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function delete($id = null)
    {
        $this->request->allowMethod(['post', 'delete']);
        $policyComponents = $this->PolicyComponents->get($id);
        if ($this->PolicyComponents->delete($policyComponents)) {
            $this->Flash->success(__('The var has been deleted.'));
        } else {
            $this->Flash->error(__('The var could not be deleted. Please, try again.'));
        }

        return $this->redirect(['action' => 'index']);
    }
}
