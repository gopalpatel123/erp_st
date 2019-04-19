<?php
/**
  * @var \App\View\AppView $this
  * @var \App\Model\Entity\FirstTampGrnRecord $firstTampGrnRecord
  */
?>
<nav class="large-3 medium-4 columns" id="actions-sidebar">
    <ul class="side-nav">
        <li class="heading"><?= __('Actions') ?></li>
        <li><?= $this->Html->link(__('Edit First Tamp Grn Record'), ['action' => 'edit', $firstTampGrnRecord->id]) ?> </li>
        <li><?= $this->Form->postLink(__('Delete First Tamp Grn Record'), ['action' => 'delete', $firstTampGrnRecord->id], ['confirm' => __('Are you sure you want to delete # {0}?', $firstTampGrnRecord->id)]) ?> </li>
        <li><?= $this->Html->link(__('List First Tamp Grn Records'), ['action' => 'index']) ?> </li>
        <li><?= $this->Html->link(__('New First Tamp Grn Record'), ['action' => 'add']) ?> </li>
        <li><?= $this->Html->link(__('List Users'), ['controller' => 'Users', 'action' => 'index']) ?> </li>
        <li><?= $this->Html->link(__('New User'), ['controller' => 'Users', 'action' => 'add']) ?> </li>
    </ul>
</nav>
<div class="firstTampGrnRecords view large-9 medium-8 columns content">
    <h3><?= h($firstTampGrnRecord->id) ?></h3>
    <table class="vertical-table">
        <tr>
            <th scope="row"><?= __('Item Code') ?></th>
            <td><?= h($firstTampGrnRecord->item_code) ?></td>
        </tr>
        <tr>
            <th scope="row"><?= __('User') ?></th>
            <td><?= $firstTampGrnRecord->has('user') ? $this->Html->link($firstTampGrnRecord->user->name, ['controller' => 'Users', 'action' => 'view', $firstTampGrnRecord->user->id]) : '' ?></td>
        </tr>
        <tr>
            <th scope="row"><?= __('Processed') ?></th>
            <td><?= h($firstTampGrnRecord->processed) ?></td>
        </tr>
        <tr>
            <th scope="row"><?= __('Is Addition Item Data Required') ?></th>
            <td><?= h($firstTampGrnRecord->is_addition_item_data_required) ?></td>
        </tr>
        <tr>
            <th scope="row"><?= __('Id') ?></th>
            <td><?= $this->Number->format($firstTampGrnRecord->id) ?></td>
        </tr>
        <tr>
            <th scope="row"><?= __('Quantity') ?></th>
            <td><?= $this->Number->format($firstTampGrnRecord->quantity) ?></td>
        </tr>
        <tr>
            <th scope="row"><?= __('Purchase Rate') ?></th>
            <td><?= $this->Number->format($firstTampGrnRecord->purchase_rate) ?></td>
        </tr>
        <tr>
            <th scope="row"><?= __('Sales Rate') ?></th>
            <td><?= $this->Number->format($firstTampGrnRecord->sales_rate) ?></td>
        </tr>
    </table>
</div>
