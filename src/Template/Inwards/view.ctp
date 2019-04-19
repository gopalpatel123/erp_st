<?php
/**
  * @var \App\View\AppView $this
  * @var \App\Model\Entity\Inward $inward
  */
?>
<nav class="large-3 medium-4 columns" id="actions-sidebar">
    <ul class="side-nav">
        <li class="heading"><?= __('Actions') ?></li>
        <li><?= $this->Html->link(__('Edit Inward'), ['action' => 'edit', $inward->id]) ?> </li>
        <li><?= $this->Form->postLink(__('Delete Inward'), ['action' => 'delete', $inward->id], ['confirm' => __('Are you sure you want to delete # {0}?', $inward->id)]) ?> </li>
        <li><?= $this->Html->link(__('List Inwards'), ['action' => 'index']) ?> </li>
        <li><?= $this->Html->link(__('New Inward'), ['action' => 'add']) ?> </li>
        <li><?= $this->Html->link(__('List Items'), ['controller' => 'Items', 'action' => 'index']) ?> </li>
        <li><?= $this->Html->link(__('New Item'), ['controller' => 'Items', 'action' => 'add']) ?> </li>
        <li><?= $this->Html->link(__('List Stock Journals'), ['controller' => 'StockJournals', 'action' => 'index']) ?> </li>
        <li><?= $this->Html->link(__('New Stock Journal'), ['controller' => 'StockJournals', 'action' => 'add']) ?> </li>
    </ul>
</nav>
<div class="inwards view large-9 medium-8 columns content">
    <h3><?= h($inward->id) ?></h3>
    <table class="vertical-table">
        <tr>
            <th scope="row"><?= __('Item') ?></th>
            <td><?= $inward->has('item') ? $this->Html->link($inward->item->name, ['controller' => 'Items', 'action' => 'view', $inward->item->id]) : '' ?></td>
        </tr>
        <tr>
            <th scope="row"><?= __('Stock Journal') ?></th>
            <td><?= $inward->has('stock_journal') ? $this->Html->link($inward->stock_journal->id, ['controller' => 'StockJournals', 'action' => 'view', $inward->stock_journal->id]) : '' ?></td>
        </tr>
        <tr>
            <th scope="row"><?= __('Id') ?></th>
            <td><?= $this->Number->format($inward->id) ?></td>
        </tr>
        <tr>
            <th scope="row"><?= __('Quantity') ?></th>
            <td><?= $this->Number->format($inward->quantity) ?></td>
        </tr>
        <tr>
            <th scope="row"><?= __('Rate') ?></th>
            <td><?= $this->Number->format($inward->rate) ?></td>
        </tr>
        <tr>
            <th scope="row"><?= __('Amount') ?></th>
            <td><?= $this->Number->format($inward->amount) ?></td>
        </tr>
    </table>
</div>
