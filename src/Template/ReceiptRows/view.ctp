<?php
/**
  * @var \App\View\AppView $this
  * @var \App\Model\Entity\ReceiptRow $receiptRow
  */
?>
<nav class="large-3 medium-4 columns" id="actions-sidebar">
    <ul class="side-nav">
        <li class="heading"><?= __('Actions') ?></li>
        <li><?= $this->Html->link(__('Edit Receipt Row'), ['action' => 'edit', $receiptRow->id]) ?> </li>
        <li><?= $this->Form->postLink(__('Delete Receipt Row'), ['action' => 'delete', $receiptRow->id], ['confirm' => __('Are you sure you want to delete # {0}?', $receiptRow->id)]) ?> </li>
        <li><?= $this->Html->link(__('List Receipt Rows'), ['action' => 'index']) ?> </li>
        <li><?= $this->Html->link(__('New Receipt Row'), ['action' => 'add']) ?> </li>
        <li><?= $this->Html->link(__('List Receipts'), ['controller' => 'Receipts', 'action' => 'index']) ?> </li>
        <li><?= $this->Html->link(__('New Receipt'), ['controller' => 'Receipts', 'action' => 'add']) ?> </li>
        <li><?= $this->Html->link(__('List Companies'), ['controller' => 'Companies', 'action' => 'index']) ?> </li>
        <li><?= $this->Html->link(__('New Company'), ['controller' => 'Companies', 'action' => 'add']) ?> </li>
        <li><?= $this->Html->link(__('List Ledgers'), ['controller' => 'Ledgers', 'action' => 'index']) ?> </li>
        <li><?= $this->Html->link(__('New Ledger'), ['controller' => 'Ledgers', 'action' => 'add']) ?> </li>
        <li><?= $this->Html->link(__('List Reference Details'), ['controller' => 'ReferenceDetails', 'action' => 'index']) ?> </li>
        <li><?= $this->Html->link(__('New Reference Detail'), ['controller' => 'ReferenceDetails', 'action' => 'add']) ?> </li>
    </ul>
</nav>
<div class="receiptRows view large-9 medium-8 columns content">
    <h3><?= h($receiptRow->id) ?></h3>
    <table class="vertical-table">
        <tr>
            <th scope="row"><?= __('Receipt') ?></th>
            <td><?= $receiptRow->has('receipt') ? $this->Html->link($receiptRow->receipt->id, ['controller' => 'Receipts', 'action' => 'view', $receiptRow->receipt->id]) : '' ?></td>
        </tr>
        <tr>
            <th scope="row"><?= __('Company') ?></th>
            <td><?= $receiptRow->has('company') ? $this->Html->link($receiptRow->company->name, ['controller' => 'Companies', 'action' => 'view', $receiptRow->company->id]) : '' ?></td>
        </tr>
        <tr>
            <th scope="row"><?= __('Type') ?></th>
            <td><?= h($receiptRow->type) ?></td>
        </tr>
        <tr>
            <th scope="row"><?= __('Ledger') ?></th>
            <td><?= $receiptRow->has('ledger') ? $this->Html->link($receiptRow->ledger->name, ['controller' => 'Ledgers', 'action' => 'view', $receiptRow->ledger->id]) : '' ?></td>
        </tr>
        <tr>
            <th scope="row"><?= __('Mode Of Payment') ?></th>
            <td><?= h($receiptRow->mode_of_payment) ?></td>
        </tr>
        <tr>
            <th scope="row"><?= __('Cheque No') ?></th>
            <td><?= h($receiptRow->cheque_no) ?></td>
        </tr>
        <tr>
            <th scope="row"><?= __('Id') ?></th>
            <td><?= $this->Number->format($receiptRow->id) ?></td>
        </tr>
        <tr>
            <th scope="row"><?= __('Debit') ?></th>
            <td><?= $this->Number->format($receiptRow->debit) ?></td>
        </tr>
        <tr>
            <th scope="row"><?= __('Credit') ?></th>
            <td><?= $this->Number->format($receiptRow->credit) ?></td>
        </tr>
        <tr>
            <th scope="row"><?= __('Cheque Date') ?></th>
            <td><?= h($receiptRow->cheque_date) ?></td>
        </tr>
    </table>
    <div class="related">
        <h4><?= __('Related Reference Details') ?></h4>
        <?php if (!empty($receiptRow->reference_details)): ?>
        <table cellpadding="0" cellspacing="0">
            <tr>
                <th scope="col"><?= __('Id') ?></th>
                <th scope="col"><?= __('Company Id') ?></th>
                <th scope="col"><?= __('Ledger Id') ?></th>
                <th scope="col"><?= __('Type') ?></th>
                <th scope="col"><?= __('Name') ?></th>
                <th scope="col"><?= __('Debit') ?></th>
                <th scope="col"><?= __('Credit') ?></th>
                <th scope="col"><?= __('Receipt Id') ?></th>
                <th scope="col"><?= __('Receipt Row Id') ?></th>
                <th scope="col"><?= __('Payment Id') ?></th>
                <th scope="col"><?= __('Payment Row Id') ?></th>
                <th scope="col" class="actions"><?= __('Actions') ?></th>
            </tr>
            <?php foreach ($receiptRow->reference_details as $referenceDetails): ?>
            <tr>
                <td><?= h($referenceDetails->id) ?></td>
                <td><?= h($referenceDetails->company_id) ?></td>
                <td><?= h($referenceDetails->ledger_id) ?></td>
                <td><?= h($referenceDetails->type) ?></td>
                <td><?= h($referenceDetails->name) ?></td>
                <td><?= h($referenceDetails->debit) ?></td>
                <td><?= h($referenceDetails->credit) ?></td>
                <td><?= h($referenceDetails->receipt_id) ?></td>
                <td><?= h($referenceDetails->receipt_row_id) ?></td>
                <td><?= h($referenceDetails->payment_id) ?></td>
                <td><?= h($referenceDetails->payment_row_id) ?></td>
                <td class="actions">
                    <?= $this->Html->link(__('View'), ['controller' => 'ReferenceDetails', 'action' => 'view', $referenceDetails->id]) ?>
                    <?= $this->Html->link(__('Edit'), ['controller' => 'ReferenceDetails', 'action' => 'edit', $referenceDetails->id]) ?>
                    <?= $this->Form->postLink(__('Delete'), ['controller' => 'ReferenceDetails', 'action' => 'delete', $referenceDetails->id], ['confirm' => __('Are you sure you want to delete # {0}?', $referenceDetails->id)]) ?>
                </td>
            </tr>
            <?php endforeach; ?>
        </table>
        <?php endif; ?>
    </div>
</div>
