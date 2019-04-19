<?php
/**
  * @var \App\View\AppView $this
  * @var \App\Model\Entity\AccountingGroup $accountingGroup
  */
?>
<nav class="large-3 medium-4 columns" id="actions-sidebar">
    <ul class="side-nav">
        <li class="heading"><?= __('Actions') ?></li>
        <li><?= $this->Html->link(__('Edit Accounting Group'), ['action' => 'edit', $accountingGroup->id]) ?> </li>
        <li><?= $this->Form->postLink(__('Delete Accounting Group'), ['action' => 'delete', $accountingGroup->id], ['confirm' => __('Are you sure you want to delete # {0}?', $accountingGroup->id)]) ?> </li>
        <li><?= $this->Html->link(__('List Accounting Groups'), ['action' => 'index']) ?> </li>
        <li><?= $this->Html->link(__('New Accounting Group'), ['action' => 'add']) ?> </li>
        <li><?= $this->Html->link(__('List Nature Of Groups'), ['controller' => 'NatureOfGroups', 'action' => 'index']) ?> </li>
        <li><?= $this->Html->link(__('New Nature Of Group'), ['controller' => 'NatureOfGroups', 'action' => 'add']) ?> </li>
        <li><?= $this->Html->link(__('List Parent Accounting Groups'), ['controller' => 'AccountingGroups', 'action' => 'index']) ?> </li>
        <li><?= $this->Html->link(__('New Parent Accounting Group'), ['controller' => 'AccountingGroups', 'action' => 'add']) ?> </li>
        <li><?= $this->Html->link(__('List Companies'), ['controller' => 'Companies', 'action' => 'index']) ?> </li>
        <li><?= $this->Html->link(__('New Company'), ['controller' => 'Companies', 'action' => 'add']) ?> </li>
        <li><?= $this->Html->link(__('List Ledgers'), ['controller' => 'Ledgers', 'action' => 'index']) ?> </li>
        <li><?= $this->Html->link(__('New Ledger'), ['controller' => 'Ledgers', 'action' => 'add']) ?> </li>
    </ul>
