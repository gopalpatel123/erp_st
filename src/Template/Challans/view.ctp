<?php
/**
  * @var \App\View\AppView $this
  * @var \App\Model\Entity\Challan $challan
  */
?>
<nav class="large-3 medium-4 columns" id="actions-sidebar">
    <ul class="side-nav">
        <li class="heading"><?= __('Actions') ?></li>
        <li><?= $this->Html->link(__('Edit Challan'), ['action' => 'edit', $challan->id]) ?> </li>
        <li><?= $this->Form->postLink(__('Delete Challan'), ['action' => 'delete', $challan->id], ['confirm' => __('Are you sure you want to delete # {0}?', $challan->id)]) ?> </li>
        <li><?= $this->Html->link(__('List Challans'), ['action' => 'index']) ?> </li>
        <li><?= $this->Html->link(__('New Challan'), ['action' => 'add']) ?> </li>
        <li><?= $this->Html->link(__('List Financial Years'), ['controller' => 'FinancialYears', 'action' => 'index']) ?> </li>
        <li><?= $this->Html->link(__('New Financial Year'), ['controller' => 'FinancialYears', 'action' => 'add']) ?> </li>
        <li><?= $this->Html->link(__('List Companies'), ['controller' => 'Companies', 'action' => 'index']) ?> </li>
        <li><?= $this->Html->link(__('New Company'), ['controller' => 'Companies', 'action' => 'add']) ?> </li>
        <li><?= $this->Html->link(__('List Customers'), ['controller' => 'Customers', 'action' => 'index']) ?> </li>
        <li><?= $this->Html->link(__('New Customer'), ['controller' => 'Customers', 'action' => 'add']) ?> </li>
        <li><?= $this->Html->link(__('List Sales Ledgers'), ['controller' => 'Ledgers', 'action' => 'index']) ?> </li>
        <li><?= $this->Html->link(__('New Sales Ledger'), ['controller' => 'Ledgers', 'action' => 'add']) ?> </li>
        <li><?= $this->Html->link(__('List Locations'), ['controller' => 'Locations', 'action' => 'index']) ?> </li>
        <li><?= $this->Html->link(__('New Location'), ['controller' => 'Locations', 'action' => 'add']) ?> </li>
        <li><?= $this->Html->link(__('List Challan Rows'), ['controller' => 'ChallanRows', 'action' => 'index']) ?> </li>
        <li><?= $this->Html->link(__('New Challan Row'), ['controller' => 'ChallanRows', 'action' => 'add']) ?> </li>
    </ul>
