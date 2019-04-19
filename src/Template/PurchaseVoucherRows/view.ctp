<?php
/**
  * @var \App\View\AppView $this
  * @var \App\Model\Entity\PurchaseVoucherRow $purchaseVoucherRow
  */
?>
<nav class="large-3 medium-4 columns" id="actions-sidebar">
    <ul class="side-nav">
        <li class="heading"><?= __('Actions') ?></li>
        <li><?= $this->Html->link(__('Edit Purchase Voucher Row'), ['action' => 'edit', $purchaseVoucherRow->id]) ?> </li>
        <li><?= $this->Form->postLink(__('Delete Purchase Voucher Row'), ['action' => 'delete', $purchaseVoucherRow->id], ['confirm' => __('Are you sure you want to delete # {0}?', $purchaseVoucherRow->id)]) ?> </li>
        <li><?= $this->Html->link(__('List Purchase Voucher Rows'), ['action' => 'index']) ?> </li>
        <li><?= $this->Html->link(__('New Purchase Voucher Row'), ['action' => 'add']) ?> </li>
        <li><?= $this->Html->link(__('List Purchase Vouchers'), ['controller' => 'PurchaseVouchers', 'action' => 'index']) ?> </li>
        <li><?= $this->Html->link(__('New Purchase Voucher'), ['controller' => 'PurchaseVouchers', 'action' => 'add']) ?> </li>
        <li><?= $this->Html->link(__('List Ledgers'), ['controller' => 'Ledgers', 'action' => 'index']) ?> </li>
        <li><?= $this->Html->link(__('New Ledger'), ['controller' => 'Ledgers', 'action' => 'add']) ?> </li>
    </ul>
</nav>
<div class="purchaseVoucherRows view large-9 medium-8 columns content">
    <h3><?= h($purchaseVoucherRow->id) ?></h3>
    <table class="vertical-table">
        <tr>
            <th scope="row"><?= __('Purchase Voucher') ?></th>
            <td><?= $purchaseVoucherRow->has('purchase_voucher') ? $this->Html->link($purchaseVoucherRow->purchase_voucher->id, ['controller' => 'PurchaseVouchers', 'action' => 'view', $purchaseVoucherRow->purchase_voucher->id]) : '' ?></td>
        </tr>
        <tr>
            <th scope="row"><?= __('Ledger') ?></th>
            <td><?= $purchaseVoucherRow->has('ledger') ? $this->Html->link($purchaseVoucherRow->ledger->name, ['controller' => 'Ledgers', 'action' => 'view', $purchaseVoucherRow->ledger->id]) : '' ?></td>
        </tr>
        <tr>
            <th scope="row"><?= __('Id') ?></th>
            <td><?= $this->Number->format($purchaseVoucherRow->id) ?></td>
        </tr>
        <tr>
            <th scope="row"><?= __('Debit') ?></th>
            <td><?= $this->Number->format($purchaseVoucherRow->debit) ?></td>
        </tr>
        <tr>
            <th scope="row"><?= __('Credit') ?></th>
            <td><?= $this->Number->format($purchaseVoucherRow->credit) ?></td>
        </tr>
    </table>
</div>
