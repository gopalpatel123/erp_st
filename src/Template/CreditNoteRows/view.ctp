<?php
/**
  * @var \App\View\AppView $this
  * @var \App\Model\Entity\CreditNoteRow $creditNoteRow
  */
?>
<nav class="large-3 medium-4 columns" id="actions-sidebar">
    <ul class="side-nav">
        <li class="heading"><?= __('Actions') ?></li>
        <li><?= $this->Html->link(__('Edit Credit Note Row'), ['action' => 'edit', $creditNoteRow->id]) ?> </li>
        <li><?= $this->Form->postLink(__('Delete Credit Note Row'), ['action' => 'delete', $creditNoteRow->id], ['confirm' => __('Are you sure you want to delete # {0}?', $creditNoteRow->id)]) ?> </li>
        <li><?= $this->Html->link(__('List Credit Note Rows'), ['action' => 'index']) ?> </li>
        <li><?= $this->Html->link(__('New Credit Note Row'), ['action' => 'add']) ?> </li>
        <li><?= $this->Html->link(__('List Credit Notes'), ['controller' => 'CreditNotes', 'action' => 'index']) ?> </li>
        <li><?= $this->Html->link(__('New Credit Note'), ['controller' => 'CreditNotes', 'action' => 'add']) ?> </li>
        <li><?= $this->Html->link(__('List Items'), ['controller' => 'Items', 'action' => 'index']) ?> </li>
        <li><?= $this->Html->link(__('New Item'), ['controller' => 'Items', 'action' => 'add']) ?> </li>
        <li><?= $this->Html->link(__('List Gst Figures'), ['controller' => 'GstFigures', 'action' => 'index']) ?> </li>
        <li><?= $this->Html->link(__('New Gst Figure'), ['controller' => 'GstFigures', 'action' => 'add']) ?> </li>
    </ul>
</nav>
<div class="creditNoteRows view large-9 medium-8 columns content">
    <h3><?= h($creditNoteRow->id) ?></h3>
    <table class="vertical-table">
        <tr>
            <th scope="row"><?= __('Credit Note') ?></th>
            <td><?= $creditNoteRow->has('credit_note') ? $this->Html->link($creditNoteRow->credit_note->id, ['controller' => 'CreditNotes', 'action' => 'view', $creditNoteRow->credit_note->id]) : '' ?></td>
        </tr>
        <tr>
            <th scope="row"><?= __('Item') ?></th>
            <td><?= $creditNoteRow->has('item') ? $this->Html->link($creditNoteRow->item->name, ['controller' => 'Items', 'action' => 'view', $creditNoteRow->item->id]) : '' ?></td>
        </tr>
        <tr>
            <th scope="row"><?= __('Gst Figure') ?></th>
            <td><?= $creditNoteRow->has('gst_figure') ? $this->Html->link($creditNoteRow->gst_figure->name, ['controller' => 'GstFigures', 'action' => 'view', $creditNoteRow->gst_figure->id]) : '' ?></td>
        </tr>
        <tr>
            <th scope="row"><?= __('Id') ?></th>
            <td><?= $this->Number->format($creditNoteRow->id) ?></td>
        </tr>
        <tr>
            <th scope="row"><?= __('Quantity') ?></th>
            <td><?= $this->Number->format($creditNoteRow->quantity) ?></td>
        </tr>
        <tr>
            <th scope="row"><?= __('Rate') ?></th>
            <td><?= $this->Number->format($creditNoteRow->rate) ?></td>
        </tr>
        <tr>
            <th scope="row"><?= __('Taxable Value') ?></th>
            <td><?= $this->Number->format($creditNoteRow->taxable_value) ?></td>
        </tr>
    </table>
</div>
