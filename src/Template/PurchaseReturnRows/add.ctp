<?php
/**
  * @var \App\View\AppView $this
  */
?>
<nav class="large-3 medium-4 columns" id="actions-sidebar">
    <ul class="side-nav">
        <li class="heading"><?= __('Actions') ?></li>
        <li><?= $this->Html->link(__('List Purchase Return Rows'), ['action' => 'index']) ?></li>
        <li><?= $this->Html->link(__('List Purchase Returns'), ['controller' => 'PurchaseReturns', 'action' => 'index']) ?></li>
        <li><?= $this->Html->link(__('New Purchase Return'), ['controller' => 'PurchaseReturns', 'action' => 'add']) ?></li>
        <li><?= $this->Html->link(__('List Items'), ['controller' => 'Items', 'action' => 'index']) ?></li>
        <li><?= $this->Html->link(__('New Item'), ['controller' => 'Items', 'action' => 'add']) ?></li>
    </ul>
</nav>
<div class="purchaseReturnRows form large-9 medium-8 columns content">
    <?= $this->Form->create($purchaseReturnRow) ?>
    <fieldset>
        <legend><?= __('Add Purchase Return Row') ?></legend>
        <?php
            echo $this->Form->control('purchase_return_id', ['options' => $purchaseReturns]);
            echo $this->Form->control('item_id', ['options' => $items]);
            echo $this->Form->control('quantity');
            echo $this->Form->control('discount_percentage');
            echo $this->Form->control('discount_amount');
            echo $this->Form->control('pnf_percentage');
            echo $this->Form->control('pnf_amount');
            echo $this->Form->control('taxable_value');
            echo $this->Form->control('item_gst_figure_id');
            echo $this->Form->control('gst_value');
            echo $this->Form->control('round_off');
            echo $this->Form->control('net_amount');
        ?>
    </fieldset>
    <?= $this->Form->button(__('Submit')) ?>
    <?= $this->Form->end() ?>
</div>
