<?php
/**
  * @var \App\View\AppView $this
  */
?>
<nav class="large-3 medium-4 columns" id="actions-sidebar">
    <ul class="side-nav">
        <li class="heading"><?= __('Actions') ?></li>
        <li><?= $this->Html->link(__('List Reference Details'), ['action' => 'index']) ?></li>
        <li><?= $this->Html->link(__('List Companies'), ['controller' => 'Companies', 'action' => 'index']) ?></li>
        <li><?= $this->Html->link(__('New Company'), ['controller' => 'Companies', 'action' => 'add']) ?></li>
        <li><?= $this->Html->link(__('List Ledgers'), ['controller' => 'Ledgers', 'action' => 'index']) ?></li>
        <li><?= $this->Html->link(__('New Ledger'), ['controller' => 'Ledgers', 'action' => 'add']) ?></li>
        <li><?= $this->Html->link(__('List Receipts'), ['controller' => 'Receipts', 'action' => 'index']) ?></li>
        <li><?= $this->Html->link(__('New Receipt'), ['controller' => 'Receipts', 'action' => 'add']) ?></li>
        <li><?= $this->Html->link(__('List Receipt Rows'), ['controller' => 'ReceiptRows', 'action' => 'index']) ?></li>
        <li><?= $this->Html->link(__('New Receipt Row'), ['controller' => 'ReceiptRows', 'action' => 'add']) ?></li>
    </ul>
</nav>
<div class="referenceDetails form large-9 medium-8 columns content">
    <?= $this->Form->create($referenceDetail) ?>
    <fieldset>
        <legend><?= __('Add Reference Detail') ?></legend>
        <?php
            echo $this->Form->control('company_id', ['options' => $companies]);
            echo $this->Form->control('ledger_id', ['options' => $ledgers]);
            echo $this->Form->control('type');
            echo $this->Form->control('name');
            echo $this->Form->control('debit');
            echo $this->Form->control('credit');
            echo $this->Form->control('receipt_id', ['options' => $receipts]);
            echo $this->Form->control('receipt_row_id', ['options' => $receiptRows]);
            echo $this->Form->control('payment_id');
            echo $this->Form->control('payment_row_id');
        ?>
    </fieldset>
    <?= $this->Form->button(__('Submit')) ?>
    <?= $this->Form->end() ?>
</div>
