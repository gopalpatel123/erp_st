<?php
/**
  * @var \App\View\AppView $this
  */
?>
<nav class="large-3 medium-4 columns" id="actions-sidebar">
    <ul class="side-nav">
        <li class="heading"><?= __('Actions') ?></li>
        <li><?= $this->Html->link(__('List Sales Invoice Rows'), ['action' => 'index']) ?></li>
        <li><?= $this->Html->link(__('List Sales Invoices'), ['controller' => 'SalesInvoices', 'action' => 'index']) ?></li>
        <li><?= $this->Html->link(__('New Sales Invoice'), ['controller' => 'SalesInvoices', 'action' => 'add']) ?></li>
        <li><?= $this->Html->link(__('List Items'), ['controller' => 'Items', 'action' => 'index']) ?></li>
        <li><?= $this->Html->link(__('New Item'), ['controller' => 'Items', 'action' => 'add']) ?></li>
        <li><?= $this->Html->link(__('List Gst Figures'), ['controller' => 'GstFigures', 'action' => 'index']) ?></li>
        <li><?= $this->Html->link(__('New Gst Figure'), ['controller' => 'GstFigures', 'action' => 'add']) ?></li>
    </ul>
</nav>
<div class="salesInvoiceRows form large-9 medium-8 columns content">
    <?= $this->Form->create($salesInvoiceRow) ?>
    <fieldset>
        <legend><?= __('Add Sales Invoice Row') ?></legend>
        <?php
            echo $this->Form->control('sales_invoice_id', ['options' => $salesInvoices]);
            echo $this->Form->control('item_id', ['options' => $items]);
            echo $this->Form->control('quantity');
            echo $this->Form->control('rate');
            echo $this->Form->control('discount_percentage');
            echo $this->Form->control('taxable_value');
            echo $this->Form->control('gst_figure_id', ['options' => $gstFigures]);
            echo $this->Form->control('output_cgst_ledger_id');
            echo $this->Form->control('output_sgst_ledger_id');
            echo $this->Form->control('output_igst_ledger_id');
        ?>
    </fieldset>
    <?= $this->Form->button(__('Submit')) ?>
    <?= $this->Form->end() ?>
</div>
