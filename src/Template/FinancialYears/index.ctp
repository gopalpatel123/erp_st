<?php
/**
  * @var \App\View\AppView $this
  * @var \App\Model\Entity\FinancialYear[]|\Cake\Collection\CollectionInterface $financialYears
  */
?>
<nav class="large-3 medium-4 columns" id="actions-sidebar">
    <ul class="side-nav">
        <li class="heading"><?= __('Actions') ?></li>
        <li><?= $this->Html->link(__('New Financial Year'), ['action' => 'add']) ?></li>
    </ul>
</nav>
<div class="financialYears index large-9 medium-8 columns content">
    <h3><?= __('Financial Years') ?></h3>
    <table cellpadding="0" cellspacing="0">
        <thead>
            <tr>
                <th scope="col"><?= $this->Paginator->sort('id') ?></th>
                <th scope="col"><?= $this->Paginator->sort('fy_from') ?></th>
                <th scope="col"><?= $this->Paginator->sort('fy_to') ?></th>
                <th scope="col"><?= $this->Paginator->sort('status') ?></th>
                <th scope="col"><?= $this->Paginator->sort('company_id') ?></th>
                <th scope="col" class="actions"><?= __('Actions') ?></th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($financialYears as $financialYear): ?>
            <tr>
                <td><?= $this->Number->format($financialYear->id) ?></td>
                <td><?= h($financialYear->fy_from) ?></td>
                <td><?= h($financialYear->fy_to) ?></td>
                <td><?= h($financialYear->status) ?></td>
                <td><?= $this->Number->format($financialYear->company_id) ?></td>
                <td class="actions">
                    <?= $this->Html->link(__('View'), ['action' => 'view', $financialYear->id]) ?>
                    <?= $this->Html->link(__('Edit'), ['action' => 'edit', $financialYear->id]) ?>
                    <?= $this->Form->postLink(__('Delete'), ['action' => 'delete', $financialYear->id], ['confirm' => __('Are you sure you want to delete # {0}?', $financialYear->id)]) ?>
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
