<?php
/**
  * @var \App\View\AppView $this
  * @var \App\Model\Entity\JournalVoucherRow $journalVoucherRow
  */
?>
<nav class="large-3 medium-4 columns" id="actions-sidebar">
    <ul class="side-nav">
        <li class="heading"><?= __('Actions') ?></li>
        <li><?= $this->Html->link(__('Edit Journal Voucher Row'), ['action' => 'edit', $journalVoucherRow->id]) ?> </li>
        <li><?= $this->Form->postLink(__('Delete Journal Voucher Row'), ['action' => 'delete', $journalVoucherRow->id], ['confirm' => __('Are you sure you want to delete # {0}?', $journalVoucherRow->id)]) ?> </li>
        <li><?= $this->Html->link(__('List Journal Voucher Rows'), ['action' => 'index']) ?> </li>
        <li><?= $this->Html->link(__('New Journal Voucher Row'), ['action' => 'add']) ?> </li>
        <li><?= $this->Html->link(__('List Journal Vouchers'), ['controller' => 'JournalVouchers', 'action' => 'index']) ?> </li>
        <li><?= $this->Html->link(__('New Journal Voucher'), ['controller' => 'JournalVouchers', 'action' => 'add']) ?> </li>
        <li><?= $this->Html->link(__('List Ledgers'), ['controller' => 'Ledgers', 'action' => 'index']) ?> </li>
        <li><?= $this->Html->link(__('New Ledger'), ['controller' => 'Ledgers', 'action' => 'add']) ?> </li>
    </ul>
</nav>
<div class="journalVoucherRows view large-9 medium-8 columns content">
    <h3><?= h($journalVoucherRow->id) ?></h3>
    <table class="vertical-table">
        <tr>
            <th scope="row"><?= __('Journal Voucher') ?></th>
            <td><?= $journalVoucherRow->has('journal_voucher') ? $this->Html->link($journalVoucherRow->journal_voucher->id, ['controller' => 'JournalVouchers', 'action' => 'view', $journalVoucherRow->journal_voucher->id]) : '' ?></td>
        </tr>
        <tr>
            <th scope="row"><?= __('Cr Dr') ?></th>
            <td><?= h($journalVoucherRow->cr_dr) ?></td>
        </tr>
        <tr>
            <th scope="row"><?= __('Ledger') ?></th>
            <td><?= $journalVoucherRow->has('ledger') ? $this->Html->link($journalVoucherRow->ledger->name, ['controller' => 'Ledgers', 'action' => 'view', $journalVoucherRow->ledger->id]) : '' ?></td>
        </tr>
        <tr>
            <th scope="row"><?= __('Mode Of Payment') ?></th>
            <td><?= h($journalVoucherRow->mode_of_payment) ?></td>
        </tr>
        <tr>
            <th scope="row"><?= __('Cheque No') ?></th>
            <td><?= h($journalVoucherRow->cheque_no) ?></td>
        </tr>
        <tr>
            <th scope="row"><?= __('Id') ?></th>
            <td><?= $this->Number->format($journalVoucherRow->id) ?></td>
        </tr>
        <tr>
            <th scope="row"><?= __('Debit') ?></th>
            <td><?= $this->Number->format($journalVoucherRow->debit) ?></td>
        </tr>
        <tr>
            <th scope="row"><?= __('Credit') ?></th>
            <td><?= $this->Number->format($journalVoucherRow->credit) ?></td>
        </tr>
        <tr>
            <th scope="row"><?= __('Total') ?></th>
            <td><?= $this->Number->format($journalVoucherRow->total) ?></td>
        </tr>
        <tr>
            <th scope="row"><?= __('Cheque Date') ?></th>
            <td><?= h($journalVoucherRow->cheque_date) ?></td>
        </tr>
    </table>
</div>