</nav>
<div class="accountingGroups view large-9 medium-8 columns content">
    <h3><?= h($accountingGroup->name) ?></h3>
    <table class="vertical-table">
        <tr>
            <th scope="row"><?= __('Nature Of Group') ?></th>
            <td><?= $accountingGroup->has('nature_of_group') ? $this->Html->link($accountingGroup->nature_of_group->name, ['controller' => 'NatureOfGroups', 'action' => 'view', $accountingGroup->nature_of_group->id]) : '' ?></td>
        </tr>
        <tr>
            <th scope="row"><?= __('Name') ?></th>
            <td><?= h($accountingGroup->name) ?></td>
        </tr>
        <tr>
            <th scope="row"><?= __('Parent Accounting Group') ?></th>
            <td><?= $accountingGroup->has('parent_accounting_group') ? $this->Html->link($accountingGroup->parent_accounting_group->name, ['controller' => 'AccountingGroups', 'action' => 'view', $accountingGroup->parent_accounting_group->id]) : '' ?></td>
        </tr>
        <tr>
            <th scope="row"><?= __('Company') ?></th>
            <td><?= $accountingGroup->has('company') ? $this->Html->link($accountingGroup->company->name, ['controller' => 'Companies', 'action' => 'view', $accountingGroup->company->id]) : '' ?></td>
        </tr>
        <tr>
            <th scope="row"><?= __('Id') ?></th>
            <td><?= $this->Number->format($accountingGroup->id) ?></td>
        </tr>
        <tr>
            <th scope="row"><?= __('Lft') ?></th>
            <td><?= $this->Number->format($accountingGroup->lft) ?></td>
        </tr>
        <tr>
            <th scope="row"><?= __('Rght') ?></th>
            <td><?= $this->Number->format($accountingGroup->rght) ?></td>
        </tr>
        <tr>
            <th scope="row"><?= __('Customer') ?></th>
            <td><?= $accountingGroup->customer ? __('Yes') : __('No'); ?></td>
        </tr>
        <tr>
            <th scope="row"><?= __('Supplier') ?></th>
            <td><?= $accountingGroup->supplier ? __('Yes') : __('No'); ?></td>
        </tr>
    </table>
    <div class="related">
        <h4><?= __('Related Accounting Groups') ?></h4>
        <?php if (!empty($accountingGroup->child_accounting_groups)): ?>
        <table cellpadding="0" cellspacing="0">
            <tr>
                <th scope="col"><?= __('Id') ?></th>
                <th scope="col"><?= __('Nature Of Group Id') ?></th>
                <th scope="col"><?= __('Name') ?></th>
                <th scope="col"><?= __('Parent Id') ?></th>
                <th scope="col"><?= __('Lft') ?></th>
                <th scope="col"><?= __('Rght') ?></th>
                <th scope="col"><?= __('Company Id') ?></th>
                <th scope="col"><?= __('Customer') ?></th>
                <th scope="col"><?= __('Supplier') ?></th>
                <th scope="col" class="actions"><?= __('Actions') ?></th>
            </tr>
            <?php foreach ($accountingGroup->child_accounting_groups as $childAccountingGroups): ?>
            <tr>
                <td><?= h($childAccountingGroups->id) ?></td>
                <td><?= h($childAccountingGroups->nature_of_group_id) ?></td>
                <td><?= h($childAccountingGroups->name) ?></td>
                <td><?= h($childAccountingGroups->parent_id) ?></td>
                <td><?= h($childAccountingGroups->lft) ?></td>
                <td><?= h($childAccountingGroups->rght) ?></td>
                <td><?= h($childAccountingGroups->company_id) ?></td>
                <td><?= h($childAccountingGroups->customer) ?></td>
                <td><?= h($childAccountingGroups->supplier) ?></td>
                <td class="actions">
                    <?= $this->Html->link(__('View'), ['controller' => 'AccountingGroups', 'action' => 'view', $childAccountingGroups->id]) ?>
                    <?= $this->Html->link(__('Edit'), ['controller' => 'AccountingGroups', 'action' => 'edit', $childAccountingGroups->id]) ?>
                    <?= $this->Form->postLink(__('Delete'), ['controller' => 'AccountingGroups', 'action' => 'delete', $childAccountingGroups->id], ['confirm' => __('Are you sure you want to delete # {0}?', $childAccountingGroups->id)]) ?>
                </td>
            </tr>
            <?php endforeach; ?>
        </table>
        <?php endif; ?>
    </div>
    <div class="related">
        <h4><?= __('Related Ledgers') ?></h4>
        <?php if (!empty($accountingGroup->ledgers)): ?>
        <table cellpadding="0" cellspacing="0">
            <tr>
                <th scope="col"><?= __('Id') ?></th>
                <th scope="col"><?= __('Name') ?></th>
                <th scope="col"><?= __('Accounting Group Id') ?></th>
                <th scope="col"><?= __('Freeze') ?></th>
                <th scope="col"><?= __('Company Id') ?></th>
                <th scope="col"><?= __('Supplier Id') ?></th>
                <th scope="col"><?= __('Customer Id') ?></th>
                <th scope="col"><?= __('Tax Percentage') ?></th>
                <th scope="col"><?= __('Gst Type') ?></th>
                <th scope="col"><?= __('Bill To Bill Accounting') ?></th>
                <th scope="col" class="actions"><?= __('Actions') ?></th>
            </tr>
            <?php foreach ($accountingGroup->ledgers as $ledgers): ?>
            <tr>
                <td><?= h($ledgers->id) ?></td>
                <td><?= h($ledgers->name) ?></td>
                <td><?= h($ledgers->accounting_group_id) ?></td>
                <td><?= h($ledgers->freeze) ?></td>
                <td><?= h($ledgers->company_id) ?></td>
                <td><?= h($ledgers->supplier_id) ?></td>
                <td><?= h($ledgers->customer_id) ?></td>
                <td><?= h($ledgers->tax_percentage) ?></td>
                <td><?= h($ledgers->gst_type) ?></td>
                <td><?= h($ledgers->bill_to_bill_accounting) ?></td>
                <td class="actions">
                    <?= $this->Html->link(__('View'), ['controller' => 'Ledgers', 'action' => 'view', $ledgers->id]) ?>
                    <?= $this->Html->link(__('Edit'), ['controller' => 'Ledgers', 'action' => 'edit', $ledgers->id]) ?>
                    <?= $this->Form->postLink(__('Delete'), ['controller' => 'Ledgers', 'action' => 'delete', $ledgers->id], ['confirm' => __('Are you sure you want to delete # {0}?', $ledgers->id)]) ?>
                </td>
            </tr>
            <?php endforeach; ?>
        </table>
        <?php endif; ?>
    </div>
</div>
