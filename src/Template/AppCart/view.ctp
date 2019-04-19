<?php
/**
  * @var \App\View\AppView $this
  * @var \App\Model\Entity\AppCart $appCart
  */
?>
<nav class="large-3 medium-4 columns" id="actions-sidebar">
    <ul class="side-nav">
        <li class="heading"><?= __('Actions') ?></li>
        <li><?= $this->Html->link(__('Edit App Cart'), ['action' => 'edit', $appCart->id]) ?> </li>
        <li><?= $this->Form->postLink(__('Delete App Cart'), ['action' => 'delete', $appCart->id], ['confirm' => __('Are you sure you want to delete # {0}?', $appCart->id)]) ?> </li>
        <li><?= $this->Html->link(__('List App Cart'), ['action' => 'index']) ?> </li>
        <li><?= $this->Html->link(__('New App Cart'), ['action' => 'add']) ?> </li>
        <li><?= $this->Html->link(__('List Items'), ['controller' => 'Items', 'action' => 'index']) ?> </li>
        <li><?= $this->Html->link(__('New Item'), ['controller' => 'Items', 'action' => 'add']) ?> </li>
        <li><?= $this->Html->link(__('List Users'), ['controller' => 'Users', 'action' => 'index']) ?> </li>
        <li><?= $this->Html->link(__('New User'), ['controller' => 'Users', 'action' => 'add']) ?> </li>
        <li><?= $this->Html->link(__('List Companies'), ['controller' => 'Companies', 'action' => 'index']) ?> </li>
        <li><?= $this->Html->link(__('New Company'), ['controller' => 'Companies', 'action' => 'add']) ?> </li>
        <li><?= $this->Html->link(__('List Locations'), ['controller' => 'Locations', 'action' => 'index']) ?> </li>
        <li><?= $this->Html->link(__('New Location'), ['controller' => 'Locations', 'action' => 'add']) ?> </li>
    </ul>
</nav>
<div class="appCart view large-9 medium-8 columns content">
    <h3><?= h($appCart->id) ?></h3>
    <table class="vertical-table">
        <tr>
            <th scope="row"><?= __('Item') ?></th>
            <td><?= $appCart->has('item') ? $this->Html->link($appCart->item->name, ['controller' => 'Items', 'action' => 'view', $appCart->item->id]) : '' ?></td>
        </tr>
        <tr>
            <th scope="row"><?= __('User') ?></th>
            <td><?= $appCart->has('user') ? $this->Html->link($appCart->user->name, ['controller' => 'Users', 'action' => 'view', $appCart->user->id]) : '' ?></td>
        </tr>
        <tr>
            <th scope="row"><?= __('Company') ?></th>
            <td><?= $appCart->has('company') ? $this->Html->link($appCart->company->name, ['controller' => 'Companies', 'action' => 'view', $appCart->company->id]) : '' ?></td>
        </tr>
        <tr>
            <th scope="row"><?= __('Location') ?></th>
            <td><?= $appCart->has('location') ? $this->Html->link($appCart->location->name, ['controller' => 'Locations', 'action' => 'view', $appCart->location->id]) : '' ?></td>
        </tr>
        <tr>
            <th scope="row"><?= __('Id') ?></th>
            <td><?= $this->Number->format($appCart->id) ?></td>
        </tr>
        <tr>
            <th scope="row"><?= __('Quantity') ?></th>
            <td><?= $this->Number->format($appCart->quantity) ?></td>
        </tr>
    </table>
</div>
