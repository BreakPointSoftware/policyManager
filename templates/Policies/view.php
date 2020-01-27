<?php
/**
 * @var \App\View\AppView $this
 * @var \App\Model\Entity\Policy $policy
 */
?>
<div class="row">
    <aside class="column">
        <div class="side-nav">
            <h4 class="heading"><?= __('Actions') ?></h4>
            <?= $this->Html->link(__('Edit Policy'), ['action' => 'edit', $policy->id], ['class' => 'side-nav-item']) ?>
            <?= $this->Html->link(__('Edit Components'), ['action' => 'editPolicyComponents', $policy->id]) ?>
            <?= $this->Form->postLink(__('Delete Policy'), ['action' => 'delete', $policy->id], ['confirm' => __('Are you sure you want to delete # {0}?', $policy->id), 'class' => 'side-nav-item']) ?>
            <?= $this->Html->link(__('List Policies'), ['action' => 'index'], ['class' => 'side-nav-item']) ?>
            <?= $this->Html->link(__('New Policy'), ['action' => 'add'], ['class' => 'side-nav-item']) ?>
        </div>
    </aside>
    <div class="column-responsive column-80">
        <div class="policies view content">
            <h3><?= h($renderFriendly['title']) ?></h3>
            <div class="text">
                <blockquote>
                    <?= $this->Text->autoParagraph(h($renderFriendly['body'])); ?>
                </blockquote>
            </div>
            <table>                
                <tr>
                    <th><?= __('Version') ?></th>
                    <td><?= h($policy->version) ?></td>
                </tr>
                <tr>
                    <th><?= __('Created') ?></th>
                    <td><?= h($policy->created) ?></td>
                </tr>
                <tr>
                    <th><?= __('Modified') ?></th>
                    <td><?= h($policy->modified) ?></td>
                </tr>
                <tr>
                    <th><?= __('Approved') ?></th>
                    <td><?= h($policy->approved) ?></td>
                </tr>
                <tr>
                    <th><?= __('Expiry') ?></th>
                    <td><?= h($policy->expiry) ?></td>
                </tr>
            </table>
        </div>
    </div>
</div>