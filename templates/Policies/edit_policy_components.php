
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
            <?= $this->Form->hidden('id')?>
            <fieldset>
                <legend><?= __('Edit Policy Components') ?></legend>
                <?php
                    $policyComponentCount = 0;
                    echo '<table>';
                    foreach($policy->policy_components as $policyComponent) {
                        
                        

                        echo $this->Form->hidden('policy_components.'.$policyComponentCount.'.id');
                        echo '<tr>';
                        echo '<th>'.h($policyComponent->value) .' is replaced by:</th>';
                        echo '</tr>';
                        echo '<tr>';
                        echo '<td>' .$this->Form->control('policy_components.'.$policyComponentCount.'.replacement', ['label' => false]) . '</td>';
                        echo '<td class=\'actions\'>';  
                        echo $this->Html->link(__('View'), ['controller' => 'PolicyComponents', 'action' => 'view', $policyComponent->id]);
                        echo $this->Html->link(__('Edit'), ['controller' => 'PolicyComponents', 'action' => 'edit', $policyComponent->id]);
                        echo $this->Form->postLink(__('Delete'), ['controller' => 'PolicyComponents', 'action' => 'delete', $policyComponent->id], ['confirm' => __('Are you sure you want to delete # {0}?', $policyComponent->id)]);
                        echo '<td/>';  
                        echo '</tr>';
                        $policyComponentCount++;
                    }
                    echo '</table>';
                ?>
            </fieldset>
            <?= $this->Form->button(__('Submit')) ?>
            <?= $this->Form->end() ?>
        </div>
    </div>
</div>`
