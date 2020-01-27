<?php
/**
 * @var \App\View\AppView $this
 * @var \App\Model\Entity\Policy[]|\Cake\Collection\CollectionInterface $policies
 */
?>
<div class="policies index content">
    <?= $this->Html->link(__('New Policy'), ['action' => 'add'], ['class' => 'button float-right']) ?>
    <h3><?= __('Policies') ?></h3>
    <div class="table-responsive">
        <table>
            <thead>
                <tr>
                    <th><?= $this->Paginator->sort('title') ?></th>
                    <th><?= $this->Paginator->sort('user_id') ?></th>
                    <th></th>
                    <th><?= $this->Paginator->sort('expiry') ?></th>
                    <th><?= $this->Paginator->sort('approved') ?></th>
                    <th class="actions"></th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($policies as $policy): ?>
                <tr>
                
                    <td><?= h($policy->title) ?></td>
                    <td><?= $policy->has('user') ? $this->Html->link($policy->user->name, ['controller' => 'Users', 'action' => 'view', $policy->user->id]) : '' ?></td>
                    <td><?= h($policy->version) ?></td>
                    <td><?= h($policy->expiry) ?></td>
                    
                    
                    <td><?= h($policy->approved) ?></td>
                    
                    <td class="actions">
                        <?= $this->Html->link(__('View'), ['action' => 'view', $policy->id]) ?>
                        <?= $this->Html->link(__('Edit'), ['action' => 'edit', $policy->id]) ?>
                        <?= $this->Html->link(__('Approve'),['action' =>'approve', $policy->id]) ?>
                        <?= $this->Form->postLink(__('Delete'), ['action' => 'delete', $policy->id], ['confirm' => __('Are you sure you want to delete # {0}?', $policy->id)]) ?>
                    </td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
    <div class="paginator">
        <ul class="pagination">
            <?= $this->Paginator->first('<< ' . __('first')) ?>
            <?= $this->Paginator->prev('< ' . __('previous')) ?>
            <?= $this->Paginator->numbers() ?>
            <?= $this->Paginator->next(__('next') . ' >') ?>
            <?= $this->Paginator->last(__('last') . ' >>') ?>
        </ul>
        <p><?= $this->Paginator->counter(__('Page {{page}} of {{pages}}, showing {{current}} record(s) out of {{count}} total')) ?></p>
    </div>
</div>
