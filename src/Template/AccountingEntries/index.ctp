<?php
/**
  * @var \App\View\AppView $this
  * @var \App\Model\Entity\AccountingEntry[]|\Cake\Collection\CollectionInterface $accountingEntries
  */
?>
<nav class="large-3 medium-4 columns" id="actions-sidebar">
    <ul class="side-nav">
        <li class="heading"><?= __('Actions') ?></li>
        <li><?= $this->Html->link(__('New Accounting Entry'), ['action' => 'add']) ?></li>
        <li><?= $this->Html->link(__('List Ledgers'), ['controller' => 'Ledgers', 'action' => 'index']) ?></li>
        <li><?= $this->Html->link(__('New Ledger'), ['controller' => 'Ledgers', 'action' => 'add']) ?></li>
        <li><?= $this->Html->link(__('List Companies'), ['controller' => 'Companies', 'action' => 'index']) ?></li>
        <li><?= $this->Html->link(__('New Company'), ['controller' => 'Companies', 'action' => 'add']) ?></li>
        <li><?= $this->Html->link(__('List Purchase Vouchers'), ['controller' => 'PurchaseVouchers', 'action' => 'index']) ?></li>
        <li><?= $this->Html->link(__('New Purchase Voucher'), ['controller' => 'PurchaseVouchers', 'action' => 'add']) ?></li>
        <li><?= $this->Html->link(__('List Sales Invoices'), ['controller' => 'SalesInvoices', 'action' => 'index']) ?></li>
        <li><?= $this->Html->link(__('New Sales Invoice'), ['controller' => 'SalesInvoices', 'action' => 'add']) ?></li>
    </ul>
</nav>
<div class="accountingEntries index large-9 medium-8 columns content">
    <h3><?= __('Accounting Entries') ?></h3>
    <table cellpadding="0" cellspacing="0">
        <thead>
            <tr>
                <th scope="col"><?= $this->Paginator->sort('id') ?></th>
                <th scope="col"><?= $this->Paginator->sort('ledger_id') ?></th>
                <th scope="col"><?= $this->Paginator->sort('debit') ?></th>
                <th scope="col"><?= $this->Paginator->sort('credit') ?></th>
                <th scope="col"><?= $this->Paginator->sort('transaction_date') ?></th>
                <th scope="col"><?= $this->Paginator->sort('company_id') ?></th>
                <th scope="col"><?= $this->Paginator->sort('purchase_voucher_id') ?></th>
                <th scope="col"><?= $this->Paginator->sort('purchase_voucher_row_id') ?></th>
                <th scope="col"><?= $this->Paginator->sort('is_opening_balance') ?></th>
                <th scope="col"><?= $this->Paginator->sort('sales_invoice_id') ?></th>
                <th scope="col"><?= $this->Paginator->sort('credit_note_id') ?></th>
                <th scope="col" class="actions"><?= __('Actions') ?></th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($accountingEntries as $accountingEntry): ?>
            <tr>
                <td><?= $this->Number->format($accountingEntry->id) ?></td>
                <td><?= $accountingEntry->has('ledger') ? $this->Html->link($accountingEntry->ledger->name, ['controller' => 'Ledgers', 'action' => 'view', $accountingEntry->ledger->id]) : '' ?></td>
                <td><?= $this->Number->format($accountingEntry->debit) ?></td>
                <td><?= $this->Number->format($accountingEntry->credit) ?></td>
                <td><?= h($accountingEntry->transaction_date) ?></td>
                <td><?= $accountingEntry->has('company') ? $this->Html->link($accountingEntry->company->name, ['controller' => 'Companies', 'action' => 'view', $accountingEntry->company->id]) : '' ?></td>
                <td><?= $accountingEntry->has('purchase_voucher') ? $this->Html->link($accountingEntry->purchase_voucher->id, ['controller' => 'PurchaseVouchers', 'action' => 'view', $accountingEntry->purchase_voucher->id]) : '' ?></td>
                <td><?= $this->Number->format($accountingEntry->purchase_voucher_row_id) ?></td>
                <td><?= h($accountingEntry->is_opening_balance) ?></td>
                <td><?= $accountingEntry->has('sales_invoice') ? $this->Html->link($accountingEntry->sales_invoice->id, ['controller' => 'SalesInvoices', 'action' => 'view', $accountingEntry->sales_invoice->id]) : '' ?></td>
                <td><?= $this->Number->format($accountingEntry->credit_note_id) ?></td>
                <td class="actions">
                    <?= $this->Html->link(__('View'), ['action' => 'view', $accountingEntry->id]) ?>
                    <?= $this->Html->link(__('Edit'), ['action' => 'edit', $accountingEntry->id]) ?>
                    <?= $this->Form->postLink(__('Delete'), ['action' => 'delete', $accountingEntry->id], ['confirm' => __('Are you sure you want to delete # {0}?', $accountingEntry->id)]) ?>
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
