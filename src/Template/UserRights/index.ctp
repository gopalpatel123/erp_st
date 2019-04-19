<?php
/**
  * @var \App\View\AppView $this
  * @var \App\Model\Entity\UserRight[]|\Cake\Collection\CollectionInterface $userRights
  */
?>
<nav class="large-3 medium-4 columns" id="actions-sidebar">
    <ul class="side-nav">
        <li class="heading"><?= __('Actions') ?></li>
        <li><?= $this->Html->link(__('New User Right'), ['action' => 'add']) ?></li>
        <li><?= $this->Html->link(__('List Pages'), ['controller' => 'Pages', 'action' => 'index']) ?></li>
        <li><?= $this->Html->link(__('New Page'), ['controller' => 'Pages', 'action' => 'add']) ?></li>
        <li><?= $this->Html->link(__('List Users'), ['controller' => 'Users', 'action' => 'index']) ?></li>
        <li><?= $this->Html->link(__('New User'), ['controller' => 'Users', 'action' => 'add']) ?></li>
    </ul>
</nav>
<div class="userRights index large-9 medium-8 columns content">
    <h3><?= __('User Rights') ?></h3>
    <table cellpadding="0" cellspacing="0">
        <thead>
            <tr>
                <th scope="col"><?= $this->Paginator->sort('id') ?></th>
                <th scope="col"><?= $this->Paginator->sort('page_id') ?></th>
                <th scope="col"><?= $this->Paginator->sort('user_id') ?></th>
                <th scope="col" class="actions"><?= __('Actions') ?></th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($userRights as $userRight): ?>
            <tr>
                <td><?= $this->Number->format($userRight->id) ?></td>
                <td><?= $userRight->has('page') ? $this->Html->link($userRight->page->id, ['controller' => 'Pages', 'action' => 'view', $userRight->page->id]) : '' ?></td>
                <td><?= $userRight->has('user') ? $this->Html->link($userRight->user->name, ['controller' => 'Users', 'action' => 'view', $userRight->user->id]) : '' ?></td>
                <td class="actions">
                    <?= $this->Html->link(__('View'), ['action' => 'view', $userRight->id]) ?>
                    <?= $this->Html->link(__('Edit'), ['action' => 'edit', $userRight->id]) ?>
                    <?= $this->Form->postLink(__('Delete'), ['action' => 'delete', $userRight->id], ['confirm' => __('Are you sure you want to delete # {0}?', $userRight->id)]) ?>
                </td>
            </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
    <div class="paginator">
        <ul class="pagination">
            <?= $this->Paginator->first('<< ' . __('first')) ?>
            <?= $this->Paginator->prev('< ' . __('previous')) ?>
            <?= $this->Paginator->numbers() ?>
            <?= $this->Paginator->next(__('next') . ' >') ?>
            <?= $this->Paginator->last(__('last') . ' >>') ?>
        </ul>
        <p><?= $this->Paginator->counter(['format' => __('Page {{page}} of {{pages}}, showing {{current}} record(s) out of {{count}} total')]) ?></p>
    </div>
</div>
