<?php
/**
  * @var \App\View\AppView $this
  * @var \App\Model\Entity\IntraLocationStockTransferVoucherRow[]|\Cake\Collection\CollectionInterface $intraLocationStockTransferVoucherRows
  */
?>
<nav class="large-3 medium-4 columns" id="actions-sidebar">
    <ul class="side-nav">
        <li class="heading"><?= __('Actions') ?></li>
        <li><?= $this->Html->link(__('New Intra Location Stock Transfer Voucher Row'), ['action' => 'add']) ?></li>
        <li><?= $this->Html->link(__('List Intra Location Stock Transfer Vouchers'), ['controller' => 'IntraLocationStockTransferVouchers', 'action' => 'index']) ?></li>
        <li><?= $this->Html->link(__('New Intra Location Stock Transfer Voucher'), ['controller' => 'IntraLocationStockTransferVouchers', 'action' => 'add']) ?></li>
        <li><?= $this->Html->link(__('List Items'), ['controller' => 'Items', 'action' => 'index']) ?></li>
        <li><?= $this->Html->link(__('New Item'), ['controller' => 'Items', 'action' => 'add']) ?></li>
    </ul>
</nav>
<div class="intraLocationStockTransferVoucherRows index large-9 medium-8 columns content">
    <h3><?= __('Intra Location Stock Transfer Voucher Rows') ?></h3>
    <table cellpadding="0" cellspacing="0">
        <thead>
            <tr>
                <th scope="col"><?= $this->Paginator->sort('id') ?></th>
                <th scope="col"><?= $this->Paginator->sort('intra_location_stock_transfer_voucher_id') ?></th>
                <th scope="col"><?= $this->Paginator->sort('item_id') ?></th>
                <th scope="col"><?= $this->Paginator->sort('quantity') ?></th>
                <th scope="col" class="actions"><?= __('Actions') ?></th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($intraLocationStockTransferVoucherRows as $intraLocationStockTransferVoucherRow): ?>
            <tr>
                <td><?= $this->Number->format($intraLocationStockTransferVoucherRow->id) ?></td>
                <td><?= $intraLocationStockTransferVoucherRow->has('intra_location_stock_transfer_voucher') ? $this->Html->link($intraLocationStockTransferVoucherRow->intra_location_stock_transfer_voucher->id, ['controller' => 'IntraLocationStockTransferVouchers', 'action' => 'view', $intraLocationStockTransferVoucherRow->intra_location_stock_transfer_voucher->id]) : '' ?></td>
                <td><?= $intraLocationStockTransferVoucherRow->has('item') ? $this->Html->link($intraLocationStockTransferVoucherRow->item->name, ['controller' => 'Items', 'action' => 'view', $intraLocationStockTransferVoucherRow->item->id]) : '' ?></td>
                <td><?= $this->Number->format($intraLocationStockTransferVoucherRow->quantity) ?></td>
                <td class="actions">
                    <?= $this->Html->link(__('View'), ['action' => 'view', $intraLocationStockTransferVoucherRow->id]) ?>
                    <?= $this->Html->link(__('Edit'), ['action' => 'edit', $intraLocationStockTransferVoucherRow->id]) ?>
                    <?= $this->Form->postLink(__('Delete'), ['action' => 'delete', $intraLocationStockTransferVoucherRow->id], ['confirm' => __('Are you sure you want to delete # {0}?', $intraLocationStockTransferVoucherRow->id)]) ?>
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
