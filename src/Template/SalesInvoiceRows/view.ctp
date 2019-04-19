<?php
/**
  * @var \App\View\AppView $this
  * @var \App\Model\Entity\SalesInvoiceRow $salesInvoiceRow
  */
?>
<nav class="large-3 medium-4 columns" id="actions-sidebar">
    <ul class="side-nav">
        <li class="heading"><?= __('Actions') ?></li>
        <li><?= $this->Html->link(__('Edit Sales Invoice Row'), ['action' => 'edit', $salesInvoiceRow->id]) ?> </li>
        <li><?= $this->Form->postLink(__('Delete Sales Invoice Row'), ['action' => 'delete', $salesInvoiceRow->id], ['confirm' => __('Are you sure you want to delete # {0}?', $salesInvoiceRow->id)]) ?> </li>
        <li><?= $this->Html->link(__('List Sales Invoice Rows'), ['action' => 'index']) ?> </li>
        <li><?= $this->Html->link(__('New Sales Invoice Row'), ['action' => 'add']) ?> </li>
        <li><?= $this->Html->link(__('List Sales Invoices'), ['controller' => 'SalesInvoices', 'action' => 'index']) ?> </li>
        <li><?= $this->Html->link(__('New Sales Invoice'), ['controller' => 'SalesInvoices', 'action' => 'add']) ?> </li>
        <li><?= $this->Html->link(__('List Items'), ['controller' => 'Items', 'action' => 'index']) ?> </li>
        <li><?= $this->Html->link(__('New Item'), ['controller' => 'Items', 'action' => 'add']) ?> </li>
        <li><?= $this->Html->link(__('List Gst Figures'), ['controller' => 'GstFigures', 'action' => 'index']) ?> </li>
        <li><?= $this->Html->link(__('New Gst Figure'), ['controller' => 'GstFigures', 'action' => 'add']) ?> </li>
    </ul>
</nav>
<div class="salesInvoiceRows view large-9 medium-8 columns content">
    <h3><?= h($salesInvoiceRow->id) ?></h3>
    <table class="vertical-table">
        <tr>
            <th scope="row"><?= __('Sales Invoice') ?></th>
            <td><?= $salesInvoiceRow->has('sales_invoice') ? $this->Html->link($salesInvoiceRow->sales_invoice->id, ['controller' => 'SalesInvoices', 'action' => 'view', $salesInvoiceRow->sales_invoice->id]) : '' ?></td>
        </tr>
        <tr>
            <th scope="row"><?= __('Item') ?></th>
            <td><?= $salesInvoiceRow->has('item') ? $this->Html->link($salesInvoiceRow->item->name, ['controller' => 'Items', 'action' => 'view', $salesInvoiceRow->item->id]) : '' ?></td>
        </tr>
        <tr>
            <th scope="row"><?= __('Gst Figure') ?></th>
            <td><?= $salesInvoiceRow->has('gst_figure') ? $this->Html->link($salesInvoiceRow->gst_figure->name, ['controller' => 'GstFigures', 'action' => 'view', $salesInvoiceRow->gst_figure->id]) : '' ?></td>
        </tr>
        <tr>
            <th scope="row"><?= __('Id') ?></th>
            <td><?= $this->Number->format($salesInvoiceRow->id) ?></td>
        </tr>
        <tr>
            <th scope="row"><?= __('Quantity') ?></th>
            <td><?= $this->Number->format($salesInvoiceRow->quantity) ?></td>
        </tr>
        <tr>
            <th scope="row"><?= __('Rate') ?></th>
            <td><?= $this->Number->format($salesInvoiceRow->rate) ?></td>
        </tr>
        <tr>
            <th scope="row"><?= __('Discount Percentage') ?></th>
            <td><?= $this->Number->format($salesInvoiceRow->discount_percentage) ?></td>
        </tr>
        <tr>
            <th scope="row"><?= __('Taxable Value') ?></th>
            <td><?= $this->Number->format($salesInvoiceRow->taxable_value) ?></td>
        </tr>
        <tr>
            <th scope="row"><?= __('Output Cgst Ledger Id') ?></th>
            <td><?= $this->Number->format($salesInvoiceRow->output_cgst_ledger_id) ?></td>
        </tr>
        <tr>
            <th scope="row"><?= __('Output Sgst Ledger Id') ?></th>
            <td><?= $this->Number->format($salesInvoiceRow->output_sgst_ledger_id) ?></td>
        </tr>
        <tr>
            <th scope="row"><?= __('Output Igst Ledger Id') ?></th>
            <td><?= $this->Number->format($salesInvoiceRow->output_igst_ledger_id) ?></td>
        </tr>
    </table>
</div>
