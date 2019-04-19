<?php
/**
  * @var \App\View\AppView $this
  * @var \App\Model\Entity\Outward $outward
  */
?>
<nav class="large-3 medium-4 columns" id="actions-sidebar">
    <ul class="side-nav">
        <li class="heading"><?= __('Actions') ?></li>
        <li><?= $this->Html->link(__('Edit Outward'), ['action' => 'edit', $outward->id]) ?> </li>
        <li><?= $this->Form->postLink(__('Delete Outward'), ['action' => 'delete', $outward->id], ['confirm' => __('Are you sure you want to delete # {0}?', $outward->id)]) ?> </li>
        <li><?= $this->Html->link(__('List Outwards'), ['action' => 'index']) ?> </li>
        <li><?= $this->Html->link(__('New Outward'), ['action' => 'add']) ?> </li>
        <li><?= $this->Html->link(__('List Items'), ['controller' => 'Items', 'action' => 'index']) ?> </li>
        <li><?= $this->Html->link(__('New Item'), ['controller' => 'Items', 'action' => 'add']) ?> </li>
        <li><?= $this->Html->link(__('List Stock Journals'), ['controller' => 'StockJournals', 'action' => 'index']) ?> </li>
        <li><?= $this->Html->link(__('New Stock Journal'), ['controller' => 'StockJournals', 'action' => 'add']) ?> </li>
    </ul>
</nav>
<div class="outwards view large-9 medium-8 columns content">
    <h3><?= h($outward->id) ?></h3>
    <table class="vertical-table">
        <tr>
            <th scope="row"><?= __('Item') ?></th>
            <td><?= $outward->has('item') ? $this->Html->link($outward->item->name, ['controller' => 'Items', 'action' => 'view', $outward->item->id]) : '' ?></td>
        </tr>
        <tr>
            <th scope="row"><?= __('Stock Journal') ?></th>
            <td><?= $outward->has('stock_journal') ? $this->Html->link($outward->stock_journal->id, ['controller' => 'StockJournals', 'action' => 'view', $outward->stock_journal->id]) : '' ?></td>
        </tr>
        <tr>
            <th scope="row"><?= __('Id') ?></th>
            <td><?= $this->Number->format($outward->id) ?></td>
        </tr>
        <tr>
            <th scope="row"><?= __('Quantity') ?></th>
            <td><?= $this->Number->format($outward->quantity) ?></td>
        </tr>
        <tr>
            <th scope="row"><?= __('Rate') ?></th>
            <td><?= $this->Number->format($outward->rate) ?></td>
        </tr>
        <tr>
            <th scope="row"><?= __('Amount') ?></th>
            <td><?= $this->Number->format($outward->amount) ?></td>
        </tr>
    </table>
</div>
