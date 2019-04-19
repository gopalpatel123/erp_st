<?php
/**
  * @var \App\View\AppView $this
  * @var \App\Model\Entity\SaleReturnRow[]|\Cake\Collection\CollectionInterface $saleReturnRows
  */
?>
<nav class="large-3 medium-4 columns" id="actions-sidebar">
    <ul class="side-nav">
        <li class="heading"><?= __('Actions') ?></li>
        <li><?= $this->Html->link(__('New Sale Return Row'), ['action' => 'add']) ?></li>
        <li><?= $this->Html->link(__('List Sale Returns'), ['controller' => 'SaleReturns', 'action' => 'index']) ?></li>
        <li><?= $this->Html->link(__('New Sale Return'), ['controller' => 'SaleReturns', 'action' => 'add']) ?></li>
        <li><?= $this->Html->link(__('List Items'), ['controller' => 'Items', 'action' => 'index']) ?></li>
        <li><?= $this->Html->link(__('New Item'), ['controller' => 'Items', 'action' => 'add']) ?></li>
        <li><?= $this->Html->link(__('List Gst Figures'), ['controller' => 'GstFigures', 'action' => 'index']) ?></li>
        <li><?= $this->Html->link(__('New Gst Figure'), ['controller' => 'GstFigures', 'action' => 'add']) ?></li>
    </ul>
</nav>
<div class="saleReturnRows index large-9 medium-8 columns content">
    <h3><?= __('Sale Return Rows') ?></h3>
    <table cellpadding="0" cellspacing="0">
        <thead>
            <tr>
                <th scope="col"><?= $this->Paginator->sort('id') ?></th>
                <th scope="col"><?= $this->Paginator->sort('sale_return_id') ?></th>
                <th scope="col"><?= $this->Paginator->sort('item_id') ?></th>
                <th scope="col"><?= $this->Paginator->sort('quantity') ?></th>
                <th scope="col"><?= $this->Paginator->sort('rate') ?></th>
                <th scope="col"><?= $this->Paginator->sort('discount_percentage') ?></th>
                <th scope="col"><?= $this->Paginator->sort('taxable_value') ?></th>
                <th scope="col"><?= $this->Paginator->sort('net_amount') ?></th>
                <th scope="col"><?= $this->Paginator->sort('gst_figure_id') ?></th>
                <th scope="col"><?= $this->Paginator->sort('gst_value') ?></th>
                <th scope="col" class="actions"><?= __('Actions') ?></th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($saleReturnRows as $saleReturnRow): ?>
            <tr>
                <td><?= $this->Number->format($saleReturnRow->id) ?></td>
                <td><?= $saleReturnRow->has('sale_return') ? $this->Html->link($saleReturnRow->sale_return->id, ['controller' => 'SaleReturns', 'action' => 'view', $saleReturnRow->sale_return->id]) : '' ?></td>
                <td><?= $saleReturnRow->has('item') ? $this->Html->link($saleReturnRow->item->name, ['controller' => 'Items', 'action' => 'view', $saleReturnRow->item->id]) : '' ?></td>
                <td><?= $this->Number->format($saleReturnRow->quantity) ?></td>
                <td><?= $this->Number->format($saleReturnRow->rate) ?></td>
                <td><?= $this->Number->format($saleReturnRow->discount_percentage) ?></td>
                <td><?= $this->Number->format($saleReturnRow->taxable_value) ?></td>
                <td><?= $this->Number->format($saleReturnRow->net_amount) ?></td>
                <td><?= $saleReturnRow->has('gst_figure') ? $this->Html->link($saleReturnRow->gst_figure->name, ['controller' => 'GstFigures', 'action' => 'view', $saleReturnRow->gst_figure->id]) : '' ?></td>
                <td><?= $this->Number->format($saleReturnRow->gst_value) ?></td>
                <td class="actions">
                    <?= $this->Html->link(__('View'), ['action' => 'view', $saleReturnRow->id]) ?>
                    <?= $this->Html->link(__('Edit'), ['action' => 'edit', $saleReturnRow->id]) ?>
                    <?= $this->Form->postLink(__('Delete'), ['action' => 'delete', $saleReturnRow->id], ['confirm' => __('Are you sure you want to delete # {0}?', $saleReturnRow->id)]) ?>
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
