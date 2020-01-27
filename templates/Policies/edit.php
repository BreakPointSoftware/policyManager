<?php use Cake\Core\Configure; ?>

<div class="row">
    <!-- Consider refactoring into an element / compent given time #Greg #Return-->
    <aside class="column">
        <div class="side-nav">
            <h4 class="heading"><?= __('Actions') ?></h4>
            <?= $this->Form->postLink(
                __('Delete'),
                ['action' => 'delete', $policy->id],
                ['confirm' => __('Are you sure you want to delete # {0}?', $policy->id), 'class' => 'side-nav-item']
            ) ?>
            <?= $this->Html->link(__('List Policies'), ['action' => 'index'], ['class' => 'side-nav-item']) ?>
        </div>
    </aside>

    <div class="column-responsive column-80">
        <div class="policies form content">
            <?= $this->Form->create($policy) ?>
            <fieldset>
                <legend><?= __('Edit Policy') ?></legend>
                <?php
                    //-- The user ID will need to not be editable and work with the signed in user
                    echo $this->Form->control('user_id', ['options' => $users, 'empty' => true]);

                    // Need to setup Many to Many relationship with editors, which are actually users
                    //echo $this->Form->control('editor_id');
                    
                    echo $this->Form->control('title');
                    echo $this->Form->textarea('body',['rows' => '100']);

                    //Need Minor / Major version wrapping 
                    echo $this->Form->control('version');

                    //approved should not be editable - but a function of the policy owner
                    //echo $this->Form->control('approved', ['empty' => true]);


                    echo $this->Form->control('expiry', ['empty' => true]);
                    
                    // - we need to offline components -- Leaving as select box for the moment for proof of concept - but these will ultiamtely be removed                    
                    //echo $this->Form->select('policy_components._ids', $policyComponents,[ 'empty' => true, 'multiple' => 'true', 'style' => 'height:125px;']);
                    
                    if(Configure::read('learningOutput')) {
                        echo '<legend>' . __('These are Tests for the various HTML helpers') . '</legend>';
                        $options = ['M' => 'Male', 'F' => 'Female'];
                        echo $this->Form->select('gender', $options, ['empty' => true]);

                        echo $this->Form->checkbox(
                            'field',
                            [1, 2, 3, 4, 5],
                            ['empty' => '(choose one)']
                        );

                        echo $this->Form->select(
                            'field',
                            [1, 2, 3, 4, 5],
                            ['empty' => '(choose one)']
                        );

                        echo $this->Form->radio(
                            'field',
                            [1, 2, 3, 4, 5],
                            ['empty' => '(choose one)']
                        );
                    }
                ?>
            </fieldset>
            <?= $this->Form->button(__('Submit')) ?>
            <?= $this->Form->end() ?>
        </div>
    </div>
</div>

<?php  if(Configure::read('learningOutput')) { ?>
<div class="policies index content">
<h3><?= __('Data for debug') ?></h3>
    <div>
        <?php        
            print_r($debugPolicyComponents);
            echo ("<BR><BR>");

            echo ("For each to execute query");
            echo ("<BR><BR>");
            foreach ($debugPolicyComponents as $row)
            {
                echo ("<BR><BR>");
                print_r($row);
                echo ("<BR><BR>");
                echo ("<BR><BR>");
                echo ($row->name);
                echo ("<BR><BR>");
                echo ($row->description);
            }
            $data = $debugPolicyComponents->all();
            $data1 = $data->toList();
            $data2 = $debugPolicyComponents->toArray();

            echo ("<BR><BR>");
            echo ("Results set from ->all()");
            echo ("<BR><BR>");
            print_r($data);
            echo ("<BR><BR>");
            echo ("Results set from ->toList()");
            echo ("<BR><BR>");
            print_r($data1);
            echo ("<BR><BR>");
            echo ("Results set from ->toArray");
            echo ("<BR><BR>");
            print_r($data2);
            echo ("<BR><BR>");
            echo ("<BR><BR>");
            echo ("<BR><BR>");
            
            if( $data1 == $data2) {
                echo ('$data1 and $data2 are equal');
                echo ('<BR><BR>');
            }
            else {
                echo ('$data1 and $data2 look the same but are not equal');
                echo ('<BR><BR>');
            }

            echo ('<BR><BR>');
            echo ('<BR><BR>');
            
            if( $data1 === $data2) {
                echo ('$data1 and $data2 are exact');
                echo ('<BR><BR>');
            }
            else {
                echo ('$data1 and $data2 look the same but are not equal');
                echo ('<BR><BR>');
            }



        } 
        ?>
  
    </div>
</div>