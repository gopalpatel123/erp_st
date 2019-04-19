<?php
/**
  * @var \App\View\AppView $this
  */
?>
<nav class="large-3 medium-4 columns" id="actions-sidebar">
    <ul class="side-nav">
        <li class="heading"><?= __('Actions') ?></li>
        <li><?= $this->Form->postLink(
                __('Delete'),
                ['action' => 'delete', $purchaseVoucherRow->id],
                ['confirm' => __('Are you sure you want to delete # {0}?', $purchaseVoucherRow->id)]
            )
        ?></li>
        <li><?= $this->Html->link(__('List Purchase Voucher Rows'), ['action' => 'index']) ?></li>
        <li><?= $this->Html->link(__('List Purchase Vouchers'), ['controller' => 'PurchaseVouchers', 'action' => 'index']) ?></li>
        <li><?= $this->Html->link(__('New Purchase Voucher'), ['controller' => 'PurchaseVouchers', 'action' => 'add']) ?></li>
        <li><?= $this->Html->link(__('List Ledgers'), ['controller' => 'Ledgers', 'action' => 'index']) ?></li>
        <li><?= $this->Html->link(__('New Ledger'), ['controller' => 'Ledgers', 'action' => 'add']) ?></li>
    </ul>
</nav>
<div class="purchaseVoucherRows form large-9 medium-8 columns content">
    <?= $this->Form->create($purchaseVoucherRow) ?>
    <fieldset>
        <legend><?= __('Edit Purchase Voucher Row') ?></legend>
        <?php
            echo $this->Form->control('purchase_voucher_id', ['options' => $purchaseVouchers]);
            echo $this->Form->control('ledger_id', ['options' => $ledgers]);
            echo $this->Form->control('debit');
            echo $this->Form->control('credit');
        ?>
    </fieldset>
    <?= $this->Form->button(__('Submit')) ?>
    <?= $this->Form->end() ?>
</div>
