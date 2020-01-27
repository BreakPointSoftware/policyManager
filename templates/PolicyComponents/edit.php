<?php
/**
 * @var \App\View\AppView $this
 * @var \App\Model\Entity\PolicyComponent $policyComponent
 */
?>
<div class="row">
    <aside class="column">
        <div class="side-nav">
            <h4 class="heading"><?= __('Actions') ?></h4>
            <?= $this->Html->link(__('List Component'), ['action' => 'index'], ['class' => 'side-nav-item']) ?>
        </div>
    </aside>
    <div class="column-responsive column-80">
        <div class="policy_component form content">
            <?= $this->Form->create($policyComponent) ?>
            <fieldset>
                <legend><?= __('Edit Component') ?></legend>
                <?php
                    echo $this->Form->control('name');
                    echo $this->Form->control('description');
                    echo $this->Form->control('value');
                    echo $this->Form->control('replacement');
                ?>
            </fieldset>
            <?= $this->Form->button(__('Submit')) ?>
            <?= $this->Form->end() ?>
        </div>
    </div>   
</div>