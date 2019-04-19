<?php
/**
  * @var \App\View\AppView $this
  * @var \App\Model\Entity\Outward[]|\Cake\Collection\CollectionInterface $outwards
  */
?>
<nav class="large-3 medium-4 columns" id="actions-sidebar">
    <ul class="side-nav">
        <li class="heading"><?= __('Actions') ?></li>
        <li><?= $this->Html->link(__('New Outward'), ['action' => 'add']) ?></li>
        <li><?= $this->Html->link(__('List Items'), ['controller' => 'Items', 'action' => 'index']) ?></li>
        <li><?= $this->Html->link(__('New Item'), ['controller' => 'Items', 'action' => 'add']) ?></li>
        <li><?= $this->Html->link(__('List Stock Journals'), ['controller' => 'StockJournals', 'action' => 'index']) ?></li>
        <li><?= $this->Html->link(__('New Stock Journal'), ['controller' => 'StockJournals', 'action' => 'add']) ?></li>
    </ul>
</nav>
<div class="outwards index large-9 medium-8 columns content">
    <h3><?= __('Outwards') ?></h3>
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
            <?php foreach ($outwards as $outward): ?>
            <tr>
                <td><?= $this->Number->format($outward->id) ?></td>
                <td><?= $outward->has('item') ? $this->Html->link($outward->item->name, ['controller' => 'Items', 'action' => 'view', $outward->item->id]) : '' ?></td>
                <td><?= $this->Number->format($outward->quantity) ?></td>
                <td><?= $this->Number->format($outward->rate) ?></td>
                <td><?= $this->Number->format($outward->amount) ?></td>
                <td><?= $outward->has('stock_journal') ? $this->Html->link($outward->stock_journal->id, ['controller' => 'StockJournals', 'action' => 'view', $outward->stock_journal->id]) : '' ?></td>
                <td class="actions">
                    <?= $this->Html->link(__('View'), ['action' => 'view', $outward->id]) ?>
                    <?= $this->Html->link(__('Edit'), ['action' => 'edit', $outward->id]) ?>
                    <?= $this->Form->postLink(__('Delete'), ['action' => 'delete', $outward->id], ['confirm' => __('Are you sure you want to delete # {0}?', $outward->id)]) ?>
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
