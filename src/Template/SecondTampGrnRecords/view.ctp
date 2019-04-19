<?php
/**
  * @var \App\View\AppView $this
  * @var \App\Model\Entity\SecondTampGrnRecord $secondTampGrnRecord
  */
?>
<nav class="large-3 medium-4 columns" id="actions-sidebar">
    <ul class="side-nav">
        <li class="heading"><?= __('Actions') ?></li>
        <li><?= $this->Html->link(__('Edit Second Tamp Grn Record'), ['action' => 'edit', $secondTampGrnRecord->id]) ?> </li>
        <li><?= $this->Form->postLink(__('Delete Second Tamp Grn Record'), ['action' => 'delete', $secondTampGrnRecord->id], ['confirm' => __('Are you sure you want to delete # {0}?', $secondTampGrnRecord->id)]) ?> </li>
        <li><?= $this->Html->link(__('List Second Tamp Grn Records'), ['action' => 'index']) ?> </li>
        <li><?= $this->Html->link(__('New Second Tamp Grn Record'), ['action' => 'add']) ?> </li>
        <li><?= $this->Html->link(__('List Users'), ['controller' => 'Users', 'action' => 'index']) ?> </li>
        <li><?= $this->Html->link(__('New User'), ['controller' => 'Users', 'action' => 'add']) ?> </li>
    </ul>
</nav>
<div class="secondTampGrnRecords view large-9 medium-8 columns content">
    <h3><?= h($secondTampGrnRecord->id) ?></h3>
    <table class="vertical-table">
        <tr>
            <th scope="row"><?= __('Item Code') ?></th>
            <td><?= h($secondTampGrnRecord->item_code) ?></td>
        </tr>
        <tr>
            <th scope="row"><?= __('User') ?></th>
            <td><?= $secondTampGrnRecord->has('user') ? $this->Html->link($secondTampGrnRecord->user->name, ['controller' => 'Users', 'action' => 'view', $secondTampGrnRecord->user->id]) : '' ?></td>
        </tr>
        <tr>
            <th scope="row"><?= __('Processed') ?></th>
            <td><?= h($secondTampGrnRecord->processed) ?></td>
        </tr>
        <tr>
            <th scope="row"><?= __('Item Name') ?></th>
            <td><?= h($secondTampGrnRecord->item_name) ?></td>
        </tr>
        <tr>
            <th scope="row"><?= __('Hsn Code') ?></th>
            <td><?= h($secondTampGrnRecord->hsn_code) ?></td>
        </tr>
        <tr>
            <th scope="row"><?= __('Unit') ?></th>
            <td><?= h($secondTampGrnRecord->unit) ?></td>
        </tr>
        <tr>
            <th scope="row"><?= __('Id') ?></th>
            <td><?= $this->Number->format($secondTampGrnRecord->id) ?></td>
        </tr>
        <tr>
            <th scope="row"><?= __('Quantity') ?></th>
            <td><?= $this->Number->format($secondTampGrnRecord->quantity) ?></td>
        </tr>
        <tr>
            <th scope="row"><?= __('Purchase Rate') ?></th>
            <td><?= $this->Number->format($secondTampGrnRecord->purchase_rate) ?></td>
        </tr>
        <tr>
            <th scope="row"><?= __('Sales Rate') ?></th>
            <td><?= $this->Number->format($secondTampGrnRecord->sales_rate) ?></td>
        </tr>
        <tr>
            <th scope="row"><?= __('Is Addition Item Data Required') ?></th>
            <td><?= $this->Number->format($secondTampGrnRecord->is_addition_item_data_required) ?></td>
        </tr>
        <tr>
            <th scope="row"><?= __('Gst Rate Fixed Or Fluid') ?></th>
            <td><?= $this->Number->format($secondTampGrnRecord->gst_rate_fixed_or_fluid) ?></td>
        </tr>
        <tr>
            <th scope="row"><?= __('First Gst Rate') ?></th>
            <td><?= $this->Number->format($secondTampGrnRecord->first_gst_rate) ?></td>
        </tr>
        <tr>
            <th scope="row"><?= __('Amount In Ref Of Gst Rate') ?></th>
            <td><?= $this->Number->format($secondTampGrnRecord->amount_in_ref_of_gst_rate) ?></td>
        </tr>
        <tr>
            <th scope="row"><?= __('Second Gst Rate') ?></th>
            <td><?= $this->Number->format($secondTampGrnRecord->second_gst_rate) ?></td>
        </tr>
    </table>
</div>
