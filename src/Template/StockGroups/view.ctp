<?php
/**
  * @var \App\View\AppView $this
  * @var \App\Model\Entity\StockGroup $stockGroup
  */
?>
<nav class="large-3 medium-4 columns" id="actions-sidebar">
    <ul class="side-nav">
        <li class="heading"><?= __('Actions') ?></li>
        <li><?= $this->Html->link(__('Edit Stock Group'), ['action' => 'edit', $stockGroup->id]) ?> </li>
        <li><?= $this->Form->postLink(__('Delete Stock Group'), ['action' => 'delete', $stockGroup->id], ['confirm' => __('Are you sure you want to delete # {0}?', $stockGroup->id)]) ?> </li>
        <li><?= $this->Html->link(__('List Stock Groups'), ['action' => 'index']) ?> </li>
        <li><?= $this->Html->link(__('New Stock Group'), ['action' => 'add']) ?> </li>
        <li><?= $this->Html->link(__('List Parent Stock Groups'), ['controller' => 'StockGroups', 'action' => 'index']) ?> </li>
        <li><?= $this->Html->link(__('New Parent Stock Group'), ['controller' => 'StockGroups', 'action' => 'add']) ?> </li>
        <li><?= $this->Html->link(__('List Companies'), ['controller' => 'Companies', 'action' => 'index']) ?> </li>
        <li><?= $this->Html->link(__('New Company'), ['controller' => 'Companies', 'action' => 'add']) ?> </li>
        <li><?= $this->Html->link(__('List Items'), ['controller' => 'Items', 'action' => 'index']) ?> </li>
        <li><?= $this->Html->link(__('New Item'), ['controller' => 'Items', 'action' => 'add']) ?> </li>
    </ul>
</nav>
<div class="stockGroups view large-9 medium-8 columns content">
    <h3><?= h($stockGroup->name) ?></h3>
    <table class="vertical-table">
        <tr>
            <th scope="row"><?= __('Name') ?></th>
            <td><?= h($stockGroup->name) ?></td>
        </tr>
        <tr>
            <th scope="row"><?= __('Parent Stock Group') ?></th>
            <td><?= $stockGroup->has('parent_stock_group') ? $this->Html->link($stockGroup->parent_stock_group->name, ['controller' => 'StockGroups', 'action' => 'view', $stockGroup->parent_stock_group->id]) : '' ?></td>
        </tr>
        <tr>
            <th scope="row"><?= __('Company') ?></th>
            <td><?= $stockGroup->has('company') ? $this->Html->link($stockGroup->company->name, ['controller' => 'Companies', 'action' => 'view', $stockGroup->company->id]) : '' ?></td>
        </tr>
        <tr>
            <th scope="row"><?= __('Id') ?></th>
            <td><?= $this->Number->format($stockGroup->id) ?></td>
        </tr>
        <tr>
            <th scope="row"><?= __('Lft') ?></th>
            <td><?= $this->Number->format($stockGroup->lft) ?></td>
        </tr>
        <tr>
            <th scope="row"><?= __('Rght') ?></th>
            <td><?= $this->Number->format($stockGroup->rght) ?></td>
        </tr>
    </table>
    <div class="related">
        <h4><?= __('Related Items') ?></h4>
        <?php if (!empty($stockGroup->items)): ?>
        <table cellpadding="0" cellspacing="0">
            <tr>
                <th scope="col"><?= __('Id') ?></th>
                <th scope="col"><?= __('Name') ?></th>
                <th scope="col"><?= __('Hsn Code') ?></th>
                <th scope="col"><?= __('Unit Id') ?></th>
                <th scope="col"><?= __('Stock Group Id') ?></th>
                <th scope="col" class="actions"><?= __('Actions') ?></th>
            </tr>
            <?php foreach ($stockGroup->items as $items): ?>
            <tr>
                <td><?= h($items->id) ?></td>
                <td><?= h($items->name) ?></td>
                <td><?= h($items->hsn_code) ?></td>
                <td><?= h($items->unit_id) ?></td>
                <td><?= h($items->stock_group_id) ?></td>
                <td class="actions">
                    <?= $this->Html->link(__('View'), ['controller' => 'Items', 'action' => 'view', $items->id]) ?>
                    <?= $this->Html->link(__('Edit'), ['controller' => 'Items', 'action' => 'edit', $items->id]) ?>
                    <?= $this->Form->postLink(__('Delete'), ['controller' => 'Items', 'action' => 'delete', $items->id], ['confirm' => __('Are you sure you want to delete # {0}?', $items->id)]) ?>
                </td>
            </tr>
            <?php endforeach; ?>
        </table>
        <?php endif; ?>
    </div>
    <div class="related">
        <h4><?= __('Related Stock Groups') ?></h4>
        <?php if (!empty($stockGroup->child_stock_groups)): ?>
        <table cellpadding="0" cellspacing="0">
            <tr>
                <th scope="col"><?= __('Id') ?></th>
                <th scope="col"><?= __('Name') ?></th>
                <th scope="col"><?= __('Parent Id') ?></th>
                <th scope="col"><?= __('Lft') ?></th>
                <th scope="col"><?= __('Rght') ?></th>
                <th scope="col"><?= __('Company Id') ?></th>
                <th scope="col" class="actions"><?= __('Actions') ?></th>
            </tr>
            <?php foreach ($stockGroup->child_stock_groups as $childStockGroups): ?>
            <tr>
                <td><?= h($childStockGroups->id) ?></td>
                <td><?= h($childStockGroups->name) ?></td>
                <td><?= h($childStockGroups->parent_id) ?></td>
                <td><?= h($childStockGroups->lft) ?></td>
                <td><?= h($childStockGroups->rght) ?></td>
                <td><?= h($childStockGroups->company_id) ?></td>
                <td class="actions">
                    <?= $this->Html->link(__('View'), ['controller' => 'StockGroups', 'action' => 'view', $childStockGroups->id]) ?>
                    <?= $this->Html->link(__('Edit'), ['controller' => 'StockGroups', 'action' => 'edit', $childStockGroups->id]) ?>
                    <?= $this->Form->postLink(__('Delete'), ['controller' => 'StockGroups', 'action' => 'delete', $childStockGroups->id], ['confirm' => __('Are you sure you want to delete # {0}?', $childStockGroups->id)]) ?>
                </td>
            </tr>
            <?php endforeach; ?>
        </table>
        <?php endif; ?>
    </div>
</div>
