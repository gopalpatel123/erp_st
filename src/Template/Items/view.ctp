<?php
/**
  * @var \App\View\AppView $this
  * @var \App\Model\Entity\Item $item
  */
?>
<nav class="large-3 medium-4 columns" id="actions-sidebar">
    <ul class="side-nav">
        <li class="heading"><?= __('Actions') ?></li>
        <li><?= $this->Html->link(__('Edit Item'), ['action' => 'edit', $item->id]) ?> </li>
        <li><?= $this->Form->postLink(__('Delete Item'), ['action' => 'delete', $item->id], ['confirm' => __('Are you sure you want to delete # {0}?', $item->id)]) ?> </li>
        <li><?= $this->Html->link(__('List Items'), ['action' => 'index']) ?> </li>
        <li><?= $this->Html->link(__('New Item'), ['action' => 'add']) ?> </li>
        <li><?= $this->Html->link(__('List Units'), ['controller' => 'Units', 'action' => 'index']) ?> </li>
        <li><?= $this->Html->link(__('New Unit'), ['controller' => 'Units', 'action' => 'add']) ?> </li>
        <li><?= $this->Html->link(__('List Stock Groups'), ['controller' => 'StockGroups', 'action' => 'index']) ?> </li>
        <li><?= $this->Html->link(__('New Stock Group'), ['controller' => 'StockGroups', 'action' => 'add']) ?> </li>
    </ul>
</nav>
<div class="items view large-9 medium-8 columns content">
    <h3><?= h($item->name) ?></h3>
    <table class="vertical-table">
        <tr>
            <th scope="row"><?= __('Name') ?></th>
            <td><?= h($item->name) ?></td>
        </tr>
        <tr>
            <th scope="row"><?= __('Hsn Code') ?></th>
            <td><?= h($item->hsn_code) ?></td>
        </tr>
        <tr>
            <th scope="row"><?= __('Unit') ?></th>
            <td><?= $item->has('unit') ? $this->Html->link($item->unit->name, ['controller' => 'Units', 'action' => 'view', $item->unit->id]) : '' ?></td>
        </tr>
        <tr>
            <th scope="row"><?= __('Stock Group') ?></th>
            <td><?= $item->has('stock_group') ? $this->Html->link($item->stock_group->name, ['controller' => 'StockGroups', 'action' => 'view', $item->stock_group->id]) : '' ?></td>
        </tr>
        <tr>
            <th scope="row"><?= __('Id') ?></th>
            <td><?= $this->Number->format($item->id) ?></td>
        </tr>
    </table>
</div>
