<?php
/**
  * @var \App\View\AppView $this
  * @var \App\Model\Entity\Ledger $ledger
  */
?>
<nav class="large-3 medium-4 columns" id="actions-sidebar">
    <ul class="side-nav">
        <li class="heading"><?= __('Actions') ?></li>
        <li><?= $this->Html->link(__('Edit Ledger'), ['action' => 'edit', $ledger->id]) ?> </li>
        <li><?= $this->Form->postLink(__('Delete Ledger'), ['action' => 'delete', $ledger->id], ['confirm' => __('Are you sure you want to delete # {0}?', $ledger->id)]) ?> </li>
        <li><?= $this->Html->link(__('List Ledgers'), ['action' => 'index']) ?> </li>
        <li><?= $this->Html->link(__('New Ledger'), ['action' => 'add']) ?> </li>
        <li><?= $this->Html->link(__('List Accounting Groups'), ['controller' => 'AccountingGroups', 'action' => 'index']) ?> </li>
        <li><?= $this->Html->link(__('New Accounting Group'), ['controller' => 'AccountingGroups', 'action' => 'add']) ?> </li>
        <li><?= $this->Html->link(__('List Companies'), ['controller' => 'Companies', 'action' => 'index']) ?> </li>
        <li><?= $this->Html->link(__('New Company'), ['controller' => 'Companies', 'action' => 'add']) ?> </li>
        <li><?= $this->Html->link(__('List Suppliers'), ['controller' => 'Suppliers', 'action' => 'index']) ?> </li>
        <li><?= $this->Html->link(__('New Supplier'), ['controller' => 'Suppliers', 'action' => 'add']) ?> </li>
        <li><?= $this->Html->link(__('List Customers'), ['controller' => 'Customers', 'action' => 'index']) ?> </li>
        <li><?= $this->Html->link(__('New Customer'), ['controller' => 'Customers', 'action' => 'add']) ?> </li>
        <li><?= $this->Html->link(__('List Accounting Entries'), ['controller' => 'AccountingEntries', 'action' => 'index']) ?> </li>
        <li><?= $this->Html->link(__('New Accounting Entry'), ['controller' => 'AccountingEntries', 'action' => 'add']) ?> </li>
    </ul>
</nav>
<div class="ledgers view large-9 medium-8 columns content">
    <h3><?= h($ledger->name) ?></h3>
    <table class="vertical-table">
        <tr>
            <th scope="row"><?= __('Name') ?></th>
            <td><?= h($ledger->name) ?></td>
        </tr>
        <tr>
            <th scope="row"><?= __('Accounting Group') ?></th>
            <td><?= $ledger->has('accounting_group') ? $this->Html->link($ledger->accounting_group->name, ['controller' => 'AccountingGroups', 'action' => 'view', $ledger->accounting_group->id]) : '' ?></td>
        </tr>
        <tr>
            <th scope="row"><?= __('Company') ?></th>
            <td><?= $ledger->has('company') ? $this->Html->link($ledger->company->name, ['controller' => 'Companies', 'action' => 'view', $ledger->company->id]) : '' ?></td>
        </tr>
        <tr>
            <th scope="row"><?= __('Supplier') ?></th>
            <td><?= $ledger->has('supplier') ? $this->Html->link($ledger->supplier->name, ['controller' => 'Suppliers', 'action' => 'view', $ledger->supplier->id]) : '' ?></td>
        </tr>
        <tr>
            <th scope="row"><?= __('Customer') ?></th>
            <td><?= $ledger->has('customer') ? $this->Html->link($ledger->customer->name, ['controller' => 'Customers', 'action' => 'view', $ledger->customer->id]) : '' ?></td>
        </tr>
        <tr>
            <th scope="row"><?= __('Gst Type') ?></th>
            <td><?= h($ledger->gst_type) ?></td>
        </tr>
        <tr>
            <th scope="row"><?= __('Id') ?></th>
            <td><?= $this->Number->format($ledger->id) ?></td>
        </tr>
        <tr>
            <th scope="row"><?= __('Tax Percentage') ?></th>
            <td><?= $this->Number->format($ledger->tax_percentage) ?></td>
        </tr>
        <tr>
            <th scope="row"><?= __('Freeze') ?></th>
            <td><?= $ledger->freeze ? __('Yes') : __('No'); ?></td>
        </tr>
    </table>
    <div class="related">
        <h4><?= __('Related Accounting Entries') ?></h4>
        <?php if (!empty($ledger->accounting_entries)): ?>
        <table cellpadding="0" cellspacing="0">
            <tr>
                <th scope="col"><?= __('Id') ?></th>
                <th scope="col"><?= __('Ledger Id') ?></th>
                <th scope="col"><?= __('Debit') ?></th>
                <th scope="col"><?= __('Credit') ?></th>
                <th scope="col"><?= __('Transaction Date') ?></th>
                <th scope="col"><?= __('Company Id') ?></th>
                <th scope="col" class="actions"><?= __('Actions') ?></th>
            </tr>
            <?php foreach ($ledger->accounting_entries as $accountingEntries): ?>
            <tr>
                <td><?= h($accountingEntries->id) ?></td>
                <td><?= h($accountingEntries->ledger_id) ?></td>
                <td><?= h($accountingEntries->debit) ?></td>
                <td><?= h($accountingEntries->credit) ?></td>
                <td><?= h($accountingEntries->transaction_date) ?></td>
                <td><?= h($accountingEntries->company_id) ?></td>
                <td class="actions">
                    <?= $this->Html->link(__('View'), ['controller' => 'AccountingEntries', 'action' => 'view', $accountingEntries->id]) ?>
                    <?= $this->Html->link(__('Edit'), ['controller' => 'AccountingEntries', 'action' => 'edit', $accountingEntries->id]) ?>
                    <?= $this->Form->postLink(__('Delete'), ['controller' => 'AccountingEntries', 'action' => 'delete', $accountingEntries->id], ['confirm' => __('Are you sure you want to delete # {0}?', $accountingEntries->id)]) ?>
                </td>
            </tr>
            <?php endforeach; ?>
        </table>
        <?php endif; ?>
    </div>
</div>
