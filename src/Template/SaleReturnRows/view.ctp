<?php
/**
  * @var \App\View\AppView $this
  * @var \App\Model\Entity\SaleReturnRow $saleReturnRow
  */
?>
<nav class="large-3 medium-4 columns" id="actions-sidebar">
    <ul class="side-nav">
        <li class="heading"><?= __('Actions') ?></li>
        <li><?= $this->Html->link(__('Edit Sale Return Row'), ['action' => 'edit', $saleReturnRow->id]) ?> </li>
        <li><?= $this->Form->postLink(__('Delete Sale Return Row'), ['action' => 'delete', $saleReturnRow->id], ['confirm' => __('Are you sure you want to delete # {0}?', $saleReturnRow->id)]) ?> </li>
        <li><?= $this->Html->link(__('List Sale Return Rows'), ['action' => 'index']) ?> </li>
        <li><?= $this->Html->link(__('New Sale Return Row'), ['action' => 'add']) ?> </li>
        <li><?= $this->Html->link(__('List Sale Returns'), ['controller' => 'SaleReturns', 'action' => 'index']) ?> </li>
        <li><?= $this->Html->link(__('New Sale Return'), ['controller' => 'SaleReturns', 'action' => 'add']) ?> </li>
        <li><?= $this->Html->link(__('List Items'), ['controller' => 'Items', 'action' => 'index']) ?> </li>
        <li><?= $this->Html->link(__('New Item'), ['controller' => 'Items', 'action' => 'add']) ?> </li>
        <li><?= $this->Html->link(__('List Gst Figures'), ['controller' => 'GstFigures', 'action' => 'index']) ?> </li>
        <li><?= $this->Html->link(__('New Gst Figure'), ['controller' => 'GstFigures', 'action' => 'add']) ?> </li>
    </ul>
</nav>
<div class="saleReturnRows view large-9 medium-8 columns content">
    <h3><?= h($saleReturnRow->id) ?></h3>
    <table class="vertical-table">
        <tr>
            <th scope="row"><?= __('Sale Return') ?></th>
            <td><?= $saleReturnRow->has('sale_return') ? $this->Html->link($saleReturnRow->sale_return->id, ['controller' => 'SaleReturns', 'action' => 'view', $saleReturnRow->sale_return->id]) : '' ?></td>
        </tr>
        <tr>
            <th scope="row"><?= __('Item') ?></th>
            <td><?= $saleReturnRow->has('item') ? $this->Html->link($saleReturnRow->item->name, ['controller' => 'Items', 'action' => 'view', $saleReturnRow->item->id]) : '' ?></td>
        </tr>
        <tr>
            <th scope="row"><?= __('Gst Figure') ?></th>
            <td><?= $saleReturnRow->has('gst_figure') ? $this->Html->link($saleReturnRow->gst_figure->name, ['controller' => 'GstFigures', 'action' => 'view', $saleReturnRow->gst_figure->id]) : '' ?></td>
        </tr>
        <tr>
            <th scope="row"><?= __('Id') ?></th>
            <td><?= $this->Number->format($saleReturnRow->id) ?></td>
        </tr>
        <tr>
            <th scope="row"><?= __('Quantity') ?></th>
            <td><?= $this->Number->format($saleReturnRow->quantity) ?></td>
        </tr>
        <tr>
            <th scope="row"><?= __('Rate') ?></th>
            <td><?= $this->Number->format($saleReturnRow->rate) ?></td>
        </tr>
        <tr>
            <th scope="row"><?= __('Discount Percentage') ?></th>
            <td><?= $this->Number->format($saleReturnRow->discount_percentage) ?></td>
        </tr>
        <tr>
            <th scope="row"><?= __('Taxable Value') ?></th>
            <td><?= $this->Number->format($saleReturnRow->taxable_value) ?></td>
        </tr>
        <tr>
            <th scope="row"><?= __('Net Amount') ?></th>
            <td><?= $this->Number->format($saleReturnRow->net_amount) ?></td>
        </tr>
        <tr>
            <th scope="row"><?= __('Gst Value') ?></th>
            <td><?= $this->Number->format($saleReturnRow->gst_value) ?></td>
        </tr>
    </table>
</div>
