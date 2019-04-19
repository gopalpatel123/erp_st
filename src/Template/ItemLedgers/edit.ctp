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
                ['action' => 'delete', $itemLedger->id],
                ['confirm' => __('Are you sure you want to delete # {0}?', $itemLedger->id)]
            )
        ?></li>
        <li><?= $this->Html->link(__('List Item Ledgers'), ['action' => 'index']) ?></li>
        <li><?= $this->Html->link(__('List Items'), ['controller' => 'Items', 'action' => 'index']) ?></li>
        <li><?= $this->Html->link(__('New Item'), ['controller' => 'Items', 'action' => 'add']) ?></li>
    </ul>
</nav>
<div class="itemLedgers form large-9 medium-8 columns content">
    <?= $this->Form->create($itemLedger) ?>
    <fieldset>
        <legend><?= __('Edit Item Ledger') ?></legend>
        <?php
            echo $this->Form->control('item_id', ['options' => $items]);
            echo $this->Form->control('transaction_date');
            echo $this->Form->control('quantity');
            echo $this->Form->control('rate');
            echo $this->Form->control('amount');
            echo $this->Form->control('status');
            echo $this->Form->control('is_opening_balance');
        ?>
    </fieldset>
    <?= $this->Form->button(__('Submit')) ?>
    <?= $this->Form->end() ?>
</div>
