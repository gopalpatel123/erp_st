<?php
/**
  * @var \App\View\AppView $this
  */
?>
<nav class="large-3 medium-4 columns" id="actions-sidebar">
    <ul class="side-nav">
        <li class="heading"><?= __('Actions') ?></li>
        <li><?= $this->Html->link(__('List Second Tamp Grn Records'), ['action' => 'index']) ?></li>
        <li><?= $this->Html->link(__('List Users'), ['controller' => 'Users', 'action' => 'index']) ?></li>
        <li><?= $this->Html->link(__('New User'), ['controller' => 'Users', 'action' => 'add']) ?></li>
    </ul>
</nav>
<div class="secondTampGrnRecords form large-9 medium-8 columns content">
    <?= $this->Form->create($secondTampGrnRecord) ?>
    <fieldset>
        <legend><?= __('Add Second Tamp Grn Record') ?></legend>
        <?php
            echo $this->Form->control('item_code');
            echo $this->Form->control('quantity');
            echo $this->Form->control('purchase_rate');
            echo $this->Form->control('sales_rate');
            echo $this->Form->control('user_id', ['options' => $users]);
            echo $this->Form->control('processed');
            echo $this->Form->control('is_addition_item_data_required');
            echo $this->Form->control('item_name');
            echo $this->Form->control('hsn_code');
            echo $this->Form->control('unit');
            echo $this->Form->control('gst_rate_fixed_or_fluid');
            echo $this->Form->control('first_gst_rate');
            echo $this->Form->control('amount_in_ref_of_gst_rate');
            echo $this->Form->control('second_gst_rate');
        ?>
    </fieldset>
    <?= $this->Form->button(__('Submit')) ?>
    <?= $this->Form->end() ?>
</div>
