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
                ['action' => 'delete', $saleReturnRow->id],
                ['confirm' => __('Are you sure you want to delete # {0}?', $saleReturnRow->id)]
            )
        ?></li>
        <li><?= $this->Html->link(__('List Sale Return Rows'), ['action' => 'index']) ?></li>
        <li><?= $this->Html->link(__('List Sale Returns'), ['controller' => 'SaleReturns', 'action' => 'index']) ?></li>
        <li><?= $this->Html->link(__('New Sale Return'), ['controller' => 'SaleReturns', 'action' => 'add']) ?></li>
        <li><?= $this->Html->link(__('List Items'), ['controller' => 'Items', 'action' => 'index']) ?></li>
        <li><?= $this->Html->link(__('New Item'), ['controller' => 'Items', 'action' => 'add']) ?></li>
        <li><?= $this->Html->link(__('List Gst Figures'), ['controller' => 'GstFigures', 'action' => 'index']) ?></li>
        <li><?= $this->Html->link(__('New Gst Figure'), ['controller' => 'GstFigures', 'action' => 'add']) ?></li>
    </ul>
</nav>
<div class="saleReturnRows form large-9 medium-8 columns content">
    <?= $this->Form->create($saleReturnRow) ?>
    <fieldset>
        <legend><?= __('Edit Sale Return Row') ?></legend>
        <?php
            echo $this->Form->control('sale_return_id', ['options' => $saleReturns]);
            echo $this->Form->control('item_id', ['options' => $items]);
            echo $this->Form->control('quantity');
            echo $this->Form->control('rate');
            echo $this->Form->control('discount_percentage');
            echo $this->Form->control('taxable_value');
            echo $this->Form->control('net_amount');
            echo $this->Form->control('gst_figure_id', ['options' => $gstFigures]);
            echo $this->Form->control('gst_value');
        ?>
    </fieldset>
    <?= $this->Form->button(__('Submit')) ?>
    <?= $this->Form->end() ?>
</div>