</nav>
<div class="challans view large-9 medium-8 columns content">
    <h3><?= h($challan->id) ?></h3>
    <table class="vertical-table">
        <tr>
            <th scope="row"><?= __('Financial Year') ?></th>
            <td><?= $challan->has('financial_year') ? $this->Html->link($challan->financial_year->id, ['controller' => 'FinancialYears', 'action' => 'view', $challan->financial_year->id]) : '' ?></td>
        </tr>
        <tr>
            <th scope="row"><?= __('Company') ?></th>
            <td><?= $challan->has('company') ? $this->Html->link($challan->company->name, ['controller' => 'Companies', 'action' => 'view', $challan->company->id]) : '' ?></td>
        </tr>
        <tr>
            <th scope="row"><?= __('Customer') ?></th>
            <td><?= $challan->has('customer') ? $this->Html->link($challan->customer->name, ['controller' => 'Customers', 'action' => 'view', $challan->customer->id]) : '' ?></td>
        </tr>
        <tr>
            <th scope="row"><?= __('Sales Ledger') ?></th>
            <td><?= $challan->has('sales_ledger') ? $this->Html->link($challan->sales_ledger->name, ['controller' => 'Ledgers', 'action' => 'view', $challan->sales_ledger->id]) : '' ?></td>
        </tr>
        <tr>
            <th scope="row"><?= __('Party Ledger') ?></th>
            <td><?= $challan->has('party_ledger') ? $this->Html->link($challan->party_ledger->name, ['controller' => 'Ledgers', 'action' => 'view', $challan->party_ledger->id]) : '' ?></td>
        </tr>
        <tr>
            <th scope="row"><?= __('Location') ?></th>
            <td><?= $challan->has('location') ? $this->Html->link($challan->location->name, ['controller' => 'Locations', 'action' => 'view', $challan->location->id]) : '' ?></td>
        </tr>
        <tr>
            <th scope="row"><?= __('Invoice Receipt Type') ?></th>
            <td><?= h($challan->invoice_receipt_type) ?></td>
        </tr>
        <tr>
            <th scope="row"><?= __('Status') ?></th>
            <td><?= h($challan->status) ?></td>
        </tr>
        <tr>
            <th scope="row"><?= __('Id') ?></th>
            <td><?= $this->Number->format($challan->id) ?></td>
        </tr>
        <tr>
            <th scope="row"><?= __('Voucher No') ?></th>
            <td><?= $this->Number->format($challan->voucher_no) ?></td>
        </tr>
        <tr>
            <th scope="row"><?= __('Amount Before Tax') ?></th>
            <td><?= $this->Number->format($challan->amount_before_tax) ?></td>
        </tr>
        <tr>
            <th scope="row"><?= __('Total Cgst') ?></th>
            <td><?= $this->Number->format($challan->total_cgst) ?></td>
        </tr>
        <tr>
            <th scope="row"><?= __('Total Sgst') ?></th>
            <td><?= $this->Number->format($challan->total_sgst) ?></td>
        </tr>
        <tr>
            <th scope="row"><?= __('Total Igst') ?></th>
            <td><?= $this->Number->format($challan->total_igst) ?></td>
        </tr>
        <tr>
            <th scope="row"><?= __('Amount After Tax') ?></th>
            <td><?= $this->Number->format($challan->amount_after_tax) ?></td>
        </tr>
        <tr>
            <th scope="row"><?= __('Round Off') ?></th>
            <td><?= $this->Number->format($challan->round_off) ?></td>
        </tr>
        <tr>
            <th scope="row"><?= __('Receipt Amount') ?></th>
            <td><?= $this->Number->format($challan->receipt_amount) ?></td>
        </tr>
        <tr>
            <th scope="row"><?= __('Discount Amount') ?></th>
            <td><?= $this->Number->format($challan->discount_amount) ?></td>
        </tr>
        <tr>
            <th scope="row"><?= __('Transaction Date') ?></th>
            <td><?= h($challan->transaction_date) ?></td>
        </tr>
    </table>
    <div class="related">
        <h4><?= __('Related Challan Rows') ?></h4>
        <?php if (!empty($challan->challan_rows)): ?>
        <table cellpadding="0" cellspacing="0">
            <tr>
                <th scope="col"><?= __('Id') ?></th>
                <th scope="col"><?= __('Challan Id') ?></th>
                <th scope="col"><?= __('Item Id') ?></th>
                <th scope="col"><?= __('Quantity') ?></th>
                <th scope="col"><?= __('Rate') ?></th>
                <th scope="col"><?= __('Discount Percentage') ?></th>
                <th scope="col"><?= __('Taxable Value') ?></th>
                <th scope="col"><?= __('Net Amount') ?></th>
                <th scope="col"><?= __('Gst Figure Id') ?></th>
                <th scope="col"><?= __('Gst Value') ?></th>
                <th scope="col"><?= __('Is Gst Excluded') ?></th>
                <th scope="col"><?= __('Challan Type') ?></th>
                <th scope="col" class="actions"><?= __('Actions') ?></th>
            </tr>
            <?php foreach ($challan->challan_rows as $challanRows): ?>
            <tr>
                <td><?= h($challanRows->id) ?></td>
                <td><?= h($challanRows->challan_id) ?></td>
                <td><?= h($challanRows->item_id) ?></td>
                <td><?= h($challanRows->quantity) ?></td>
                <td><?= h($challanRows->rate) ?></td>
                <td><?= h($challanRows->discount_percentage) ?></td>
                <td><?= h($challanRows->taxable_value) ?></td>
                <td><?= h($challanRows->net_amount) ?></td>
                <td><?= h($challanRows->gst_figure_id) ?></td>
                <td><?= h($challanRows->gst_value) ?></td>
                <td><?= h($challanRows->is_gst_excluded) ?></td>
                <td><?= h($challanRows->challan_type) ?></td>
                <td class="actions">
                    <?= $this->Html->link(__('View'), ['controller' => 'ChallanRows', 'action' => 'view', $challanRows->id]) ?>
                    <?= $this->Html->link(__('Edit'), ['controller' => 'ChallanRows', 'action' => 'edit', $challanRows->id]) ?>
                    <?= $this->Form->postLink(__('Delete'), ['controller' => 'ChallanRows', 'action' => 'delete', $challanRows->id], ['confirm' => __('Are you sure you want to delete # {0}?', $challanRows->id)]) ?>
                </td>
            </tr>
            <?php endforeach; ?>
        </table>
        <?php endif; ?>
    </div>
</div>
