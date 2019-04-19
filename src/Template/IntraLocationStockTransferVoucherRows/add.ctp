<?php
/**
  * @var \App\View\AppView $this
  */
?>
<nav class="large-3 medium-4 columns" id="actions-sidebar">
    <ul class="side-nav">
        <li class="heading"><?= __('Actions') ?></li>
        <li><?= $this->Html->link(__('List Intra Location Stock Transfer Voucher Rows'), ['action' => 'index']) ?></li>
        <li><?= $this->Html->link(__('List Intra Location Stock Transfer Vouchers'), ['controller' => 'IntraLocationStockTransferVouchers', 'action' => 'index']) ?></li>
        <li><?= $this->Html->link(__('New Intra Location Stock Transfer Voucher'), ['controller' => 'IntraLocationStockTransferVouchers', 'action' => 'add']) ?></li>
        <li><?= $this->Html->link(__('List Items'), ['controller' => 'Items', 'action' => 'index']) ?></li>
        <li><?= $this->Html->link(__('New Item'), ['controller' => 'Items', 'action' => 'add']) ?></li>
    </ul>
</nav>
<div class="intraLocationStockTransferVoucherRows form large-9 medium-8 columns content">
    <?= $this->Form->create($intraLocationStockTransferVoucherRow) ?>
    <fieldset>
        <legend><?= __('Add Intra Location Stock Transfer Voucher Row') ?></legend>
        <?php
            echo $this->Form->control('intra_location_stock_transfer_voucher_id', ['options' => $intraLocationStockTransferVouchers]);
            echo $this->Form->control('item_id', ['options' => $items]);
            echo $this->Form->control('quantity');
        ?>
    </fieldset>
    <?= $this->Form->button(__('Submit')) ?>
    <?= $this->Form->end() ?>
</div>
