<?php
/**
  * @var \App\View\AppView $this
  * @var \App\Model\Entity\SalesInvoice $salesInvoice
  */
?>
<nav class="large-3 medium-4 columns" id="actions-sidebar">
    <ul class="side-nav">
        <li class="heading"><?= __('Actions') ?></li>
        <li><?= $this->Html->link(__('Edit Sales Invoice'), ['action' => 'edit', $salesInvoice->id]) ?> </li>
        <li><?= $this->Form->postLink(__('Delete Sales Invoice'), ['action' => 'delete', $salesInvoice->id], ['confirm' => __('Are you sure you want to delete # {0}?', $salesInvoice->id)]) ?> </li>
        <li><?= $this->Html->link(__('List Sales Invoices'), ['action' => 'index']) ?> </li>
        <li><?= $this->Html->link(__('New Sales Invoice'), ['action' => 'add']) ?> </li>
        <li><?= $this->Html->link(__('List Companies'), ['controller' => 'Companies', 'action' => 'index']) ?> </li>
        <li><?= $this->Html->link(__('New Company'), ['controller' => 'Companies', 'action' => 'add']) ?> </li>
        <li><?= $this->Html->link(__('List Customers'), ['controller' => 'Customers', 'action' => 'index']) ?> </li>
        <li><?= $this->Html->link(__('New Customer'), ['controller' => 'Customers', 'action' => 'add']) ?> </li>
        <li><?= $this->Html->link(__('List Gst Figures'), ['controller' => 'GstFigures', 'action' => 'index']) ?> </li>
        <li><?= $this->Html->link(__('New Gst Figure'), ['controller' => 'GstFigures', 'action' => 'add']) ?> </li>
        <li><?= $this->Html->link(__('List Sales Invoice Rows'), ['controller' => 'SalesInvoiceRows', 'action' => 'index']) ?> </li>
        <li><?= $this->Html->link(__('New Sales Invoice Row'), ['controller' => 'SalesInvoiceRows', 'action' => 'add']) ?> </li>
    </ul>
</nav>
<div class="salesInvoices view large-9 medium-8 columns content">
    <h3><?= h($salesInvoice->id) ?></h3>
    <table class="vertical-table">
        <tr>
            <th scope="row"><?= __('Company') ?></th>
            <td><?= $salesInvoice->has('company') ? $this->Html->link($salesInvoice->company->name, ['controller' => 'Companies', 'action' => 'view', $salesInvoice->company->id]) : '' ?></td>
        </tr>
        <tr>
            <th scope="row"><?= __('Cash Or Credit') ?></th>
            <td><?= h($salesInvoice->cash_or_credit) ?></td>
        </tr>
        <tr>
            <th scope="row"><?= __('Customer') ?></th>
            <td><?= $salesInvoice->has('customer') ? $this->Html->link($salesInvoice->customer->name, ['controller' => 'Customers', 'action' => 'view', $salesInvoice->customer->id]) : '' ?></td>
        </tr>
        <tr>
            <th scope="row"><?= __('Gst Figure') ?></th>
            <td><?= $salesInvoice->has('gst_figure') ? $this->Html->link($salesInvoice->gst_figure->name, ['controller' => 'GstFigures', 'action' => 'view', $salesInvoice->gst_figure->id]) : '' ?></td>
        </tr>
        <tr>
            <th scope="row"><?= __('Id') ?></th>
            <td><?= $this->Number->format($salesInvoice->id) ?></td>
        </tr>
        <tr>
            <th scope="row"><?= __('Voucher No') ?></th>
            <td><?= $this->Number->format($salesInvoice->voucher_no) ?></td>
        </tr>
        <tr>
            <th scope="row"><?= __('Amount Before Tax') ?></th>
            <td><?= $this->Number->format($salesInvoice->amount_before_tax) ?></td>
        </tr>
        <tr>
            <th scope="row"><?= __('Total Cgst') ?></th>
            <td><?= $this->Number->format($salesInvoice->total_cgst) ?></td>
        </tr>
        <tr>
            <th scope="row"><?= __('Total Sgst') ?></th>
            <td><?= $this->Number->format($salesInvoice->total_sgst) ?></td>
        </tr>
        <tr>
            <th scope="row"><?= __('Total Igst') ?></th>
            <td><?= $this->Number->format($salesInvoice->total_igst) ?></td>
        </tr>
        <tr>
            <th scope="row"><?= __('Amount After Tax') ?></th>
            <td><?= $this->Number->format($salesInvoice->amount_after_tax) ?></td>
        </tr>
        <tr>
            <th scope="row"><?= __('Transaction Date') ?></th>
            <td><?= h($salesInvoice->transaction_date) ?></td>
        </tr>
    </table>
    <div class="related">
        <h4><?= __('Related Sales Invoice Rows') ?></h4>
        <?php if (!empty($salesInvoice->sales_invoice_rows)): ?>
        <table cellpadding="0" cellspacing="0">
            <tr>
                <th scope="col"><?= __('Id') ?></th>
                <th scope="col"><?= __('Sales Invoice Id') ?></th>
                <th scope="col"><?= __('Item Id') ?></th>
                <th scope="col"><?= __('Quantity') ?></th>
                <th scope="col"><?= __('Rate') ?></th>
                <th scope="col"><?= __('Discount Percentage') ?></th>
                <th scope="col"><?= __('Taxable Value') ?></th>
                <th scope="col"><?= __('Gst Figure Id') ?></th>
                <th scope="col"><?= __('Output Cgst Ledger Id') ?></th>
                <th scope="col"><?= __('Output Sgst Ledger Id') ?></th>
                <th scope="col"><?= __('Output Igst Ledger Id') ?></th>
                <th scope="col" class="actions"><?= __('Actions') ?></th>
            </tr>
            <?php foreach ($salesInvoice->sales_invoice_rows as $salesInvoiceRows): ?>
            <tr>
                <td><?= h($salesInvoiceRows->id) ?></td>
                <td><?= h($salesInvoiceRows->sales_invoice_id) ?></td>
                <td><?= h($salesInvoiceRows->item_id) ?></td>
                <td><?= h($salesInvoiceRows->quantity) ?></td>
                <td><?= h($salesInvoiceRows->rate) ?></td>
                <td><?= h($salesInvoiceRows->discount_percentage) ?></td>
                <td><?= h($salesInvoiceRows->taxable_value) ?></td>
                <td><?= h($salesInvoiceRows->gst_figure_id) ?></td>
                <td><?= h($salesInvoiceRows->output_cgst_ledger_id) ?></td>
                <td><?= h($salesInvoiceRows->output_sgst_ledger_id) ?></td>
                <td><?= h($salesInvoiceRows->output_igst_ledger_id) ?></td>
                <td class="actions">
                    <?= $this->Html->link(__('View'), ['controller' => 'SalesInvoiceRows', 'action' => 'view', $salesInvoiceRows->id]) ?>
                    <?= $this->Html->link(__('Edit'), ['controller' => 'SalesInvoiceRows', 'action' => 'edit', $salesInvoiceRows->id]) ?>
                    <?= $this->Form->postLink(__('Delete'), ['controller' => 'SalesInvoiceRows', 'action' => 'delete', $salesInvoiceRows->id], ['confirm' => __('Are you sure you want to delete # {0}?', $salesInvoiceRows->id)]) ?>
                </td>
            </tr>
            <?php endforeach; ?>
        </table>
        <?php endif; ?>
    </div>
</div>
