<?php
/**
  * @var \App\View\AppView $this
  * @var \App\Model\Entity\ReceiptRow[]|\Cake\Collection\CollectionInterface $receiptRows
  */
?>
<nav class="large-3 medium-4 columns" id="actions-sidebar">
    <ul class="side-nav">
        <li class="heading"><?= __('Actions') ?></li>
        <li><?= $this->Html->link(__('New Receipt Row'), ['action' => 'add']) ?></li>
        <li><?= $this->Html->link(__('List Receipts'), ['controller' => 'Receipts', 'action' => 'index']) ?></li>
        <li><?= $this->Html->link(__('New Receipt'), ['controller' => 'Receipts', 'action' => 'add']) ?></li>
        <li><?= $this->Html->link(__('List Companies'), ['controller' => 'Companies', 'action' => 'index']) ?></li>
        <li><?= $this->Html->link(__('New Company'), ['controller' => 'Companies', 'action' => 'add']) ?></li>
        <li><?= $this->Html->link(__('List Ledgers'), ['controller' => 'Ledgers', 'action' => 'index']) ?></li>
        <li><?= $this->Html->link(__('New Ledger'), ['controller' => 'Ledgers', 'action' => 'add']) ?></li>
        <li><?= $this->Html->link(__('List Reference Details'), ['controller' => 'ReferenceDetails', 'action' => 'index']) ?></li>
        <li><?= $this->Html->link(__('New Reference Detail'), ['controller' => 'ReferenceDetails', 'action' => 'add']) ?></li>
    </ul>
</nav>
<div class="receiptRows index large-9 medium-8 columns content">
    <h3><?= __('Receipt Rows') ?></h3>
    <table cellpadding="0" cellspacing="0">
        <thead>
            <tr>
                <th scope="col"><?= $this->Paginator->sort('id') ?></th>
                <th scope="col"><?= $this->Paginator->sort('receipt_id') ?></th>
                <th scope="col"><?= $this->Paginator->sort('company_id') ?></th>
                <th scope="col"><?= $this->Paginator->sort('type') ?></th>
                <th scope="col"><?= $this->Paginator->sort('ledger_id') ?></th>
                <th scope="col"><?= $this->Paginator->sort('debit') ?></th>
                <th scope="col"><?= $this->Paginator->sort('credit') ?></th>
                <th scope="col"><?= $this->Paginator->sort('mode_of_payment') ?></th>
                <th scope="col"><?= $this->Paginator->sort('cheque_no') ?></th>
                <th scope="col"><?= $this->Paginator->sort('cheque_date') ?></th>
                <th scope="col" class="actions"><?= __('Actions') ?></th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($receiptRows as $receiptRow): ?>
            <tr>
                <td><?= $this->Number->format($receiptRow->id) ?></td>
                <td><?= $receiptRow->has('receipt') ? $this->Html->link($receiptRow->receipt->id, ['controller' => 'Receipts', 'action' => 'view', $receiptRow->receipt->id]) : '' ?></td>
                <td><?= $receiptRow->has('company') ? $this->Html->link($receiptRow->company->name, ['controller' => 'Companies', 'action' => 'view', $receiptRow->company->id]) : '' ?></td>
                <td><?= h($receiptRow->type) ?></td>
                <td><?= $receiptRow->has('ledger') ? $this->Html->link($receiptRow->ledger->name, ['controller' => 'Ledgers', 'action' => 'view', $receiptRow->ledger->id]) : '' ?></td>
                <td><?= $this->Number->format($receiptRow->debit) ?></td>
                <td><?= $this->Number->format($receiptRow->credit) ?></td>
                <td><?= h($receiptRow->mode_of_payment) ?></td>
                <td><?= h($receiptRow->cheque_no) ?></td>
                <td><?= h($receiptRow->cheque_date) ?></td>
                <td class="actions">
                    <?= $this->Html->link(__('View'), ['action' => 'view', $receiptRow->id]) ?>
                    <?= $this->Html->link(__('Edit'), ['action' => 'edit', $receiptRow->id]) ?>
                    <?= $this->Form->postLink(__('Delete'), ['action' => 'delete', $receiptRow->id], ['confirm' => __('Are you sure you want to delete # {0}?', $receiptRow->id)]) ?>
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
