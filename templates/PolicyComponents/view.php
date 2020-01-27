<?php
/**
 * @var \App\View\AppView $this
 * @var \App\Model\Entity\PolicyComponent $PolicyComponent
 */
?>
<div class="row">
    <aside class="column">
        <div class="side-nav">
            <h4 class="heading"><?= __('Actions') ?></h4>
            <?= $this->Html->link(__('Edit Component'), ['action' => 'edit', $policyComponent->id], ['class' => 'side-nav-item']) ?>
            <?= $this->Form->postLink(__('Delete Component'), ['action' => 'delete', $policyComponent->id], ['confirm' => __('Are you sure you want to delete # {0}?', $policyComponent->id), 'class' => 'side-nav-item']) ?>
            <?= $this->Html->link(__('List Component'), ['action' => 'index'], ['class' => 'side-nav-item']) ?>
            <?= $this->Html->link(__('New Component'), ['action' => 'add'], ['class' => 'side-nav-item']) ?>
        </div>
    </aside>
    <div class="column-responsive column-80">
        <div class="policy_component view content">
            <h3><?= h($policyComponent->name) ?></h3>
            <table>
                <tr>
                    <th><?= __('Description') ?></th>
                    <td><?= h($policyComponent->description) ?></td>
                </tr>
                <tr>
                    <th><?= __('Value') ?></th>
                    <td><?= h($policyComponent->value) ?></td>
                </tr>
                <tr>
                    <th><?= __('Value') ?></th>
                    <td><?= h($policyComponent->replacement) ?></td>
                </tr>
                <tr>
                    <th><?= __('Created') ?></th>
                    <td><?= h($policyComponent->created) ?></td>
                </tr>
                <tr>
                    <th><?= __('Modified') ?></th>
                    <td><?= h($policyComponent->modified) ?></td>
                </tr>
            </table>
            <div class="related">
                <h4><?= __('Used in Policies') ?></h4>
                <?php if (!empty($policyComponent->policies)) : ?>
                <div class="table-responsive">
                    <table>
                        <tr>
                            <th><?= __('Name') ?></th>
                            <th class="actions"><?= __('Actions') ?></th>
                        </tr>
                        <?php foreach ($policyComponent->policies as $policyUsed) : ?>
                        <tr>
                            <td><?= h($policyUsed->title) ?></td>
                            <td class="actions">
                                <?= $this->Html->link(__('View'), ['controller' => 'Policies', 'action' => 'view', $policyUsed->id]) ?>
                                <?= $this->Html->link(__('Edit'), ['controller' => 'Policies', 'action' => 'edit', $policyUsed->id]) ?>
                                <?= $this->Form->postLink(__('Delete'), ['controller' => 'Policies', 'action' => 'delete', $policyUsed->id], ['confirm' => __('Are you sure you want to delete # {0}?', $policyUsed->id)]) ?>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                    </table>
                </div>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>
