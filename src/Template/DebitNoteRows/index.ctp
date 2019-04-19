<?php
/**
  * @var \App\View\AppView $this
  * @var \App\Model\Entity\DebitNoteRow[]|\Cake\Collection\CollectionInterface $debitNoteRows
  */
?>
<nav class="large-3 medium-4 columns" id="actions-sidebar">
    <ul class="side-nav">
        <li class="heading"><?= __('Actions') ?></li>
        <li><?= $this->Html->link(__('New Debit Note Row'), ['action' => 'add']) ?></li>
        <li><?= $this->Html->link(__('List Credit Notes'), ['controller' => 'CreditNotes', 'action' => 'index']) ?></li>
        <li><?= $this->Html->link(__('New Credit Note'), ['controller' => 'CreditNotes', 'action' => 'add']) ?></li>
        <li><?= $this->Html->link(__('List Ledgers'), ['controller' => 'Ledgers', 'action' => 'index']) ?></li>
        <li><?= $this->Html->link(__('New Ledger'), ['controller' => 'Ledgers', 'action' => 'add']) ?></li>
        <li><?= $this->Html->link(__('List Accounting Entries'), ['controller' => 'AccountingEntries', 'action' => 'index']) ?></li>
        <li><?= $this->Html->link(__('New Accounting Entry'), ['controller' => 'AccountingEntries', 'action' => 'add']) ?></li>
        <li><?= $this->Html->link(__('List Reference Details'), ['controller' => 'ReferenceDetails', 'action' => 'index']) ?></li>
        <li><?= $this->Html->link(__('New Reference Detail'), ['controller' => 'ReferenceDetails', 'action' => 'add']) ?></li>
    </ul>
</nav>
<div class="debitNoteRows index large-9 medium-8 columns content">
    <h3><?= __('Debit Note Rows') ?></h3>
    <table cellpadding="0" cellspacing="0">
        <thead>
            <tr>
                <th scope="col"><?= $this->Paginator->sort('id') ?></th>
                <th scope="col"><?= $this->Paginator->sort('credit_note_id') ?></th>
                <th scope="col"><?= $this->Paginator->sort('cr_dr') ?></th>
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
            <?php foreach ($debitNoteRows as $debitNoteRow): ?>
            <tr>
                <td><?= $this->Number->format($debitNoteRow->id) ?></td>
                <td><?= $debitNoteRow->has('credit_note') ? $this->Html->link($debitNoteRow->credit_note->id, ['controller' => 'CreditNotes', 'action' => 'view', $debitNoteRow->credit_note->id]) : '' ?></td>
                <td><?= h($debitNoteRow->cr_dr) ?></td>
                <td><?= $debitNoteRow->has('ledger') ? $this->Html->link($debitNoteRow->ledger->name, ['controller' => 'Ledgers', 'action' => 'view', $debitNoteRow->ledger->id]) : '' ?></td>
                <td><?= $this->Number->format($debitNoteRow->debit) ?></td>
                <td><?= $this->Number->format($debitNoteRow->credit) ?></td>
                <td><?= h($debitNoteRow->mode_of_payment) ?></td>
                <td><?= h($debitNoteRow->cheque_no) ?></td>
                <td><?= h($debitNoteRow->cheque_date) ?></td>
                <td class="actions">
                    <?= $this->Html->link(__('View'), ['action' => 'view', $debitNoteRow->id]) ?>
                    <?= $this->Html->link(__('Edit'), ['action' => 'edit', $debitNoteRow->id]) ?>
                    <?= $this->Form->postLink(__('Delete'), ['action' => 'delete', $debitNoteRow->id], ['confirm' => __('Are you sure you want to delete # {0}?', $debitNoteRow->id)]) ?>
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
