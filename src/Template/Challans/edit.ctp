<?php
/**
  * @var \App\View\AppView $this
  */
?>
<nav class="large-3 medium-4 columns" id="actions-sidebar">
    <ul class="side-nav">
        <li class="heading"><?= __('Actions') ?></li>
        <li><?= $this->Form->postLink(
                __('Delete'),
                ['action' => 'delete', $challan->id],
                ['confirm' => __('Are you sure you want to delete # {0}?', $challan->id)]
            )
        ?></li>
        <li><?= $this->Html->link(__('List Challans'), ['action' => 'index']) ?></li>
        <li><?= $this->Html->link(__('List Financial Years'), ['controller' => 'FinancialYears', 'action' => 'index']) ?></li>
        <li><?= $this->Html->link(__('New Financial Year'), ['controller' => 'FinancialYears', 'action' => 'add']) ?></li>
        <li><?= $this->Html->link(__('List Companies'), ['controller' => 'Companies', 'action' => 'index']) ?></li>
        <li><?= $this->Html->link(__('New Company'), ['controller' => 'Companies', 'action' => 'add']) ?></li>
        <li><?= $this->Html->link(__('List Customers'), ['controller' => 'Customers', 'action' => 'index']) ?></li>
        <li><?= $this->Html->link(__('New Customer'), ['controller' => 'Customers', 'action' => 'add']) ?></li>
        <li><?= $this->Html->link(__('List Sales Ledgers'), ['controller' => 'Ledgers', 'action' => 'index']) ?></li>
        <li><?= $this->Html->link(__('New Sales Ledger'), ['controller' => 'Ledgers', 'action' => 'add']) ?></li>
        <li><?= $this->Html->link(__('List Locations'), ['controller' => 'Locations', 'action' => 'index']) ?></li>
        <li><?= $this->Html->link(__('New Location'), ['controller' => 'Locations', 'action' => 'add']) ?></li>
        <li><?= $this->Html->link(__('List Challan Rows'), ['controller' => 'ChallanRows', 'action' => 'index']) ?></li>
        <li><?= $this->Html->link(__('New Challan Row'), ['controller' => 'ChallanRows', 'action' => 'add']) ?></li>
    </ul>
</nav>
<div class="challans form large-9 medium-8 columns content">
    <?= $this->Form->create($challan) ?>
    <fieldset>
        <legend><?= __('Edit Challan') ?></legend>
        <?php
            echo $this->Form->control('voucher_no');
            echo $this->Form->control('financial_year_id', ['options' => $financialYears, 'empty' => true]);
            echo $this->Form->control('company_id', ['options' => $companies]);
            echo $this->Form->control('transaction_date');
            echo $this->Form->control('customer_id', ['options' => $customers]);
            echo $this->Form->control('amount_before_tax');
            echo $this->Form->control('total_cgst');
            echo $this->Form->control('total_sgst');
            echo $this->Form->control('total_igst');
            echo $this->Form->control('amount_after_tax');
            echo $this->Form->control('round_off');
            echo $this->Form->control('sales_ledger_id', ['options' => $salesLedgers]);
            echo $this->Form->control('party_ledger_id', ['options' => $partyLedgers]);
            echo $this->Form->control('location_id', ['options' => $locations]);
            echo $this->Form->control('invoice_receipt_type');
            echo $this->Form->control('receipt_amount');
            echo $this->Form->control('discount_amount');
            echo $this->Form->control('status');
        ?>
    </fieldset>
    <?= $this->Form->button(__('Submit')) ?>
    <?= $this->Form->end() ?>
</div>
