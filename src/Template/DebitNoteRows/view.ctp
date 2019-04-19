<?php
/**
  * @var \App\View\AppView $this
  * @var \App\Model\Entity\DebitNoteRow $debitNoteRow
  */
?>
<nav class="large-3 medium-4 columns" id="actions-sidebar">
    <ul class="side-nav">
        <li class="heading"><?= __('Actions') ?></li>
        <li><?= $this->Html->link(__('Edit Debit Note Row'), ['action' => 'edit', $debitNoteRow->id]) ?> </li>
        <li><?= $this->Form->postLink(__('Delete Debit Note Row'), ['action' => 'delete', $debitNoteRow->id], ['confirm' => __('Are you sure you want to delete # {0}?', $debitNoteRow->id)]) ?> </li>
        <li><?= $this->Html->link(__('List Debit Note Rows'), ['action' => 'index']) ?> </li>
        <li><?= $this->Html->link(__('New Debit Note Row'), ['action' => 'add']) ?> </li>
        <li><?= $this->Html->link(__('List Credit Notes'), ['controller' => 'CreditNotes', 'action' => 'index']) ?> </li>
        <li><?= $this->Html->link(__('New Credit Note'), ['controller' => 'CreditNotes', 'action' => 'add']) ?> </li>
        <li><?= $this->Html->link(__('List Ledgers'), ['controller' => 'Ledgers', 'action' => 'index']) ?> </li>
        <li><?= $this->Html->link(__('New Ledger'), ['controller' => 'Ledgers', 'action' => 'add']) ?> </li>
        <li><?= $this->Html->link(__('List Accounting Entries'), ['controller' => 'AccountingEntries', 'action' => 'index']) ?> </li>
        <li><?= $this->Html->link(__('New Accounting Entry'), ['controller' => 'AccountingEntries', 'action' => 'add']) ?> </li>
        <li><?= $this->Html->link(__('List Reference Details'), ['controller' => 'ReferenceDetails', 'action' => 'index']) ?> </li>
        <li><?= $this->Html->link(__('New Reference Detail'), ['controller' => 'ReferenceDetails', 'action' => 'add']) ?> </li>
    </ul>
