<?php
/**
  * @var \App\View\AppView $this
  * @var \App\Model\Entity\ItemLedger $itemLedger
  */
?>
<nav class="large-3 medium-4 columns" id="actions-sidebar">
    <ul class="side-nav">
        <li class="heading"><?= __('Actions') ?></li>
        <li><?= $this->Html->link(__('Edit Item Ledger'), ['action' => 'edit', $itemLedger->id]) ?> </li>
        <li><?= $this->Form->postLink(__('Delete Item Ledger'), ['action' => 'delete', $itemLedger->id], ['confirm' => __('Are you sure you want to delete # {0}?', $itemLedger->id)]) ?> </li>
        <li><?= $this->Html->link(__('List Item Ledgers'), ['action' => 'index']) ?> </li>
        <li><?= $this->Html->link(__('New Item Ledger'), ['action' => 'add']) ?> </li>
        <li><?= $this->Html->link(__('List Items'), ['controller' => 'Items', 'action' => 'index']) ?> </li>
        <li><?= $this->Html->link(__('New Item'), ['controller' => 'Items', 'action' => 'add']) ?> </li>
    </ul>
</nav>
<div class="itemLedgers view large-9 medium-8 columns content">
    <h3><?= h($itemLedger->id) ?></h3>
    <table class="vertical-table">
        <tr>
            <th scope="row"><?= __('Item') ?></th>
            <td><?= $itemLedger->has('item') ? $this->Html->link($itemLedger->item->name, ['controller' => 'Items', 'action' => 'view', $itemLedger->item->id]) : '' ?></td>
        </tr>
        <tr>
            <th scope="row"><?= __('Status') ?></th>
            <td><?= h($itemLedger->status) ?></td>
        </tr>
        <tr>
            <th scope="row"><?= __('Is Opening Balance') ?></th>
            <td><?= h($itemLedger->is_opening_balance) ?></td>
        </tr>
        <tr>
            <th scope="row"><?= __('Id') ?></th>
            <td><?= $this->Number->format($itemLedger->id) ?></td>
        </tr>
        <tr>
            <th scope="row"><?= __('Quantity') ?></th>
            <td><?= $this->Number->format($itemLedger->quantity) ?></td>
        </tr>
        <tr>
            <th scope="row"><?= __('Rate') ?></th>
            <td><?= $this->Number->format($itemLedger->rate) ?></td>
        </tr>
        <tr>
            <th scope="row"><?= __('Amount') ?></th>
            <td><?= $this->Number->format($itemLedger->amount) ?></td>
        </tr>
        <tr>
            <th scope="row"><?= __('Transaction Date') ?></th>
            <td><?= h($itemLedger->transaction_date) ?></td>
        </tr>
    </table>
</div>
