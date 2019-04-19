<?php
/**
  * @var \App\View\AppView $this
  * @var \App\Model\Entity\PurchaseInvoiceRow $purchaseInvoiceRow
  */
?>
<nav class="large-3 medium-4 columns" id="actions-sidebar">
    <ul class="side-nav">
        <li class="heading"><?= __('Actions') ?></li>
        <li><?= $this->Html->link(__('Edit Purchase Invoice Row'), ['action' => 'edit', $purchaseInvoiceRow->id]) ?> </li>
        <li><?= $this->Form->postLink(__('Delete Purchase Invoice Row'), ['action' => 'delete', $purchaseInvoiceRow->id], ['confirm' => __('Are you sure you want to delete # {0}?', $purchaseInvoiceRow->id)]) ?> </li>
        <li><?= $this->Html->link(__('List Purchase Invoice Rows'), ['action' => 'index']) ?> </li>
        <li><?= $this->Html->link(__('New Purchase Invoice Row'), ['action' => 'add']) ?> </li>
        <li><?= $this->Html->link(__('List Purchase Invoices'), ['controller' => 'PurchaseInvoices', 'action' => 'index']) ?> </li>
        <li><?= $this->Html->link(__('New Purchase Invoice'), ['controller' => 'PurchaseInvoices', 'action' => 'add']) ?> </li>
        <li><?= $this->Html->link(__('List Items'), ['controller' => 'Items', 'action' => 'index']) ?> </li>
        <li><?= $this->Html->link(__('New Item'), ['controller' => 'Items', 'action' => 'add']) ?> </li>
        <li><?= $this->Html->link(__('List Gst Figures'), ['controller' => 'GstFigures', 'action' => 'index']) ?> </li>
        <li><?= $this->Html->link(__('New Gst Figure'), ['controller' => 'GstFigures', 'action' => 'add']) ?> </li>
    </ul>
</nav>
<div class="purchaseInvoiceRows view large-9 medium-8 columns content">
    <h3><?= h($purchaseInvoiceRow->id) ?></h3>
    <table class="vertical-table">
        <tr>
            <th scope="row"><?= __('Purchase Invoice') ?></th>
            <td><?= $purchaseInvoiceRow->has('purchase_invoice') ? $this->Html->link($purchaseInvoiceRow->purchase_invoice->id, ['controller' => 'PurchaseInvoices', 'action' => 'view', $purchaseInvoiceRow->purchase_invoice->id]) : '' ?></td>
        </tr>
        <tr>
            <th scope="row"><?= __('Item') ?></th>
            <td><?= $purchaseInvoiceRow->has('item') ? $this->Html->link($purchaseInvoiceRow->item->name, ['controller' => 'Items', 'action' => 'view', $purchaseInvoiceRow->item->id]) : '' ?></td>
        </tr>
        <tr>
            <th scope="row"><?= __('Pnf Percentage') ?></th>
            <td><?= h($purchaseInvoiceRow->pnf_percentage) ?></td>
        </tr>
        <tr>
            <th scope="row"><?= __('Gst Figure') ?></th>
            <td><?= $purchaseInvoiceRow->has('gst_figure') ? $this->Html->link($purchaseInvoiceRow->gst_figure->name, ['controller' => 'GstFigures', 'action' => 'view', $purchaseInvoiceRow->gst_figure->id]) : '' ?></td>
        </tr>
        <tr>
            <th scope="row"><?= __('Id') ?></th>
            <td><?= $this->Number->format($purchaseInvoiceRow->id) ?></td>
        </tr>
        <tr>
            <th scope="row"><?= __('Quantity') ?></th>
            <td><?= $this->Number->format($purchaseInvoiceRow->quantity) ?></td>
        </tr>
        <tr>
            <th scope="row"><?= __('Rate') ?></th>
            <td><?= $this->Number->format($purchaseInvoiceRow->rate) ?></td>
        </tr>
        <tr>
            <th scope="row"><?= __('Discount Percentage') ?></th>
            <td><?= $this->Number->format($purchaseInvoiceRow->discount_percentage) ?></td>
        </tr>
        <tr>
            <th scope="row"><?= __('Discount Amount') ?></th>
            <td><?= $this->Number->format($purchaseInvoiceRow->discount_amount) ?></td>
        </tr>
        <tr>
            <th scope="row"><?= __('Pnf Amount') ?></th>
            <td><?= $this->Number->format($purchaseInvoiceRow->pnf_amount) ?></td>
        </tr>
        <tr>
            <th scope="row"><?= __('Taxable Value') ?></th>
            <td><?= $this->Number->format($purchaseInvoiceRow->taxable_value) ?></td>
        </tr>
        <tr>
            <th scope="row"><?= __('Net Amount') ?></th>
            <td><?= $this->Number->format($purchaseInvoiceRow->net_amount) ?></td>
        </tr>
        <tr>
            <th scope="row"><?= __('Gst Value') ?></th>
            <td><?= $this->Number->format($purchaseInvoiceRow->gst_value) ?></td>
        </tr>
    </table>
</div>
