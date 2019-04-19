<?php
/**
  * @var \App\View\AppView $this
  * @var \App\Model\Entity\ContraVoucherRow[]|\Cake\Collection\CollectionInterface $contraVoucherRows
  */
?>
<nav class="large-3 medium-4 columns" id="actions-sidebar">
    <ul class="side-nav">
        <li class="heading"><?= __('Actions') ?></li>
        <li><?= $this->Html->link(__('New Contra Voucher Row'), ['action' => 'add']) ?></li>
        <li><?= $this->Html->link(__('List Journal Vouchers'), ['controller' => 'JournalVouchers', 'action' => 'index']) ?></li>
        <li><?= $this->Html->link(__('New Journal Voucher'), ['controller' => 'JournalVouchers', 'action' => 'add']) ?></li>
        <li><?= $this->Html->link(__('List Ledgers'), ['controller' => 'Ledgers', 'action' => 'index']) ?></li>
        <li><?= $this->Html->link(__('New Ledger'), ['controller' => 'Ledgers', 'action' => 'add']) ?></li>
        <li><?= $this->Html->link(__('List Reference Details'), ['controller' => 'ReferenceDetails', 'action' => 'index']) ?></li>
        <li><?= $this->Html->link(__('New Reference Detail'), ['controller' => 'ReferenceDetails', 'action' => 'add']) ?></li>
    </ul>
</nav>
<div class="contraVoucherRows index large-9 medium-8 columns content">
    <h3><?= __('Contra Voucher Rows') ?></h3>
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
            <?php foreach ($contraVoucherRows as $contraVoucherRow): ?>
            <tr>
                <td><?= $this->Number->format($contraVoucherRow->id) ?></td>
                <td><?= $contraVoucherRow->has('journal_voucher') ? $this->Html->link($contraVoucherRow->journal_voucher->id, ['controller' => 'JournalVouchers', 'action' => 'view', $contraVoucherRow->journal_voucher->id]) : '' ?></td>
                <td><?= h($contraVoucherRow->cr_dr) ?></td>
                <td><?= $contraVoucherRow->has('ledger') ? $this->Html->link($contraVoucherRow->ledger->name, ['controller' => 'Ledgers', 'action' => 'view', $contraVoucherRow->ledger->id]) : '' ?></td>
                <td><?= $this->Number->format($contraVoucherRow->debit) ?></td>
                <td><?= $this->Number->format($contraVoucherRow->credit) ?></td>
                <td><?= h($contraVoucherRow->mode_of_payment) ?></td>
                <td><?= h($contraVoucherRow->cheque_no) ?></td>
                <td><?= h($contraVoucherRow->cheque_date) ?></td>
                <td><?= $this->Number->format($contraVoucherRow->total) ?></td>
                <td class="actions">
                    <?= $this->Html->link(__('View'), ['action' => 'view', $contraVoucherRow->id]) ?>
                    <?= $this->Html->link(__('Edit'), ['action' => 'edit', $contraVoucherRow->id]) ?>
                    <?= $this->Form->postLink(__('Delete'), ['action' => 'delete', $contraVoucherRow->id], ['confirm' => __('Are you sure you want to delete # {0}?', $contraVoucherRow->id)]) ?>
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
