
<div class="row">
    <!-- Consider refactoring into an element / compent given time #Greg #Return-->
    <aside class="column">
        <div class="side-nav">
            <h4 class="heading"><?= __('Actions') ?></h4>
            <?= $this->Form->postLink(
                //Needs to be contextual #return
                __('Delete'),
                ['action' => 'delete', $policy->id],
                ['confirm' => __('Are you sure you want to delete # {0}?', $policy->id), 'class' => 'side-nav-item']
            ) ?>
            <?= $this->Html->link(__('List Policies'), ['action' => 'index'], ['class' => 'side-nav-item']) ?>
        </div>
    </aside>

    <div class="column-responsive column-80">
        <div class="policies form content">
            <?= $this->Form->create() ?>
            <fieldset>
                <legend><?= __('Add Policy') ?></legend>
                <?php
                    //-- The user ID will need to not be editable and work with the signed in user
                    echo $this->Form->control('user_id', ['options' => $users, 'empty' => true]);

                    //
                    // Need to setup Many to Many relationship with editors, which are actually users
                    //echo $this->Form->control('editor_id');-->
                    
                    echo $this->Form->control('title');
                    echo $this->Form->textarea('body',['rows' => '100']);

                    //Need Minor / Major version wrapping 
                    echo $this->Form->control('version');

                    //approved should not be editable - but a function of the policy owner
                    //echo $this->Form->control('approved', ['empty' => true]);

                    echo $this->Form->control('expiry', ['empty' => true, 'type' => 'date']);
                    
                    //#note  we need to offline components -- Leaving as select box for the moment for proof of concept - but these will ultimately be removed                    
                    //echo $this->Form->select('policy_components._ids', $policyComponents,[ 'empty' => true, 'multiple' => 'true', 'style' => 'height:125px;']);
                    
                    // Testing to see if many to many saving would work directly from form #
                    //echo $this->Form->control('policy_components.0.name');
                    //echo $this->Form->control('policy_components.0.description');
                    //echo $this->Form->control('policy_components.0.value');
                    //echo $this->Form->control('policy_components.1.name');
                    //echo $this->Form->control('policy_components.1.description');
                    //echo $this->Form->control('policy_components.1.value');
                ?>
            </fieldset>
            <?= $this->Form->button(__('Submit')) ?>
            <?= $this->Form->end() ?>
        </div>
    </div>
</div>

