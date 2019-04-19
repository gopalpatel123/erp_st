<?php
/**
  * @var \App\View\AppView $this
  * @var \App\Model\Entity\JournalVoucherRow[]|\Cake\Collection\CollectionInterface $journalVoucherRows
  */
?>
<nav class="large-3 medium-4 columns" id="actions-sidebar">
    <ul class="side-nav">
        <li class="heading"><?= __('Actions') ?></li>
        <li><?= $this->Html->link(__('New Journal Voucher Row'), ['action' => 'add']) ?></li>
        <li><?= $this->Html->link(__('List Journal Vouchers'), ['controller' => 'JournalVouchers', 'action' => 'index']) ?></li>
        <li><?= $this->Html->link(__('New Journal Voucher'), ['controller' => 'JournalVouchers', 'action' => 'add']) ?></li>
        <li><?= $this->Html->link(__('List Ledgers'), ['controller' => 'Ledgers', 'action' => 'index']) ?></li>
        <li><?= $this->Html->link(__('New Ledger'), ['controller' => 'Ledgers', 'action' => 'add']) ?></li>
    </ul>
</nav>
<div class="journalVoucherRows index large-9 medium-8 columns content">
    <h3><?= __('Journal Voucher Rows') ?></h3>
    <table cellpadding="0" cellspacing="0">
        <thead>
            <tr>
                <th scope="col"><?= $this->Paginator->sort('id') ?></th>
                <th scope="col"><?= $this->Paginator->sort('journal_voucher_id') ?></th>
                <th scope="col"><?= $this->Paginator->sort('cr_dr') ?></th>
                <th scope="col"><?= $this->Paginator->sort('ledger_id') ?></th>
                <th scope="col"><?= $this->Paginator->sort('debit') ?></th>
                <th scope="col"><?= $this->Paginator->sort('credit') ?></th>
                <th scope="col"><?= $this->Paginator->sort('mode_of_payment') ?></th>
                <th scope="col"><?= $this->Paginator->sort('cheque_no') ?></th>
                <th scope="col"><?= $this->Paginator->sort('cheque_date') ?></th>
                <th scope="col"><?= $this->Paginator->sort('total') ?></th>
                <th scope="col" class="actions"><?= __('Actions') ?></th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($journalVoucherRows as $journalVoucherRow): ?>
            <tr>
                <td><?= $this->Number->format($journalVoucherRow->id) ?></td>
                <td><?= $journalVoucherRow->has('journal_voucher') ? $this->Html->link($journalVoucherRow->journal_voucher->id, ['controller' => 'JournalVouchers', 'action' => 'view', $journalVoucherRow->journal_voucher->id]) : '' ?></td>
                <td><?= h($journalVoucherRow->cr_dr) ?></td>
                <td><?= $journalVoucherRow->has('ledger') ? $this->Html->link($journalVoucherRow->ledger->name, ['controller' => 'Ledgers', 'action' => 'view', $journalVoucherRow->ledger->id]) : '' ?></td>
                <td><?= $this->Number->format($journalVoucherRow->debit) ?></td>
                <td><?= $this->Number->format($journalVoucherRow->credit) ?></td>
                <td><?= h($journalVoucherRow->mode_of_payment) ?></td>
                <td><?= h($journalVoucherRow->cheque_no) ?></td>
                <td><?= h($journalVoucherRow->cheque_date) ?></td>
                <td><?= $this->Number->format($journalVoucherRow->total) ?></td>
                <td class="actions">
                    <?= $this->Html->link(__('View'), ['action' => 'view', $journalVoucherRow->id]) ?>
                    <?= $this->Html->link(__('Edit'), ['action' => 'edit', $journalVoucherRow->id]) ?>
                    <?= $this->Form->postLink(__('Delete'), ['action' => 'delete', $journalVoucherRow->id], ['confirm' => __('Are you sure you want to delete # {0}?', $journalVoucherRow->id)]) ?>
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
