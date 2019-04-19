<?php
/**
  * @var \App\View\AppView $this
  * @var \App\Model\Entity\CreditNoteRow[]|\Cake\Collection\CollectionInterface $creditNoteRows
  */
?>
<nav class="large-3 medium-4 columns" id="actions-sidebar">
    <ul class="side-nav">
        <li class="heading"><?= __('Actions') ?></li>
        <li><?= $this->Html->link(__('New Credit Note Row'), ['action' => 'add']) ?></li>
        <li><?= $this->Html->link(__('List Credit Notes'), ['controller' => 'CreditNotes', 'action' => 'index']) ?></li>
        <li><?= $this->Html->link(__('New Credit Note'), ['controller' => 'CreditNotes', 'action' => 'add']) ?></li>
        <li><?= $this->Html->link(__('List Items'), ['controller' => 'Items', 'action' => 'index']) ?></li>
        <li><?= $this->Html->link(__('New Item'), ['controller' => 'Items', 'action' => 'add']) ?></li>
        <li><?= $this->Html->link(__('List Gst Figures'), ['controller' => 'GstFigures', 'action' => 'index']) ?></li>
        <li><?= $this->Html->link(__('New Gst Figure'), ['controller' => 'GstFigures', 'action' => 'add']) ?></li>
    </ul>
</nav>
<div class="creditNoteRows index large-9 medium-8 columns content">
    <h3><?= __('Credit Note Rows') ?></h3>
    <table cellpadding="0" cellspacing="0">
        <thead>
            <tr>
                <th scope="col"><?= $this->Paginator->sort('id') ?></th>
                <th scope="col"><?= $this->Paginator->sort('credit_note_id') ?></th>
                <th scope="col"><?= $this->Paginator->sort('item_id') ?></th>
                <th scope="col"><?= $this->Paginator->sort('quantity') ?></th>
                <th scope="col"><?= $this->Paginator->sort('rate') ?></th>
                <th scope="col"><?= $this->Paginator->sort('taxable_value') ?></th>
                <th scope="col"><?= $this->Paginator->sort('gst_figure_id') ?></th>
                <th scope="col" class="actions"><?= __('Actions') ?></th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($creditNoteRows as $creditNoteRow): ?>
            <tr>
                <td><?= $this->Number->format($creditNoteRow->id) ?></td>
                <td><?= $creditNoteRow->has('credit_note') ? $this->Html->link($creditNoteRow->credit_note->id, ['controller' => 'CreditNotes', 'action' => 'view', $creditNoteRow->credit_note->id]) : '' ?></td>
                <td><?= $creditNoteRow->has('item') ? $this->Html->link($creditNoteRow->item->name, ['controller' => 'Items', 'action' => 'view', $creditNoteRow->item->id]) : '' ?></td>
                <td><?= $this->Number->format($creditNoteRow->quantity) ?></td>
                <td><?= $this->Number->format($creditNoteRow->rate) ?></td>
                <td><?= $this->Number->format($creditNoteRow->taxable_value) ?></td>
                <td><?= $creditNoteRow->has('gst_figure') ? $this->Html->link($creditNoteRow->gst_figure->name, ['controller' => 'GstFigures', 'action' => 'view', $creditNoteRow->gst_figure->id]) : '' ?></td>
                <td class="actions">
                    <?= $this->Html->link(__('View'), ['action' => 'view', $creditNoteRow->id]) ?>
                    <?= $this->Html->link(__('Edit'), ['action' => 'edit', $creditNoteRow->id]) ?>
                    <?= $this->Form->postLink(__('Delete'), ['action' => 'delete', $creditNoteRow->id], ['confirm' => __('Are you sure you want to delete # {0}?', $creditNoteRow->id)]) ?>
                </td>
            </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
    <div class="paginator">
        <ul class="pagination">
            <?= $this->Paginator->first('<< ' . __('first')) ?>
            <?= $this->Paginator->prev('< ' . __('previous')) ?>
            <?= $this->Paginator->numbers() ?>
            <?= $this->Paginator->next(__('next') . ' >') ?>
            <?= $this->Paginator->last(__('last') . ' >>') ?>
        </ul>
        <p><?= $this->Paginator->counter(['format' => __('Page {{page}} of {{pages}}, showing {{current}} record(s) out of {{count}} total')]) ?></p>
    </div>
</div>
