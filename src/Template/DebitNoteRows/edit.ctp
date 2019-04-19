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
                ['action' => 'delete', $debitNoteRow->id],
                ['confirm' => __('Are you sure you want to delete # {0}?', $debitNoteRow->id)]
            )
        ?></li>
        <li><?= $this->Html->link(__('List Debit Note Rows'), ['action' => 'index']) ?></li>
        <li><?= $this->Html->link(__('List Credit Notes'), ['controller' => 'CreditNotes', 'action' => 'index']) ?></li>
        <li><?= $this->Html->link(__('New Credit Note'), ['controller' => 'CreditNotes', 'action' => 'add']) ?></li>
        <li><?= $this->Html->link(__('List Ledgers'), ['controller' => 'Ledgers', 'action' => 'index']) ?></li>
        <li><?= $this->Html->link(__('New Ledger'), ['controller' => 'Ledgers', 'action' => 'add']) ?></li>
        <li><?= $this->Html->link(__('List Accounting Entries'), ['controller' => 'AccountingEntries', 'action' => 'index']) ?></li>
        <li><?= $this->Html->link(__('New Accounting Entry'), ['controller' => 'AccountingEntries', 'action' => 'add']) ?></li>
        <li><?= $this->Html->link(__('List Reference Details'), ['controller' => 'ReferenceDetails', 'action' => 'index']) ?></li>
        <li><?= $this->Html->link(__('New Reference Detail'), ['controller' => 'ReferenceDetails', 'action' => 'add']) ?></li>
    </ul>
</nav>
<div class="debitNoteRows form large-9 medium-8 columns content">
    <?= $this->Form->create($debitNoteRow) ?>
    <fieldset>
        <legend><?= __('Edit Debit Note Row') ?></legend>
        <?php
            echo $this->Form->control('credit_note_id', ['options' => $creditNotes]);
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
