<?php
/**
  * @var \App\View\AppView $this
  */
?>
<nav class="large-3 medium-4 columns" id="actions-sidebar">
    <ul class="side-nav">
        <li class="heading"><?= __('Actions') ?></li>
        <li><?= $this->Html->link(__('List Sales Voucher Rows'), ['action' => 'index']) ?></li>
        <li><?= $this->Html->link(__('List Sales Vouchers'), ['controller' => 'SalesVouchers', 'action' => 'index']) ?></li>
        <li><?= $this->Html->link(__('New Sales Voucher'), ['controller' => 'SalesVouchers', 'action' => 'add']) ?></li>
        <li><?= $this->Html->link(__('List Ledgers'), ['controller' => 'Ledgers', 'action' => 'index']) ?></li>
        <li><?= $this->Html->link(__('New Ledger'), ['controller' => 'Ledgers', 'action' => 'add']) ?></li>
        <li><?= $this->Html->link(__('List Reference Details'), ['controller' => 'ReferenceDetails', 'action' => 'index']) ?></li>
        <li><?= $this->Html->link(__('New Reference Detail'), ['controller' => 'ReferenceDetails', 'action' => 'add']) ?></li>
    </ul>
</nav>
<div class="salesVoucherRows form large-9 medium-8 columns content">
    <?= $this->Form->create($salesVoucherRow) ?>
    <fieldset>
        <legend><?= __('Add Sales Voucher Row') ?></legend>
        <?php
            echo $this->Form->control('sales_voucher_id', ['options' => $salesVouchers]);
            echo $this->Form->control('cr_dr');
            echo $this->Form->control('ledger_id', ['options' => $ledgers]);
            echo $this->Form->control('debit');
            echo $this->Form->control('credit');
            echo $this->Form->control('mode_of_payment');
            echo $this->Form->control('cheque_no');
            echo $this->Form->control('cheque_date');
        ?>
    </fieldset>
    <?= $this->Form->button(__('Submit')) ?>
    <?= $this->Form->end() ?>
</div>
