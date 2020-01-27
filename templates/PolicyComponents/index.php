<?php
/**
 * @var \App\View\AppView $this
 * @var \App\Model\Entity\PolicyComponent[]|\Cake\Collection\CollectionInterface $policyComponents
 */
?>
<div class="policy_components index content">
    
    <h3><?= __('Policy Components View') ?></h3>
    <?= $this->Html->link(__('New Component'), ['action' => 'add'], ['class' => 'button float-right']) ?>    
    
    <div class="table-responsive">
        <table>
            <thead>
                <tr>
                    
                    <th><?= $this->Paginator->sort('name') ?></th>
                    <th><?= $this->Paginator->sort('description') ?></th>
                    <th><?= $this->Paginator->sort('value') ?></th>
                    
                    <th class="actions"><?= __('Actions') ?></th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($policyComponents as $policyComponent): ?>
                <tr>
                    
                    <td><?= h($policyComponent->name) ?></td>
                    <td><?= h($policyComponent->description) ?></td>
                    <td><?= h($policyComponent->value) ?></td>
                    
                    
                    <td class="actions">
                        <?= $this->Html->link(__('View'), ['action' => 'view', $policyComponent->id]) ?>
                        <?= $this->Html->link(__('Edit'), ['action' => 'edit', $policyComponent->id]) ?>
                        <?= $this->Form->postLink(__('Delete'), ['action' => 'delete', $policyComponent->id], ['confirm' => __('Are you sure you want to delete # {0}?', $policyComponent->id)]) ?>
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



    

