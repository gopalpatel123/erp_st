<?php
/**
  * @var \App\View\AppView $this
  * @var \App\Model\Entity\PurchaseVoucherRow[]|\Cake\Collection\CollectionInterface $purchaseVoucherRows
  */
?>
<nav class="large-3 medium-4 columns" id="actions-sidebar">
    <ul class="side-nav">
        <li class="heading"><?= __('Actions') ?></li>
        <li><?= $this->Html->link(__('New Purchase Voucher Row'), ['action' => 'add']) ?></li>
        <li><?= $this->Html->link(__('List Purchase Vouchers'), ['controller' => 'PurchaseVouchers', 'action' => 'index']) ?></li>
        <li><?= $this->Html->link(__('New Purchase Voucher'), ['controller' => 'PurchaseVouchers', 'action' => 'add']) ?></li>
        <li><?= $this->Html->link(__('List Ledgers'), ['controller' => 'Ledgers', 'action' => 'index']) ?></li>
        <li><?= $this->Html->link(__('New Ledger'), ['controller' => 'Ledgers', 'action' => 'add']) ?></li>
    </ul>
</nav>
<div class="purchaseVoucherRows index large-9 medium-8 columns content">
    <h3><?= __('Purchase Voucher Rows') ?></h3>
    <table cellpadding="0" cellspacing="0">
        <thead>
            <tr>
                <th scope="col"><?= $this->Paginator->sort('id') ?></th>
                <th scope="col"><?= $this->Paginator->sort('purchase_voucher_id') ?></th>
                <th scope="col"><?= $this->Paginator->sort('ledger_id') ?></th>
                <th scope="col"><?= $this->Paginator->sort('debit') ?></th>
                <th scope="col"><?= $this->Paginator->sort('credit') ?></th>
                <th scope="col" class="actions"><?= __('Actions') ?></th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($purchaseVoucherRows as $purchaseVoucherRow): ?>
            <tr>
                <td><?= $this->Number->format($purchaseVoucherRow->id) ?></td>
                <td><?= $purchaseVoucherRow->has('purchase_voucher') ? $this->Html->link($purchaseVoucherRow->purchase_voucher->id, ['controller' => 'PurchaseVouchers', 'action' => 'view', $purchaseVoucherRow->purchase_voucher->id]) : '' ?></td>
                <td><?= $purchaseVoucherRow->has('ledger') ? $this->Html->link($purchaseVoucherRow->ledger->name, ['controller' => 'Ledgers', 'action' => 'view', $purchaseVoucherRow->ledger->id]) : '' ?></td>
                <td><?= $this->Number->format($purchaseVoucherRow->debit) ?></td>
                <td><?= $this->Number->format($purchaseVoucherRow->credit) ?></td>
                <td class="actions">
                    <?= $this->Html->link(__('View'), ['action' => 'view', $purchaseVoucherRow->id]) ?>
                    <?= $this->Html->link(__('Edit'), ['action' => 'edit', $purchaseVoucherRow->id]) ?>
                    <?= $this->Form->postLink(__('Delete'), ['action' => 'delete', $purchaseVoucherRow->id], ['confirm' => __('Are you sure you want to delete # {0}?', $purchaseVoucherRow->id)]) ?>
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
