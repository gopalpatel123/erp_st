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
                ['action' => 'delete', $purchaseReturn->id],
                ['confirm' => __('Are you sure you want to delete # {0}?', $purchaseReturn->id)]
            )
        ?></li>
        <li><?= $this->Html->link(__('List Purchase Returns'), ['action' => 'index']) ?></li>
        <li><?= $this->Html->link(__('List Purchase Invoices'), ['controller' => 'PurchaseInvoices', 'action' => 'index']) ?></li>
        <li><?= $this->Html->link(__('New Purchase Invoice'), ['controller' => 'PurchaseInvoices', 'action' => 'add']) ?></li>
        <li><?= $this->Html->link(__('List Companies'), ['controller' => 'Companies', 'action' => 'index']) ?></li>
        <li><?= $this->Html->link(__('New Company'), ['controller' => 'Companies', 'action' => 'add']) ?></li>
        <li><?= $this->Html->link(__('List Purchase Return Rows'), ['controller' => 'PurchaseReturnRows', 'action' => 'index']) ?></li>
        <li><?= $this->Html->link(__('New Purchase Return Row'), ['controller' => 'PurchaseReturnRows', 'action' => 'add']) ?></li>
    </ul>
</nav>
<div class="purchaseReturns form large-9 medium-8 columns content">
    <?= $this->Form->create($purchaseReturn) ?>
    <fieldset>
        <legend><?= __('Edit Purchase Return') ?></legend>
        <?php
            echo $this->Form->control('purchase_invoice_id', ['options' => $purchaseInvoices]);
            echo $this->Form->control('voucher_no');
            echo $this->Form->control('company_id', ['options' => $companies]);
            echo $this->Form->control('transaction_date');
            echo $this->Form->control('total_amount');
        ?>
    </fieldset>
    <?= $this->Form->button(__('Submit')) ?>
    <?= $this->Form->end() ?>
</div>
