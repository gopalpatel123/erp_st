<?php
/**
  * @var \App\View\AppView $this
  * @var \App\Model\Entity\SalesVoucherRow $salesVoucherRow
  */
?>
<nav class="large-3 medium-4 columns" id="actions-sidebar">
    <ul class="side-nav">
        <li class="heading"><?= __('Actions') ?></li>
        <li><?= $this->Html->link(__('Edit Sales Voucher Row'), ['action' => 'edit', $salesVoucherRow->id]) ?> </li>
        <li><?= $this->Form->postLink(__('Delete Sales Voucher Row'), ['action' => 'delete', $salesVoucherRow->id], ['confirm' => __('Are you sure you want to delete # {0}?', $salesVoucherRow->id)]) ?> </li>
        <li><?= $this->Html->link(__('List Sales Voucher Rows'), ['action' => 'index']) ?> </li>
        <li><?= $this->Html->link(__('New Sales Voucher Row'), ['action' => 'add']) ?> </li>
        <li><?= $this->Html->link(__('List Sales Vouchers'), ['controller' => 'SalesVouchers', 'action' => 'index']) ?> </li>
        <li><?= $this->Html->link(__('New Sales Voucher'), ['controller' => 'SalesVouchers', 'action' => 'add']) ?> </li>
        <li><?= $this->Html->link(__('List Ledgers'), ['controller' => 'Ledgers', 'action' => 'index']) ?> </li>
        <li><?= $this->Html->link(__('New Ledger'), ['controller' => 'Ledgers', 'action' => 'add']) ?> </li>
        <li><?= $this->Html->link(__('List Reference Details'), ['controller' => 'ReferenceDetails', 'action' => 'index']) ?> </li>
        <li><?= $this->Html->link(__('New Reference Detail'), ['controller' => 'ReferenceDetails', 'action' => 'add']) ?> </li>
    </ul>
</nav>
<div class="salesVoucherRows view large-9 medium-8 columns content">
    <h3><?= h($salesVoucherRow->id) ?></h3>
    <table class="vertical-table">
        <tr>
            <th scope="row"><?= __('Sales Voucher') ?></th>
            <td><?= $salesVoucherRow->has('sales_voucher') ? $this->Html->link($salesVoucherRow->sales_voucher->id, ['controller' => 'SalesVouchers', 'action' => 'view', $salesVoucherRow->sales_voucher->id]) : '' ?></td>
        </tr>
        <tr>
            <th scope="row"><?= __('Cr Dr') ?></th>
            <td><?= h($salesVoucherRow->cr_dr) ?></td>
        </tr>
        <tr>
            <th scope="row"><?= __('Ledger') ?></th>
            <td><?= $salesVoucherRow->has('ledger') ? $this->Html->link($salesVoucherRow->ledger->name, ['controller' => 'Ledgers', 'action' => 'view', $salesVoucherRow->ledger->id]) : '' ?></td>
        </tr>
        <tr>
            <th scope="row"><?= __('Mode Of Payment') ?></th>
            <td><?= h($salesVoucherRow->mode_of_payment) ?></td>
        </tr>
        <tr>
            <th scope="row"><?= __('Cheque No') ?></th>
            <td><?= h($salesVoucherRow->cheque_no) ?></td>
        </tr>
        <tr>
            <th scope="row"><?= __('Id') ?></th>
            <td><?= $this->Number->format($salesVoucherRow->id) ?></td>
        </tr>
        <tr>
            <th scope="row"><?= __('Debit') ?></th>
            <td><?= $this->Number->format($salesVoucherRow->debit) ?></td>
        </tr>
        <tr>
            <th scope="row"><?= __('Credit') ?></th>
            <td><?= $this->Number->format($salesVoucherRow->credit) ?></td>
        </tr>
        <tr>
            <th scope="row"><?= __('Cheque Date') ?></th>
            <td><?= h($salesVoucherRow->cheque_date) ?></td>
        </tr>
    </table>
    <div class="related">
        <h4><?= __('Related Reference Details') ?></h4>
        <?php if (!empty($salesVoucherRow->reference_details)): ?>
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
                <th scope="col"><?= __('Payment Row Id') ?></th>
                <th scope="col"><?= __('Sales Voucher Row Id') ?></th>
                <th scope="col" class="actions"><?= __('Actions') ?></th>
            </tr>
            <?php foreach ($salesVoucherRow->reference_details as $referenceDetails): ?>
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
                <td><?= h($referenceDetails->payment_row_id) ?></td>
                <td><?= h($referenceDetails->sales_voucher_row_id) ?></td>
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
