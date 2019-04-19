<?php
/**
  * @var \App\View\AppView $this
  * @var \App\Model\Entity\ReferenceDetail[]|\Cake\Collection\CollectionInterface $referenceDetails
  */
?>
<nav class="large-3 medium-4 columns" id="actions-sidebar">
    <ul class="side-nav">
        <li class="heading"><?= __('Actions') ?></li>
        <li><?= $this->Html->link(__('New Reference Detail'), ['action' => 'add']) ?></li>
        <li><?= $this->Html->link(__('List Companies'), ['controller' => 'Companies', 'action' => 'index']) ?></li>
        <li><?= $this->Html->link(__('New Company'), ['controller' => 'Companies', 'action' => 'add']) ?></li>
        <li><?= $this->Html->link(__('List Ledgers'), ['controller' => 'Ledgers', 'action' => 'index']) ?></li>
        <li><?= $this->Html->link(__('New Ledger'), ['controller' => 'Ledgers', 'action' => 'add']) ?></li>
        <li><?= $this->Html->link(__('List Receipts'), ['controller' => 'Receipts', 'action' => 'index']) ?></li>
        <li><?= $this->Html->link(__('New Receipt'), ['controller' => 'Receipts', 'action' => 'add']) ?></li>
        <li><?= $this->Html->link(__('List Receipt Rows'), ['controller' => 'ReceiptRows', 'action' => 'index']) ?></li>
        <li><?= $this->Html->link(__('New Receipt Row'), ['controller' => 'ReceiptRows', 'action' => 'add']) ?></li>
    </ul>
</nav>
<div class="referenceDetails index large-9 medium-8 columns content">
    <h3><?= __('Reference Details') ?></h3>
    <table cellpadding="0" cellspacing="0">
        <thead>
            <tr>
                <th scope="col"><?= $this->Paginator->sort('id') ?></th>
                <th scope="col"><?= $this->Paginator->sort('company_id') ?></th>
                <th scope="col"><?= $this->Paginator->sort('ledger_id') ?></th>
                <th scope="col"><?= $this->Paginator->sort('type') ?></th>
                <th scope="col"><?= $this->Paginator->sort('name') ?></th>
                <th scope="col"><?= $this->Paginator->sort('debit') ?></th>
                <th scope="col"><?= $this->Paginator->sort('credit') ?></th>
                <th scope="col"><?= $this->Paginator->sort('receipt_id') ?></th>
                <th scope="col"><?= $this->Paginator->sort('receipt_row_id') ?></th>
                <th scope="col"><?= $this->Paginator->sort('payment_id') ?></th>
                <th scope="col"><?= $this->Paginator->sort('payment_row_id') ?></th>
                <th scope="col" class="actions"><?= __('Actions') ?></th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($referenceDetails as $referenceDetail): ?>
            <tr>
                <td><?= $this->Number->format($referenceDetail->id) ?></td>
                <td><?= $referenceDetail->has('company') ? $this->Html->link($referenceDetail->company->name, ['controller' => 'Companies', 'action' => 'view', $referenceDetail->company->id]) : '' ?></td>
                <td><?= $referenceDetail->has('ledger') ? $this->Html->link($referenceDetail->ledger->name, ['controller' => 'Ledgers', 'action' => 'view', $referenceDetail->ledger->id]) : '' ?></td>
                <td><?= h($referenceDetail->type) ?></td>
                <td><?= h($referenceDetail->name) ?></td>
                <td><?= $this->Number->format($referenceDetail->debit) ?></td>
                <td><?= $this->Number->format($referenceDetail->credit) ?></td>
                <td><?= $referenceDetail->has('receipt') ? $this->Html->link($referenceDetail->receipt->id, ['controller' => 'Receipts', 'action' => 'view', $referenceDetail->receipt->id]) : '' ?></td>
                <td><?= $referenceDetail->has('receipt_row') ? $this->Html->link($referenceDetail->receipt_row->id, ['controller' => 'ReceiptRows', 'action' => 'view', $referenceDetail->receipt_row->id]) : '' ?></td>
                <td><?= $this->Number->format($referenceDetail->payment_id) ?></td>
                <td><?= $this->Number->format($referenceDetail->payment_row_id) ?></td>
                <td class="actions">
                    <?= $this->Html->link(__('View'), ['action' => 'view', $referenceDetail->id]) ?>
                    <?= $this->Html->link(__('Edit'), ['action' => 'edit', $referenceDetail->id]) ?>
                    <?= $this->Form->postLink(__('Delete'), ['action' => 'delete', $referenceDetail->id], ['confirm' => __('Are you sure you want to delete # {0}?', $referenceDetail->id)]) ?>
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
