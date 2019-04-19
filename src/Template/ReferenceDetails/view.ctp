<?php
/**
  * @var \App\View\AppView $this
  * @var \App\Model\Entity\ReferenceDetail $referenceDetail
  */
?>
<nav class="large-3 medium-4 columns" id="actions-sidebar">
    <ul class="side-nav">
        <li class="heading"><?= __('Actions') ?></li>
        <li><?= $this->Html->link(__('Edit Reference Detail'), ['action' => 'edit', $referenceDetail->id]) ?> </li>
        <li><?= $this->Form->postLink(__('Delete Reference Detail'), ['action' => 'delete', $referenceDetail->id], ['confirm' => __('Are you sure you want to delete # {0}?', $referenceDetail->id)]) ?> </li>
        <li><?= $this->Html->link(__('List Reference Details'), ['action' => 'index']) ?> </li>
        <li><?= $this->Html->link(__('New Reference Detail'), ['action' => 'add']) ?> </li>
        <li><?= $this->Html->link(__('List Companies'), ['controller' => 'Companies', 'action' => 'index']) ?> </li>
        <li><?= $this->Html->link(__('New Company'), ['controller' => 'Companies', 'action' => 'add']) ?> </li>
        <li><?= $this->Html->link(__('List Ledgers'), ['controller' => 'Ledgers', 'action' => 'index']) ?> </li>
        <li><?= $this->Html->link(__('New Ledger'), ['controller' => 'Ledgers', 'action' => 'add']) ?> </li>
        <li><?= $this->Html->link(__('List Receipts'), ['controller' => 'Receipts', 'action' => 'index']) ?> </li>
        <li><?= $this->Html->link(__('New Receipt'), ['controller' => 'Receipts', 'action' => 'add']) ?> </li>
        <li><?= $this->Html->link(__('List Receipt Rows'), ['controller' => 'ReceiptRows', 'action' => 'index']) ?> </li>
        <li><?= $this->Html->link(__('New Receipt Row'), ['controller' => 'ReceiptRows', 'action' => 'add']) ?> </li>
    </ul>
</nav>
<div class="referenceDetails view large-9 medium-8 columns content">
    <h3><?= h($referenceDetail->name) ?></h3>
    <table class="vertical-table">
        <tr>
            <th scope="row"><?= __('Company') ?></th>
            <td><?= $referenceDetail->has('company') ? $this->Html->link($referenceDetail->company->name, ['controller' => 'Companies', 'action' => 'view', $referenceDetail->company->id]) : '' ?></td>
        </tr>
        <tr>
            <th scope="row"><?= __('Ledger') ?></th>
            <td><?= $referenceDetail->has('ledger') ? $this->Html->link($referenceDetail->ledger->name, ['controller' => 'Ledgers', 'action' => 'view', $referenceDetail->ledger->id]) : '' ?></td>
        </tr>
        <tr>
            <th scope="row"><?= __('Type') ?></th>
            <td><?= h($referenceDetail->type) ?></td>
        </tr>
        <tr>
            <th scope="row"><?= __('Name') ?></th>
            <td><?= h($referenceDetail->name) ?></td>
        </tr>
        <tr>
            <th scope="row"><?= __('Receipt') ?></th>
            <td><?= $referenceDetail->has('receipt') ? $this->Html->link($referenceDetail->receipt->id, ['controller' => 'Receipts', 'action' => 'view', $referenceDetail->receipt->id]) : '' ?></td>
        </tr>
        <tr>
            <th scope="row"><?= __('Receipt Row') ?></th>
            <td><?= $referenceDetail->has('receipt_row') ? $this->Html->link($referenceDetail->receipt_row->id, ['controller' => 'ReceiptRows', 'action' => 'view', $referenceDetail->receipt_row->id]) : '' ?></td>
        </tr>
        <tr>
            <th scope="row"><?= __('Id') ?></th>
            <td><?= $this->Number->format($referenceDetail->id) ?></td>
        </tr>
        <tr>
            <th scope="row"><?= __('Debit') ?></th>
            <td><?= $this->Number->format($referenceDetail->debit) ?></td>
        </tr>
        <tr>
            <th scope="row"><?= __('Credit') ?></th>
            <td><?= $this->Number->format($referenceDetail->credit) ?></td>
        </tr>
        <tr>
            <th scope="row"><?= __('Payment Id') ?></th>
            <td><?= $this->Number->format($referenceDetail->payment_id) ?></td>
        </tr>
        <tr>
            <th scope="row"><?= __('Payment Row Id') ?></th>
            <td><?= $this->Number->format($referenceDetail->payment_row_id) ?></td>
        </tr>
    </table>
</div>
