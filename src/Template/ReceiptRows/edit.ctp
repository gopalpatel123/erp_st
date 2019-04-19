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
                ['action' => 'delete', $receiptRow->id],
                ['confirm' => __('Are you sure you want to delete # {0}?', $receiptRow->id)]
            )
        ?></li>
        <li><?= $this->Html->link(__('List Receipt Rows'), ['action' => 'index']) ?></li>
        <li><?= $this->Html->link(__('List Receipts'), ['controller' => 'Receipts', 'action' => 'index']) ?></li>
        <li><?= $this->Html->link(__('New Receipt'), ['controller' => 'Receipts', 'action' => 'add']) ?></li>
        <li><?= $this->Html->link(__('List Companies'), ['controller' => 'Companies', 'action' => 'index']) ?></li>
        <li><?= $this->Html->link(__('New Company'), ['controller' => 'Companies', 'action' => 'add']) ?></li>
        <li><?= $this->Html->link(__('List Ledgers'), ['controller' => 'Ledgers', 'action' => 'index']) ?></li>
        <li><?= $this->Html->link(__('New Ledger'), ['controller' => 'Ledgers', 'action' => 'add']) ?></li>
        <li><?= $this->Html->link(__('List Reference Details'), ['controller' => 'ReferenceDetails', 'action' => 'index']) ?></li>
        <li><?= $this->Html->link(__('New Reference Detail'), ['controller' => 'ReferenceDetails', 'action' => 'add']) ?></li>
    </ul>
</nav>
<div class="receiptRows form large-9 medium-8 columns content">
    <?= $this->Form->create($receiptRow) ?>
    <fieldset>
        <legend><?= __('Edit Receipt Row') ?></legend>
        <?php
            echo $this->Form->control('receipt_id', ['options' => $receipts]);
            echo $this->Form->control('company_id', ['options' => $companies]);
            echo $this->Form->control('type');
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
