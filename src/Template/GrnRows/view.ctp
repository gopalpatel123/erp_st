<?php
/**
  * @var \App\View\AppView $this
  * @var \App\Model\Entity\GrnRow $grnRow
  */
?>
<nav class="large-3 medium-4 columns" id="actions-sidebar">
    <ul class="side-nav">
        <li class="heading"><?= __('Actions') ?></li>
        <li><?= $this->Html->link(__('Edit Grn Row'), ['action' => 'edit', $grnRow->id]) ?> </li>
        <li><?= $this->Form->postLink(__('Delete Grn Row'), ['action' => 'delete', $grnRow->id], ['confirm' => __('Are you sure you want to delete # {0}?', $grnRow->id)]) ?> </li>
        <li><?= $this->Html->link(__('List Grn Rows'), ['action' => 'index']) ?> </li>
        <li><?= $this->Html->link(__('New Grn Row'), ['action' => 'add']) ?> </li>
        <li><?= $this->Html->link(__('List Grns'), ['controller' => 'Grns', 'action' => 'index']) ?> </li>
        <li><?= $this->Html->link(__('New Grn'), ['controller' => 'Grns', 'action' => 'add']) ?> </li>
        <li><?= $this->Html->link(__('List Items'), ['controller' => 'Items', 'action' => 'index']) ?> </li>
        <li><?= $this->Html->link(__('New Item'), ['controller' => 'Items', 'action' => 'add']) ?> </li>
    </ul>
</nav>
<div class="grnRows view large-9 medium-8 columns content">
    <h3><?= h($grnRow->id) ?></h3>
    <table class="vertical-table">
        <tr>
            <th scope="row"><?= __('Grn') ?></th>
            <td><?= $grnRow->has('grn') ? $this->Html->link($grnRow->grn->id, ['controller' => 'Grns', 'action' => 'view', $grnRow->grn->id]) : '' ?></td>
        </tr>
        <tr>
            <th scope="row"><?= __('Item') ?></th>
            <td><?= $grnRow->has('item') ? $this->Html->link($grnRow->item->name, ['controller' => 'Items', 'action' => 'view', $grnRow->item->id]) : '' ?></td>
        </tr>
        <tr>
            <th scope="row"><?= __('Id') ?></th>
            <td><?= $this->Number->format($grnRow->id) ?></td>
        </tr>
        <tr>
            <th scope="row"><?= __('Quantity') ?></th>
            <td><?= $this->Number->format($grnRow->quantity) ?></td>
        </tr>
        <tr>
            <th scope="row"><?= __('Purchase Rate') ?></th>
            <td><?= $this->Number->format($grnRow->purchase_rate) ?></td>
        </tr>
        <tr>
            <th scope="row"><?= __('Sale Rate') ?></th>
            <td><?= $this->Number->format($grnRow->sale_rate) ?></td>
        </tr>
    </table>
</div>
