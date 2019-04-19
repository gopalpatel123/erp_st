<?php
/**
  * @var \App\View\AppView $this
  * @var \App\Model\Entity\Company $company
  */
?>
<nav class="large-3 medium-4 columns" id="actions-sidebar">
    <ul class="side-nav">
        <li class="heading"><?= __('Actions') ?></li>
        <li><?= $this->Html->link(__('Edit Company'), ['action' => 'edit', $company->id]) ?> </li>
        <li><?= $this->Form->postLink(__('Delete Company'), ['action' => 'delete', $company->id], ['confirm' => __('Are you sure you want to delete # {0}?', $company->id)]) ?> </li>
        <li><?= $this->Html->link(__('List Companies'), ['action' => 'index']) ?> </li>
        <li><?= $this->Html->link(__('New Company'), ['action' => 'add']) ?> </li>
        <li><?= $this->Html->link(__('List States'), ['controller' => 'States', 'action' => 'index']) ?> </li>
        <li><?= $this->Html->link(__('New State'), ['controller' => 'States', 'action' => 'add']) ?> </li>
        <li><?= $this->Html->link(__('List Company Users'), ['controller' => 'CompanyUsers', 'action' => 'index']) ?> </li>
        <li><?= $this->Html->link(__('New Company User'), ['controller' => 'CompanyUsers', 'action' => 'add']) ?> </li>
    </ul>
</nav>
<div class="companies view large-9 medium-8 columns content">
    <h3><?= h($company->name) ?></h3>
    <table class="vertical-table">
        <tr>
            <th scope="row"><?= __('Name') ?></th>
            <td><?= h($company->name) ?></td>
        </tr>
        <tr>
            <th scope="row"><?= __('State') ?></th>
            <td><?= $company->has('state') ? $this->Html->link($company->state->name, ['controller' => 'States', 'action' => 'view', $company->state->id]) : '' ?></td>
        </tr>
        <tr>
            <th scope="row"><?= __('Phone No') ?></th>
            <td><?= h($company->phone_no) ?></td>
        </tr>
        <tr>
            <th scope="row"><?= __('Mobile') ?></th>
            <td><?= h($company->mobile) ?></td>
        </tr>
        <tr>
            <th scope="row"><?= __('Fax No') ?></th>
            <td><?= h($company->fax_no) ?></td>
        </tr>
        <tr>
            <th scope="row"><?= __('Email') ?></th>
            <td><?= h($company->email) ?></td>
        </tr>
        <tr>
            <th scope="row"><?= __('Gstin') ?></th>
            <td><?= h($company->gstin) ?></td>
        </tr>
        <tr>
            <th scope="row"><?= __('Pan') ?></th>
            <td><?= h($company->pan) ?></td>
        </tr>
        <tr>
            <th scope="row"><?= __('Id') ?></th>
            <td><?= $this->Number->format($company->id) ?></td>
        </tr>
        <tr>
            <th scope="row"><?= __('Financial Year Begins From') ?></th>
            <td><?= h($company->financial_year_begins_from) ?></td>
        </tr>
        <tr>
            <th scope="row"><?= __('Books Beginning From') ?></th>
            <td><?= h($company->books_beginning_from) ?></td>
        </tr>
    </table>
    <div class="row">
        <h4><?= __('Address') ?></h4>
        <?= $this->Text->autoParagraph(h($company->address)); ?>
    </div>
    <div class="related">
        <h4><?= __('Related Company Users') ?></h4>
        <?php if (!empty($company->company_users)): ?>
        <table cellpadding="0" cellspacing="0">
            <tr>
                <th scope="col"><?= __('Id') ?></th>
                <th scope="col"><?= __('Company Id') ?></th>
                <th scope="col"><?= __('User Id') ?></th>
                <th scope="col" class="actions"><?= __('Actions') ?></th>
            </tr>
            <?php foreach ($company->company_users as $companyUsers): ?>
            <tr>
                <td><?= h($companyUsers->id) ?></td>
                <td><?= h($companyUsers->company_id) ?></td>
                <td><?= h($companyUsers->user_id) ?></td>
                <td class="actions">
                    <?= $this->Html->link(__('View'), ['controller' => 'CompanyUsers', 'action' => 'view', $companyUsers->id]) ?>
                    <?= $this->Html->link(__('Edit'), ['controller' => 'CompanyUsers', 'action' => 'edit', $companyUsers->id]) ?>
                    <?= $this->Form->postLink(__('Delete'), ['controller' => 'CompanyUsers', 'action' => 'delete', $companyUsers->id], ['confirm' => __('Are you sure you want to delete # {0}?', $companyUsers->id)]) ?>
                </td>
            </tr>
            <?php endforeach; ?>
        </table>
        <?php endif; ?>
    </div>
</div>