</nav>
<div class="debitNoteRows view large-9 medium-8 columns content">
    <h3><?= h($debitNoteRow->id) ?></h3>
    <table class="vertical-table">
        <tr>
            <th scope="row"><?= __('Credit Note') ?></th>
            <td><?= $debitNoteRow->has('credit_note') ? $this->Html->link($debitNoteRow->credit_note->id, ['controller' => 'CreditNotes', 'action' => 'view', $debitNoteRow->credit_note->id]) : '' ?></td>
        </tr>
        <tr>
            <th scope="row"><?= __('Cr Dr') ?></th>
            <td><?= h($debitNoteRow->cr_dr) ?></td>
        </tr>
        <tr>
            <th scope="row"><?= __('Ledger') ?></th>
            <td><?= $debitNoteRow->has('ledger') ? $this->Html->link($debitNoteRow->ledger->name, ['controller' => 'Ledgers', 'action' => 'view', $debitNoteRow->ledger->id]) : '' ?></td>
        </tr>
        <tr>
            <th scope="row"><?= __('Mode Of Payment') ?></th>
            <td><?= h($debitNoteRow->mode_of_payment) ?></td>
        </tr>
        <tr>
            <th scope="row"><?= __('Cheque No') ?></th>
            <td><?= h($debitNoteRow->cheque_no) ?></td>
        </tr>
        <tr>
            <th scope="row"><?= __('Id') ?></th>
            <td><?= $this->Number->format($debitNoteRow->id) ?></td>
        </tr>
        <tr>
            <th scope="row"><?= __('Debit') ?></th>
            <td><?= $this->Number->format($debitNoteRow->debit) ?></td>
        </tr>
        <tr>
            <th scope="row"><?= __('Credit') ?></th>
            <td><?= $this->Number->format($debitNoteRow->credit) ?></td>
        </tr>
        <tr>
            <th scope="row"><?= __('Cheque Date') ?></th>
            <td><?= h($debitNoteRow->cheque_date) ?></td>
        </tr>
    </table>
    <div class="related">
        <h4><?= __('Related Accounting Entries') ?></h4>
        <?php if (!empty($debitNoteRow->accounting_entries)): ?>
        <table cellpadding="0" cellspacing="0">
            <tr>
                <th scope="col"><?= __('Id') ?></th>
                <th scope="col"><?= __('Ledger Id') ?></th>
                <th scope="col"><?= __('Debit') ?></th>
                <th scope="col"><?= __('Credit') ?></th>
                <th scope="col"><?= __('Transaction Date') ?></th>
                <th scope="col"><?= __('Company Id') ?></th>
                <th scope="col"><?= __('Purchase Voucher Id') ?></th>
                <th scope="col"><?= __('Purchase Voucher Row Id') ?></th>
                <th scope="col"><?= __('Is Opening Balance') ?></th>
                <th scope="col"><?= __('Sales Invoice Id') ?></th>
                <th scope="col"><?= __('Credit Note Id') ?></th>
                <th scope="col"><?= __('Credit Note Row Id') ?></th>
                <th scope="col"><?= __('Receipt Id') ?></th>
                <th scope="col"><?= __('Receipt Row Id') ?></th>
                <th scope="col"><?= __('Payment Id') ?></th>
                <th scope="col"><?= __('Payment Row Id') ?></th>
                <th scope="col"><?= __('Debit Note Id') ?></th>
                <th scope="col"><?= __('Debit Note Row Id') ?></th>
                <th scope="col" class="actions"><?= __('Actions') ?></th>
            </tr>
            <?php foreach ($debitNoteRow->accounting_entries as $accountingEntries): ?>
            <tr>
                <td><?= h($accountingEntries->id) ?></td>
                <td><?= h($accountingEntries->ledger_id) ?></td>
                <td><?= h($accountingEntries->debit) ?></td>
                <td><?= h($accountingEntries->credit) ?></td>
                <td><?= h($accountingEntries->transaction_date) ?></td>
                <td><?= h($accountingEntries->company_id) ?></td>
                <td><?= h($accountingEntries->purchase_voucher_id) ?></td>
                <td><?= h($accountingEntries->purchase_voucher_row_id) ?></td>
                <td><?= h($accountingEntries->is_opening_balance) ?></td>
                <td><?= h($accountingEntries->sales_invoice_id) ?></td>
                <td><?= h($accountingEntries->credit_note_id) ?></td>
                <td><?= h($accountingEntries->credit_note_row_id) ?></td>
                <td><?= h($accountingEntries->receipt_id) ?></td>
                <td><?= h($accountingEntries->receipt_row_id) ?></td>
                <td><?= h($accountingEntries->payment_id) ?></td>
                <td><?= h($accountingEntries->payment_row_id) ?></td>
                <td><?= h($accountingEntries->debit_note_id) ?></td>
                <td><?= h($accountingEntries->debit_note_row_id) ?></td>
                <td class="actions">
                    <?= $this->Html->link(__('View'), ['controller' => 'AccountingEntries', 'action' => 'view', $accountingEntries->id]) ?>
                    <?= $this->Html->link(__('Edit'), ['controller' => 'AccountingEntries', 'action' => 'edit', $accountingEntries->id]) ?>
                    <?= $this->Form->postLink(__('Delete'), ['controller' => 'AccountingEntries', 'action' => 'delete', $accountingEntries->id], ['confirm' => __('Are you sure you want to delete # {0}?', $accountingEntries->id)]) ?>
                </td>
            </tr>
            <?php endforeach; ?>
        </table>
        <?php endif; ?>
    </div>
    <div class="related">
        <h4><?= __('Related Reference Details') ?></h4>
        <?php if (!empty($debitNoteRow->reference_details)): ?>
        <table cellpadding="0" cellspacing="0">
            <tr>
                <th scope="col"><?= __('Id') ?></th>
                <th scope="col"><?= __('Company Id') ?></th>
                <th scope="col"><?= __('Ledger Id') ?></th>
                <th scope="col"><?= __('Type') ?></th>
                <th scope="col"><?= __('Ref Name') ?></th>
                <th scope="col"><?= __('Debit') ?></th>
                <th scope="col"><?= __('Credit') ?></th>
                <th scope="col"><?= __('Receipt Id') ?></th>
                <th scope="col"><?= __('Receipt Row Id') ?></th>
                <th scope="col"><?= __('Payment Id') ?></th>
                <th scope="col"><?= __('Payment Row Id') ?></th>
                <th scope="col"><?= __('Sales Voucher Id') ?></th>
                <th scope="col"><?= __('Sales Voucher Row Id') ?></th>
                <th scope="col"><?= __('Credit Note Id') ?></th>
                <th scope="col"><?= __('Credit Note Row Id') ?></th>
                <th scope="col"><?= __('Debit Note Id') ?></th>
                <th scope="col"><?= __('Debit Note Row Id') ?></th>
                <th scope="col" class="actions"><?= __('Actions') ?></th>
            </tr>
            <?php foreach ($debitNoteRow->reference_details as $referenceDetails): ?>
            <tr>
                <td><?= h($referenceDetails->id) ?></td>
                <td><?= h($referenceDetails->company_id) ?></td>
                <td><?= h($referenceDetails->ledger_id) ?></td>
                <td><?= h($referenceDetails->type) ?></td>
                <td><?= h($referenceDetails->ref_name) ?></td>
                <td><?= h($referenceDetails->debit) ?></td>
                <td><?= h($referenceDetails->credit) ?></td>
                <td><?= h($referenceDetails->receipt_id) ?></td>
                <td><?= h($referenceDetails->receipt_row_id) ?></td>
                <td><?= h($referenceDetails->payment_id) ?></td>
                <td><?= h($referenceDetails->payment_row_id) ?></td>
                <td><?= h($referenceDetails->sales_voucher_id) ?></td>
                <td><?= h($referenceDetails->sales_voucher_row_id) ?></td>
                <td><?= h($referenceDetails->credit_note_id) ?></td>
                <td><?= h($referenceDetails->credit_note_row_id) ?></td>
                <td><?= h($referenceDetails->debit_note_id) ?></td>
                <td><?= h($referenceDetails->debit_note_row_id) ?></td>
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
