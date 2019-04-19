<?php
/**
  * @var \App\View\AppView $this
  * @var \App\Model\Entity\PurchaseReturnRow[]|\Cake\Collection\CollectionInterface $purchaseReturnRows
  */
?>
<nav class="large-3 medium-4 columns" id="actions-sidebar">
    <ul class="side-nav">
        <li class="heading"><?= __('Actions') ?></li>
        <li><?= $this->Html->link(__('New Purchase Return Row'), ['action' => 'add']) ?></li>
        <li><?= $this->Html->link(__('List Purchase Returns'), ['controller' => 'PurchaseReturns', 'action' => 'index']) ?></li>
        <li><?= $this->Html->link(__('New Purchase Return'), ['controller' => 'PurchaseReturns', 'action' => 'add']) ?></li>
        <li><?= $this->Html->link(__('List Items'), ['controller' => 'Items', 'action' => 'index']) ?></li>
        <li><?= $this->Html->link(__('New Item'), ['controller' => 'Items', 'action' => 'add']) ?></li>
    </ul>
</nav>
<div class="purchaseReturnRows index large-9 medium-8 columns content">
    <h3><?= __('Purchase Return Rows') ?></h3>
    <table cellpadding="0" cellspacing="0">
        <thead>
            <tr>
                <th scope="col"><?= $this->Paginator->sort('id') ?></th>
                <th scope="col"><?= $this->Paginator->sort('purchase_return_id') ?></th>
                <th scope="col"><?= $this->Paginator->sort('item_id') ?></th>
                <th scope="col"><?= $this->Paginator->sort('quantity') ?></th>
                <th scope="col"><?= $this->Paginator->sort('discount_percentage') ?></th>
                <th scope="col"><?= $this->Paginator->sort('discount_amount') ?></th>
                <th scope="col"><?= $this->Paginator->sort('pnf_percentage') ?></th>
                <th scope="col"><?= $this->Paginator->sort('pnf_amount') ?></th>
                <th scope="col"><?= $this->Paginator->sort('taxable_value') ?></th>
                <th scope="col"><?= $this->Paginator->sort('item_gst_figure_id') ?></th>
                <th scope="col"><?= $this->Paginator->sort('gst_value') ?></th>
                <th scope="col"><?= $this->Paginator->sort('round_off') ?></th>
                <th scope="col"><?= $this->Paginator->sort('net_amount') ?></th>
                <th scope="col" class="actions"><?= __('Actions') ?></th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($purchaseReturnRows as $purchaseReturnRow): ?>
            <tr>
                <td><?= $this->Number->format($purchaseReturnRow->id) ?></td>
                <td><?= $purchaseReturnRow->has('purchase_return') ? $this->Html->link($purchaseReturnRow->purchase_return->id, ['controller' => 'PurchaseReturns', 'action' => 'view', $purchaseReturnRow->purchase_return->id]) : '' ?></td>
                <td><?= $purchaseReturnRow->has('item') ? $this->Html->link($purchaseReturnRow->item->name, ['controller' => 'Items', 'action' => 'view', $purchaseReturnRow->item->id]) : '' ?></td>
                <td><?= $this->Number->format($purchaseReturnRow->quantity) ?></td>
                <td><?= $this->Number->format($purchaseReturnRow->discount_percentage) ?></td>
                <td><?= $this->Number->format($purchaseReturnRow->discount_amount) ?></td>
                <td><?= $this->Number->format($purchaseReturnRow->pnf_percentage) ?></td>
                <td><?= $this->Number->format($purchaseReturnRow->pnf_amount) ?></td>
                <td><?= $this->Number->format($purchaseReturnRow->taxable_value) ?></td>
                <td><?= $this->Number->format($purchaseReturnRow->item_gst_figure_id) ?></td>
                <td><?= $this->Number->format($purchaseReturnRow->gst_value) ?></td>
                <td><?= $this->Number->format($purchaseReturnRow->round_off) ?></td>
                <td><?= $this->Number->format($purchaseReturnRow->net_amount) ?></td>
                <td class="actions">
                    <?= $this->Html->link(__('View'), ['action' => 'view', $purchaseReturnRow->id]) ?>
                    <?= $this->Html->link(__('Edit'), ['action' => 'edit', $purchaseReturnRow->id]) ?>
                    <?= $this->Form->postLink(__('Delete'), ['action' => 'delete', $purchaseReturnRow->id], ['confirm' => __('Are you sure you want to delete # {0}?', $purchaseReturnRow->id)]) ?>
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
