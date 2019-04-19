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
                ['action' => 'delete', $outward->id],
                ['confirm' => __('Are you sure you want to delete # {0}?', $outward->id)]
            )
        ?></li>
        <li><?= $this->Html->link(__('List Outwards'), ['action' => 'index']) ?></li>
        <li><?= $this->Html->link(__('List Items'), ['controller' => 'Items', 'action' => 'index']) ?></li>
        <li><?= $this->Html->link(__('New Item'), ['controller' => 'Items', 'action' => 'add']) ?></li>
        <li><?= $this->Html->link(__('List Stock Journals'), ['controller' => 'StockJournals', 'action' => 'index']) ?></li>
        <li><?= $this->Html->link(__('New Stock Journal'), ['controller' => 'StockJournals', 'action' => 'add']) ?></li>
    </ul>
</nav>
<div class="outwards form large-9 medium-8 columns content">
    <?= $this->Form->create($outward) ?>
    <fieldset>
        <legend><?= __('Edit Outward') ?></legend>
        <?php
            echo $this->Form->control('item_id', ['options' => $items]);
            echo $this->Form->control('quantity');
            echo $this->Form->control('rate');
            echo $this->Form->control('amount');
            echo $this->Form->control('stock_journal_id', ['options' => $stockJournals]);
        ?>
    </fieldset>
    <?= $this->Form->button(__('Submit')) ?>
    <?= $this->Form->end() ?>
</div>
