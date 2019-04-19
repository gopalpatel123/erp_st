<?php
/**
  * @var \App\View\AppView $this
  * @var \App\Model\Entity\CompanyUser $companyUser
  */
?>
<nav class="large-3 medium-4 columns" id="actions-sidebar">
    <ul class="side-nav">
        <li class="heading"><?= __('Actions') ?></li>
        <li><?= $this->Html->link(__('Edit Company User'), ['action' => 'edit', $companyUser->id]) ?> </li>
        <li><?= $this->Form->postLink(__('Delete Company User'), ['action' => 'delete', $companyUser->id], ['confirm' => __('Are you sure you want to delete # {0}?', $companyUser->id)]) ?> </li>
        <li><?= $this->Html->link(__('List Company Users'), ['action' => 'index']) ?> </li>
        <li><?= $this->Html->link(__('New Company User'), ['action' => 'add']) ?> </li>
        <li><?= $this->Html->link(__('List Companies'), ['controller' => 'Companies', 'action' => 'index']) ?> </li>
        <li><?= $this->Html->link(__('New Company'), ['controller' => 'Companies', 'action' => 'add']) ?> </li>
        <li><?= $this->Html->link(__('List Users'), ['controller' => 'Users', 'action' => 'index']) ?> </li>
        <li><?= $this->Html->link(__('New User'), ['controller' => 'Users', 'action' => 'add']) ?> </li>
    </ul>
</nav>
<div class="companyUsers view large-9 medium-8 columns content">
    <h3><?= h($companyUser->id) ?></h3>
    <table class="vertical-table">
        <tr>
            <th scope="row"><?= __('Company') ?></th>
            <td><?= $companyUser->has('company') ? $this->Html->link($companyUser->company->name, ['controller' => 'Companies', 'action' => 'view', $companyUser->company->id]) : '' ?></td>
        </tr>
        <tr>
            <th scope="row"><?= __('User') ?></th>
            <td><?= $companyUser->has('user') ? $this->Html->link($companyUser->user->name, ['controller' => 'Users', 'action' => 'view', $companyUser->user->id]) : '' ?></td>
        </tr>
        <tr>
            <th scope="row"><?= __('Id') ?></th>
            <td><?= $this->Number->format($companyUser->id) ?></td>
        </tr>
    </table>
</div>
