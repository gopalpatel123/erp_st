<?php
/**
  * @var \App\View\AppView $this
  */
?>
<nav class="large-3 medium-4 columns" id="actions-sidebar">
    <ul class="side-nav">
        <li class="heading"><?= __('Actions') ?></li>
        <li><?= $this->Html->link(__('List Accounting Entries'), ['action' => 'index']) ?></li>
        <li><?= $this->Html->link(__('List Ledgers'), ['controller' => 'Ledgers', 'action' => 'index']) ?></li>
        <li><?= $this->Html->link(__('New Ledger'), ['controller' => 'Ledgers', 'action' => 'add']) ?></li>
        <li><?= $this->Html->link(__('List Companies'), ['controller' => 'Companies', 'action' => 'index']) ?></li>
        <li><?= $this->Html->link(__('New Company'), ['controller' => 'Companies', 'action' => 'add']) ?></li>
        <li><?= $this->Html->link(__('List Purchase Vouchers'), ['controller' => 'PurchaseVouchers', 'action' => 'index']) ?></li>
        <li><?= $this->Html->link(__('New Purchase Voucher'), ['controller' => 'PurchaseVouchers', 'action' => 'add']) ?></li>
        <li><?= $this->Html->link(__('List Sales Invoices'), ['controller' => 'SalesInvoices', 'action' => 'index']) ?></li>
        <li><?= $this->Html->link(__('New Sales Invoice'), ['controller' => 'SalesInvoices', 'action' => 'add']) ?></li>
    </ul>
</nav>
<div class="accountingEntries form large-9 medium-8 columns content">
    <?= $this->Form->create($accountingEntry) ?>
    <fieldset>
        <legend><?= __('Add Accounting Entry') ?></legend>
        <?php
            echo $this->Form->control('ledger_id', ['options' => $ledgers]);
            echo $this->Form->control('debit');
            echo $this->Form->control('credit');
            echo $this->Form->control('transaction_date');
            echo $this->Form->control('company_id', ['options' => $companies]);
            echo $this->Form->control('purchase_voucher_id', ['options' => $purchaseVouchers, 'empty' => true]);
            echo $this->Form->control('purchase_voucher_row_id');
            echo $this->Form->control('is_opening_balance');
            echo $this->Form->control('sales_invoice_id', ['options' => $salesInvoices]);
            echo $this->Form->control('credit_note_id');
        ?>
    </fieldset>
    <?= $this->Form->button(__('Submit')) ?>
    <?= $this->Form->end() ?>
</div>
