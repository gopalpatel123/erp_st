<?php
/**
  * @var \App\View\AppView $this
  * @var \App\Model\Entity\FirstTampGrnRecord[]|\Cake\Collection\CollectionInterface $firstTampGrnRecords
  */
?>
<nav class="large-3 medium-4 columns" id="actions-sidebar">
    <ul class="side-nav">
        <li class="heading"><?= __('Actions') ?></li>
        <li><?= $this->Html->link(__('New First Tamp Grn Record'), ['action' => 'add']) ?></li>
        <li><?= $this->Html->link(__('List Users'), ['controller' => 'Users', 'action' => 'index']) ?></li>
        <li><?= $this->Html->link(__('New User'), ['controller' => 'Users', 'action' => 'add']) ?></li>
    </ul>
</nav>
<div class="firstTampGrnRecords index large-9 medium-8 columns content">
    <h3><?= __('First Tamp Grn Records') ?></h3>
    <table cellpadding="0" cellspacing="0">
        <thead>
            <tr>
                <th scope="col"><?= $this->Paginator->sort('id') ?></th>
                <th scope="col"><?= $this->Paginator->sort('item_code') ?></th>
                <th scope="col"><?= $this->Paginator->sort('quantity') ?></th>
                <th scope="col"><?= $this->Paginator->sort('purchase_rate') ?></th>
                <th scope="col"><?= $this->Paginator->sort('sales_rate') ?></th>
                <th scope="col"><?= $this->Paginator->sort('user_id') ?></th>
                <th scope="col"><?= $this->Paginator->sort('processed') ?></th>
                <th scope="col"><?= $this->Paginator->sort('is_addition_item_data_required') ?></th>
                <th scope="col" class="actions"><?= __('Actions') ?></th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($firstTampGrnRecords as $firstTampGrnRecord): ?>
            <tr>
                <td><?= $this->Number->format($firstTampGrnRecord->id) ?></td>
                <td><?= h($firstTampGrnRecord->item_code) ?></td>
                <td><?= $this->Number->format($firstTampGrnRecord->quantity) ?></td>
                <td><?= $this->Number->format($firstTampGrnRecord->purchase_rate) ?></td>
                <td><?= $this->Number->format($firstTampGrnRecord->sales_rate) ?></td>
                <td><?= $firstTampGrnRecord->has('user') ? $this->Html->link($firstTampGrnRecord->user->name, ['controller' => 'Users', 'action' => 'view', $firstTampGrnRecord->user->id]) : '' ?></td>
                <td><?= h($firstTampGrnRecord->processed) ?></td>
                <td><?= h($firstTampGrnRecord->is_addition_item_data_required) ?></td>
                <td class="actions">
                    <?= $this->Html->link(__('View'), ['action' => 'view', $firstTampGrnRecord->id]) ?>
                    <?= $this->Html->link(__('Edit'), ['action' => 'edit', $firstTampGrnRecord->id]) ?>
                    <?= $this->Form->postLink(__('Delete'), ['action' => 'delete', $firstTampGrnRecord->id], ['confirm' => __('Are you sure you want to delete # {0}?', $firstTampGrnRecord->id)]) ?>
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
