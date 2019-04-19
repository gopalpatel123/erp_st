<?php
/**
  * @var \App\View\AppView $this
  * @var \App\Model\Entity\IntraLocationStockTransferVoucherRow $intraLocationStockTransferVoucherRow
  */
?>
<nav class="large-3 medium-4 columns" id="actions-sidebar">
    <ul class="side-nav">
        <li class="heading"><?= __('Actions') ?></li>
        <li><?= $this->Html->link(__('Edit Intra Location Stock Transfer Voucher Row'), ['action' => 'edit', $intraLocationStockTransferVoucherRow->id]) ?> </li>
        <li><?= $this->Form->postLink(__('Delete Intra Location Stock Transfer Voucher Row'), ['action' => 'delete', $intraLocationStockTransferVoucherRow->id], ['confirm' => __('Are you sure you want to delete # {0}?', $intraLocationStockTransferVoucherRow->id)]) ?> </li>
        <li><?= $this->Html->link(__('List Intra Location Stock Transfer Voucher Rows'), ['action' => 'index']) ?> </li>
        <li><?= $this->Html->link(__('New Intra Location Stock Transfer Voucher Row'), ['action' => 'add']) ?> </li>
        <li><?= $this->Html->link(__('List Intra Location Stock Transfer Vouchers'), ['controller' => 'IntraLocationStockTransferVouchers', 'action' => 'index']) ?> </li>
        <li><?= $this->Html->link(__('New Intra Location Stock Transfer Voucher'), ['controller' => 'IntraLocationStockTransferVouchers', 'action' => 'add']) ?> </li>
        <li><?= $this->Html->link(__('List Items'), ['controller' => 'Items', 'action' => 'index']) ?> </li>
        <li><?= $this->Html->link(__('New Item'), ['controller' => 'Items', 'action' => 'add']) ?> </li>
    </ul>
</nav>
<div class="intraLocationStockTransferVoucherRows view large-9 medium-8 columns content">
    <h3><?= h($intraLocationStockTransferVoucherRow->id) ?></h3>
    <table class="vertical-table">
        <tr>
            <th scope="row"><?= __('Intra Location Stock Transfer Voucher') ?></th>
            <td><?= $intraLocationStockTransferVoucherRow->has('intra_location_stock_transfer_voucher') ? $this->Html->link($intraLocationStockTransferVoucherRow->intra_location_stock_transfer_voucher->id, ['controller' => 'IntraLocationStockTransferVouchers', 'action' => 'view', $intraLocationStockTransferVoucherRow->intra_location_stock_transfer_voucher->id]) : '' ?></td>
        </tr>
        <tr>
            <th scope="row"><?= __('Item') ?></th>
            <td><?= $intraLocationStockTransferVoucherRow->has('item') ? $this->Html->link($intraLocationStockTransferVoucherRow->item->name, ['controller' => 'Items', 'action' => 'view', $intraLocationStockTransferVoucherRow->item->id]) : '' ?></td>
        </tr>
        <tr>
            <th scope="row"><?= __('Id') ?></th>
            <td><?= $this->Number->format($intraLocationStockTransferVoucherRow->id) ?></td>
        </tr>
        <tr>
            <th scope="row"><?= __('Quantity') ?></th>
            <td><?= $this->Number->format($intraLocationStockTransferVoucherRow->quantity) ?></td>
        </tr>
    </table>
</div>
