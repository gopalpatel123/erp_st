<?php
/**
  * @var \App\View\AppView $this
  * @var \App\Model\Entity\Inward[]|\Cake\Collection\CollectionInterface $inwards
  */
?>
<nav class="large-3 medium-4 columns" id="actions-sidebar">
    <ul class="side-nav">
        <li class="heading"><?= __('Actions') ?></li>
        <li><?= $this->Html->link(__('New Inward'), ['action' => 'add']) ?></li>
        <li><?= $this->Html->link(__('List Items'), ['controller' => 'Items', 'action' => 'index']) ?></li>
        <li><?= $this->Html->link(__('New Item'), ['controller' => 'Items', 'action' => 'add']) ?></li>
        <li><?= $this->Html->link(__('List Stock Journals'), ['controller' => 'StockJournals', 'action' => 'index']) ?></li>
        <li><?= $this->Html->link(__('New Stock Journal'), ['controller' => 'StockJournals', 'action' => 'add']) ?></li>
    </ul>
</nav>
<div class="inwards index large-9 medium-8 columns content">
    <h3><?= __('Inwards') ?></h3>
    <table cellpadding="0" cellspacing="0">
        <thead>
            <tr>
                <th scope="col"><?= $this->Paginator->sort('id') ?></th>
                <th scope="col"><?= $this->Paginator->sort('item_id') ?></th>
                <th scope="col"><?= $this->Paginator->sort('quantity') ?></th>
                <th scope="col"><?= $this->Paginator->sort('rate') ?></th>
                <th scope="col"><?= $this->Paginator->sort('amount') ?></th>
                <th scope="col"><?= $this->Paginator->sort('stock_journal_id') ?></th>
                <th scope="col" class="actions"><?= __('Actions') ?></th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($inwards as $inward): ?>
            <tr>
                <td><?= $this->Number->format($inward->id) ?></td>
                <td><?= $inward->has('item') ? $this->Html->link($inward->item->name, ['controller' => 'Items', 'action' => 'view', $inward->item->id]) : '' ?></td>
                <td><?= $this->Number->format($inward->quantity) ?></td>
                <td><?= $this->Number->format($inward->rate) ?></td>
                <td><?= $this->Number->format($inward->amount) ?></td>
                <td><?= $inward->has('stock_journal') ? $this->Html->link($inward->stock_journal->id, ['controller' => 'StockJournals', 'action' => 'view', $inward->stock_journal->id]) : '' ?></td>
                <td class="actions">
                    <?= $this->Html->link(__('View'), ['action' => 'view', $inward->id]) ?>
                    <?= $this->Html->link(__('Edit'), ['action' => 'edit', $inward->id]) ?>
                    <?= $this->Form->postLink(__('Delete'), ['action' => 'delete', $inward->id], ['confirm' => __('Are you sure you want to delete # {0}?', $inward->id)]) ?>
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
