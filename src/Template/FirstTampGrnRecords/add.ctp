<?php
/**
  * @var \App\View\AppView $this
  */
?>
<nav class="large-3 medium-4 columns" id="actions-sidebar">
    <ul class="side-nav">
        <li class="heading"><?= __('Actions') ?></li>
        <li><?= $this->Html->link(__('List First Tamp Grn Records'), ['action' => 'index']) ?></li>
        <li><?= $this->Html->link(__('List Users'), ['controller' => 'Users', 'action' => 'index']) ?></li>
        <li><?= $this->Html->link(__('New User'), ['controller' => 'Users', 'action' => 'add']) ?></li>
    </ul>
</nav>
<div class="firstTampGrnRecords form large-9 medium-8 columns content">
    <?= $this->Form->create($firstTampGrnRecord) ?>
    <fieldset>
        <legend><?= __('Add First Tamp Grn Record') ?></legend>
        <?php
            echo $this->Form->control('item_code');
            echo $this->Form->control('quantity');
            echo $this->Form->control('purchase_rate');
            echo $this->Form->control('sales_rate');
            echo $this->Form->control('user_id', ['options' => $users]);
            echo $this->Form->control('processed');
            echo $this->Form->control('is_addition_item_data_required');
        ?>
    </fieldset>
    <?= $this->Form->button(__('Submit')) ?>
    <?= $this->Form->end() ?>
</div>
