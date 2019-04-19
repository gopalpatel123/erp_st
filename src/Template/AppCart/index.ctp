<?php
/**
  * @var \App\View\AppView $this
  * @var \App\Model\Entity\AppCart[]|\Cake\Collection\CollectionInterface $appCart
  */
?>
<nav class="large-3 medium-4 columns" id="actions-sidebar">
    <ul class="side-nav">
        <li class="heading"><?= __('Actions') ?></li>
        <li><?= $this->Html->link(__('New App Cart'), ['action' => 'add']) ?></li>
        <li><?= $this->Html->link(__('List Items'), ['controller' => 'Items', 'action' => 'index']) ?></li>
        <li><?= $this->Html->link(__('New Item'), ['controller' => 'Items', 'action' => 'add']) ?></li>
        <li><?= $this->Html->link(__('List Users'), ['controller' => 'Users', 'action' => 'index']) ?></li>
        <li><?= $this->Html->link(__('New User'), ['controller' => 'Users', 'action' => 'add']) ?></li>
        <li><?= $this->Html->link(__('List Companies'), ['controller' => 'Companies', 'action' => 'index']) ?></li>
        <li><?= $this->Html->link(__('New Company'), ['controller' => 'Companies', 'action' => 'add']) ?></li>
        <li><?= $this->Html->link(__('List Locations'), ['controller' => 'Locations', 'action' => 'index']) ?></li>
        <li><?= $this->Html->link(__('New Location'), ['controller' => 'Locations', 'action' => 'add']) ?></li>
    </ul>
</nav>
<div class="appCart index large-9 medium-8 columns content">
    <h3><?= __('App Cart') ?></h3>
    <table cellpadding="0" cellspacing="0">
        <thead>
            <tr>
                <th scope="col"><?= $this->Paginator->sort('id') ?></th>
                <th scope="col"><?= $this->Paginator->sort('item_id') ?></th>
                <th scope="col"><?= $this->Paginator->sort('user_id') ?></th>
                <th scope="col"><?= $this->Paginator->sort('quantity') ?></th>
                <th scope="col"><?= $this->Paginator->sort('company_id') ?></th>
                <th scope="col"><?= $this->Paginator->sort('location_id') ?></th>
                <th scope="col" class="actions"><?= __('Actions') ?></th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($appCart as $appCart): ?>
            <tr>
                <td><?= $this->Number->format($appCart->id) ?></td>
                <td><?= $appCart->has('item') ? $this->Html->link($appCart->item->name, ['controller' => 'Items', 'action' => 'view', $appCart->item->id]) : '' ?></td>
                <td><?= $appCart->has('user') ? $this->Html->link($appCart->user->name, ['controller' => 'Users', 'action' => 'view', $appCart->user->id]) : '' ?></td>
                <td><?= $this->Number->format($appCart->quantity) ?></td>
                <td><?= $appCart->has('company') ? $this->Html->link($appCart->company->name, ['controller' => 'Companies', 'action' => 'view', $appCart->company->id]) : '' ?></td>
                <td><?= $appCart->has('location') ? $this->Html->link($appCart->location->name, ['controller' => 'Locations', 'action' => 'view', $appCart->location->id]) : '' ?></td>
                <td class="actions">
                    <?= $this->Html->link(__('View'), ['action' => 'view', $appCart->id]) ?>
                    <?= $this->Html->link(__('Edit'), ['action' => 'edit', $appCart->id]) ?>
                    <?= $this->Form->postLink(__('Delete'), ['action' => 'delete', $appCart->id], ['confirm' => __('Are you sure you want to delete # {0}?', $appCart->id)]) ?>
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
