<?php
/**
  * @var \App\View\AppView $this
  * @var \App\Model\Entity\SalesVoucherRow[]|\Cake\Collection\CollectionInterface $salesVoucherRows
  */
?>
<nav class="large-3 medium-4 columns" id="actions-sidebar">
    <ul class="side-nav">
        <li class="heading"><?= __('Actions') ?></li>
        <li><?= $this->Html->link(__('New Sales Voucher Row'), ['action' => 'add']) ?></li>
        <li><?= $this->Html->link(__('List Sales Vouchers'), ['controller' => 'SalesVouchers', 'action' => 'index']) ?></li>
        <li><?= $this->Html->link(__('New Sales Voucher'), ['controller' => 'SalesVouchers', 'action' => 'add']) ?></li>
        <li><?= $this->Html->link(__('List Ledgers'), ['controller' => 'Ledgers', 'action' => 'index']) ?></li>
        <li><?= $this->Html->link(__('New Ledger'), ['controller' => 'Ledgers', 'action' => 'add']) ?></li>
        <li><?= $this->Html->link(__('List Reference Details'), ['controller' => 'ReferenceDetails', 'action' => 'index']) ?></li>
        <li><?= $this->Html->link(__('New Reference Detail'), ['controller' => 'ReferenceDetails', 'action' => 'add']) ?></li>
    </ul>
</nav>
<div class="salesVoucherRows index large-9 medium-8 columns content">
    <h3><?= __('Sales Voucher Rows') ?></h3>
    <table cellpadding="0" cellspacing="0">
        <thead>
            <tr>
                <th scope="col"><?= $this->Paginator->sort('id') ?></th>
                <th scope="col"><?= $this->Paginator->sort('sales_voucher_id') ?></th>
                <th scope="col"><?= $this->Paginator->sort('cr_dr') ?></th>
                <th scope="col"><?= $this->Paginator->sort('ledger_id') ?></th>
                <th scope="col"><?= $this->Paginator->sort('debit') ?></th>
                <th scope="col"><?= $this->Paginator->sort('credit') ?></th>
                <th scope="col"><?= $this->Paginator->sort('mode_of_payment') ?></th>
                <th scope="col"><?= $this->Paginator->sort('cheque_no') ?></th>
                <th scope="col"><?= $this->Paginator->sort('cheque_date') ?></th>
                <th scope="col" class="actions"><?= __('Actions') ?></th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($salesVoucherRows as $salesVoucherRow): ?>
            <tr>
                <td><?= $this->Number->format($salesVoucherRow->id) ?></td>
                <td><?= $salesVoucherRow->has('sales_voucher') ? $this->Html->link($salesVoucherRow->sales_voucher->id, ['controller' => 'SalesVouchers', 'action' => 'view', $salesVoucherRow->sales_voucher->id]) : '' ?></td>
                <td><?= h($salesVoucherRow->cr_dr) ?></td>
                <td><?= $salesVoucherRow->has('ledger') ? $this->Html->link($salesVoucherRow->ledger->name, ['controller' => 'Ledgers', 'action' => 'view', $salesVoucherRow->ledger->id]) : '' ?></td>
                <td><?= $this->Number->format($salesVoucherRow->debit) ?></td>
                <td><?= $this->Number->format($salesVoucherRow->credit) ?></td>
                <td><?= h($salesVoucherRow->mode_of_payment) ?></td>
                <td><?= h($salesVoucherRow->cheque_no) ?></td>
                <td><?= h($salesVoucherRow->cheque_date) ?></td>
                <td class="actions">
                    <?= $this->Html->link(__('View'), ['action' => 'view', $salesVoucherRow->id]) ?>
                    <?= $this->Html->link(__('Edit'), ['action' => 'edit', $salesVoucherRow->id]) ?>
                    <?= $this->Form->postLink(__('Delete'), ['action' => 'delete', $salesVoucherRow->id], ['confirm' => __('Are you sure you want to delete # {0}?', $salesVoucherRow->id)]) ?>
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
