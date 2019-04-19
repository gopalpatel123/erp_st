<?php
/**
  * @var \App\View\AppView $this
  * @var \App\Model\Entity\SalesInvoiceRow[]|\Cake\Collection\CollectionInterface $salesInvoiceRows
  */
?>
<nav class="large-3 medium-4 columns" id="actions-sidebar">
    <ul class="side-nav">
        <li class="heading"><?= __('Actions') ?></li>
        <li><?= $this->Html->link(__('New Sales Invoice Row'), ['action' => 'add']) ?></li>
        <li><?= $this->Html->link(__('List Sales Invoices'), ['controller' => 'SalesInvoices', 'action' => 'index']) ?></li>
        <li><?= $this->Html->link(__('New Sales Invoice'), ['controller' => 'SalesInvoices', 'action' => 'add']) ?></li>
        <li><?= $this->Html->link(__('List Items'), ['controller' => 'Items', 'action' => 'index']) ?></li>
        <li><?= $this->Html->link(__('New Item'), ['controller' => 'Items', 'action' => 'add']) ?></li>
        <li><?= $this->Html->link(__('List Gst Figures'), ['controller' => 'GstFigures', 'action' => 'index']) ?></li>
        <li><?= $this->Html->link(__('New Gst Figure'), ['controller' => 'GstFigures', 'action' => 'add']) ?></li>
    </ul>
</nav>
<div class="salesInvoiceRows index large-9 medium-8 columns content">
    <h3><?= __('Sales Invoice Rows') ?></h3>
    <table cellpadding="0" cellspacing="0">
        <thead>
            <tr>
                <th scope="col"><?= $this->Paginator->sort('id') ?></th>
                <th scope="col"><?= $this->Paginator->sort('sales_invoice_id') ?></th>
                <th scope="col"><?= $this->Paginator->sort('item_id') ?></th>
                <th scope="col"><?= $this->Paginator->sort('quantity') ?></th>
                <th scope="col"><?= $this->Paginator->sort('rate') ?></th>
                <th scope="col"><?= $this->Paginator->sort('discount_percentage') ?></th>
                <th scope="col"><?= $this->Paginator->sort('taxable_value') ?></th>
                <th scope="col"><?= $this->Paginator->sort('gst_figure_id') ?></th>
                <th scope="col"><?= $this->Paginator->sort('output_cgst_ledger_id') ?></th>
                <th scope="col"><?= $this->Paginator->sort('output_sgst_ledger_id') ?></th>
                <th scope="col"><?= $this->Paginator->sort('output_igst_ledger_id') ?></th>
                <th scope="col" class="actions"><?= __('Actions') ?></th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($salesInvoiceRows as $salesInvoiceRow): ?>
            <tr>
                <td><?= $this->Number->format($salesInvoiceRow->id) ?></td>
                <td><?= $salesInvoiceRow->has('sales_invoice') ? $this->Html->link($salesInvoiceRow->sales_invoice->id, ['controller' => 'SalesInvoices', 'action' => 'view', $salesInvoiceRow->sales_invoice->id]) : '' ?></td>
                <td><?= $salesInvoiceRow->has('item') ? $this->Html->link($salesInvoiceRow->item->name, ['controller' => 'Items', 'action' => 'view', $salesInvoiceRow->item->id]) : '' ?></td>
                <td><?= $this->Number->format($salesInvoiceRow->quantity) ?></td>
                <td><?= $this->Number->format($salesInvoiceRow->rate) ?></td>
                <td><?= $this->Number->format($salesInvoiceRow->discount_percentage) ?></td>
                <td><?= $this->Number->format($salesInvoiceRow->taxable_value) ?></td>
                <td><?= $salesInvoiceRow->has('gst_figure') ? $this->Html->link($salesInvoiceRow->gst_figure->name, ['controller' => 'GstFigures', 'action' => 'view', $salesInvoiceRow->gst_figure->id]) : '' ?></td>
                <td><?= $this->Number->format($salesInvoiceRow->output_cgst_ledger_id) ?></td>
                <td><?= $this->Number->format($salesInvoiceRow->output_sgst_ledger_id) ?></td>
                <td><?= $this->Number->format($salesInvoiceRow->output_igst_ledger_id) ?></td>
                <td class="actions">
                    <?= $this->Html->link(__('View'), ['action' => 'view', $salesInvoiceRow->id]) ?>
                    <?= $this->Html->link(__('Edit'), ['action' => 'edit', $salesInvoiceRow->id]) ?>
                    <?= $this->Form->postLink(__('Delete'), ['action' => 'delete', $salesInvoiceRow->id], ['confirm' => __('Are you sure you want to delete # {0}?', $salesInvoiceRow->id)]) ?>
                </td>
            </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
    <div class="paginator">
        <ul class="pagination">
            <?= $this->Paginator->first('<< ' . __('first')) ?>
            <?= $this->Paginator->prev('< ' . __('previous')) ?>
            <?= $this->Paginator->numbers() ?>
            <?= $this->Paginator->next(__('next') . ' >') ?>
            <?= $this->Paginator->last(__('last') . ' >>') ?>
        </ul>
        <p><?= $this->Paginator->counter(['format' => __('Page {{page}} of {{pages}}, showing {{current}} record(s) out of {{count}} total')]) ?></p>
    </div>
</div>
