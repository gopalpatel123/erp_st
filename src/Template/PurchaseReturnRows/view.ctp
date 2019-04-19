<?php
/**
  * @var \App\View\AppView $this
  * @var \App\Model\Entity\PurchaseReturnRow $purchaseReturnRow
  */
?>
<nav class="large-3 medium-4 columns" id="actions-sidebar">
    <ul class="side-nav">
        <li class="heading"><?= __('Actions') ?></li>
        <li><?= $this->Html->link(__('Edit Purchase Return Row'), ['action' => 'edit', $purchaseReturnRow->id]) ?> </li>
        <li><?= $this->Form->postLink(__('Delete Purchase Return Row'), ['action' => 'delete', $purchaseReturnRow->id], ['confirm' => __('Are you sure you want to delete # {0}?', $purchaseReturnRow->id)]) ?> </li>
        <li><?= $this->Html->link(__('List Purchase Return Rows'), ['action' => 'index']) ?> </li>
        <li><?= $this->Html->link(__('New Purchase Return Row'), ['action' => 'add']) ?> </li>
        <li><?= $this->Html->link(__('List Purchase Returns'), ['controller' => 'PurchaseReturns', 'action' => 'index']) ?> </li>
        <li><?= $this->Html->link(__('New Purchase Return'), ['controller' => 'PurchaseReturns', 'action' => 'add']) ?> </li>
        <li><?= $this->Html->link(__('List Items'), ['controller' => 'Items', 'action' => 'index']) ?> </li>
        <li><?= $this->Html->link(__('New Item'), ['controller' => 'Items', 'action' => 'add']) ?> </li>
    </ul>
</nav>
<div class="purchaseReturnRows view large-9 medium-8 columns content">
    <h3><?= h($purchaseReturnRow->id) ?></h3>
    <table class="vertical-table">
        <tr>
            <th scope="row"><?= __('Purchase Return') ?></th>
            <td><?= $purchaseReturnRow->has('purchase_return') ? $this->Html->link($purchaseReturnRow->purchase_return->id, ['controller' => 'PurchaseReturns', 'action' => 'view', $purchaseReturnRow->purchase_return->id]) : '' ?></td>
        </tr>
        <tr>
            <th scope="row"><?= __('Item') ?></th>
            <td><?= $purchaseReturnRow->has('item') ? $this->Html->link($purchaseReturnRow->item->name, ['controller' => 'Items', 'action' => 'view', $purchaseReturnRow->item->id]) : '' ?></td>
        </tr>
        <tr>
            <th scope="row"><?= __('Id') ?></th>
            <td><?= $this->Number->format($purchaseReturnRow->id) ?></td>
        </tr>
        <tr>
            <th scope="row"><?= __('Quantity') ?></th>
            <td><?= $this->Number->format($purchaseReturnRow->quantity) ?></td>
        </tr>
        <tr>
            <th scope="row"><?= __('Discount Percentage') ?></th>
            <td><?= $this->Number->format($purchaseReturnRow->discount_percentage) ?></td>
        </tr>
        <tr>
            <th scope="row"><?= __('Discount Amount') ?></th>
            <td><?= $this->Number->format($purchaseReturnRow->discount_amount) ?></td>
        </tr>
        <tr>
            <th scope="row"><?= __('Pnf Percentage') ?></th>
            <td><?= $this->Number->format($purchaseReturnRow->pnf_percentage) ?></td>
        </tr>
        <tr>
            <th scope="row"><?= __('Pnf Amount') ?></th>
            <td><?= $this->Number->format($purchaseReturnRow->pnf_amount) ?></td>
        </tr>
        <tr>
            <th scope="row"><?= __('Taxable Value') ?></th>
            <td><?= $this->Number->format($purchaseReturnRow->taxable_value) ?></td>
        </tr>
        <tr>
            <th scope="row"><?= __('Item Gst Figure Id') ?></th>
            <td><?= $this->Number->format($purchaseReturnRow->item_gst_figure_id) ?></td>
        </tr>
        <tr>
            <th scope="row"><?= __('Gst Value') ?></th>
            <td><?= $this->Number->format($purchaseReturnRow->gst_value) ?></td>
        </tr>
        <tr>
            <th scope="row"><?= __('Round Off') ?></th>
            <td><?= $this->Number->format($purchaseReturnRow->round_off) ?></td>
        </tr>
        <tr>
            <th scope="row"><?= __('Net Amount') ?></th>
            <td><?= $this->Number->format($purchaseReturnRow->net_amount) ?></td>
        </tr>
    </table>
</div>
