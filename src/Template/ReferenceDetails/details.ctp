
<div class="row">
	<div class="col-md-9">
		<div class="portlet light ">
			<div class="portlet-title">
				<div class="caption">
					<i class="icon-bar-chart font-green-sharp hide"></i>
					<span class="caption-subject font-green-sharp bold ">Referencial Details View</span>
				</div>
			</div>
			<div class="portlet-body table-responsive">
					<?php if(!empty($referenceDetails)){?>
					<tr><td>&nbsp;</td><td colspan="5">
					<table class="table table-bordered table-condensed" width="50%" align="center" style="">
					<thead>
					<tr>
						<th scope="col" style="text-align:left">Date</th>
						<th scope="col" style="text-align:left">Voucher</th>
						<th scope="col" style="text-align:left">Voucher No.</th>
						<th scope="col" style="text-align:left">Debit</th>
						<th scope="col" style="text-align:left">Credit</th>
					</tr>
					</thead>
					<tbody>
					<?php foreach($referenceDetails as $refdata){ ?>
					<tr>
					<td style="text-align:left"><?=date('d-m-Y',strtotime($refdata->transaction_date))?></td>
					<?php if(!empty($refdata->payment_row_id)){ ?> <td style="text-align:left"><?='Payment' ?>
					</td>
					<td style="text-align:left"><?= $this->Html->link($refdata->payment_row->ref_payment->voucher_no, ['controller' => 'Payments', 'action' => 'edit', $refdata->payment_row->payment_id]) ?></td>
					</td><?php } ?>
					<?php if(!empty($refdata->receipt_row_id)){ ?> <td style="text-align:left"><?='Receipt' ?>
					</td>
					<td style="text-align:left"><?= $this->Html->link($refdata->receipt_row->ref_receipt->voucher_no, ['controller' => 'Receipts', 'action' => 'edit', $refdata->receipt_row->receipt_id]) ?></td>
					</td><?php } ?>
					<?php if(!empty($refdata->sales_voucher_row_id)){ ?> <td style="text-align:left"><?='Sales Voucher' ?>
					</td>
					<td style="text-align:left"><?= $this->Html->link($refdata->sales_voucher_row->ref_sales_voucher->voucher_no, ['controller' => 'SalesVouchers', 'action' => 'edit', $refdata->sales_voucher_row->sales_voucher_id]) ?></td>
					</td><?php } ?>
					<?php if(!empty($refdata->purchase_voucher_row_id)){ ?> <td style="text-align:left"><?='Purchase Voucher' ?>
					</td>
					<td style="text-align:left"><?= $this->Html->link($refdata->purchase_voucher_row->ref_purchase_voucher->voucher_no, ['controller' => 'PurchaseVouchers', 'action' => 'edit', $refdata->purchase_voucher_row->purchase_voucher_id]) ?></td>
					</td><?php } ?>
					<?php if(!empty($refdata->credit_note_row_id)){ ?> <td style="text-align:left"><?='Credit Note' ?>
					</td>
					<td style="text-align:left"><?= $this->Html->link($refdata->credit_note_row->ref_credit_note->voucher_no, ['controller' => 'CreditNotes', 'action' => 'edit', $refdata->credit_note_row->credit_note_id]) ?></td>
					</td><?php } ?>
					<?php if(!empty($refdata->debit_note_row_id)){ ?> <td style="text-align:left"><?='Debit Note' ?>
					</td>
					<td style="text-align:left"><?= $this->Html->link($refdata->debit_note_row->ref_debit_note->voucher_no, ['controller' => 'DebitNotes', 'action' => 'edit', $refdata->debit_note_row->debit_note_id]) ?></td>
					</td><?php } ?>
					<?php if(!empty($refdata->journal_voucher_row_id)){ ?> <td style="text-align:left"><?='Journal Vouchers' ?>
					</td>
					<td style="text-align:left"><?= $this->Html->link($refdata->journal_voucher_row->ref_journal_voucher->voucher_no, ['controller' => 'JournalVouchers', 'action' => 'edit', $refdata->journal_voucher_row->journal_voucher_id]) ?></td>
					</td><?php } ?>
					<?php if(!empty($refdata->sales_invoice_id)){ ?> <td style="text-align:left"><?='Sales Invoice' ?>
					</td>
					<td style="text-align:left"><?= $this->Html->link($refdata->sales_invoice->voucher_no, ['controller' => 'SalesInvoices', 'action' => 'edit', $refdata->sales_invoice_id]) ?></td>
					</td><?php } ?>
					<?php if(!empty($refdata->purchase_invoice_id)){ ?> <td style="text-align:left"><?='Purchase Invoice' ?>
					</td>
					<td style="text-align:left"><?= $this->Html->link($refdata->purchase_invoice->voucher_no, ['controller' => 'PurchaseInvoices', 'action' => 'edit', $refdata->purchase_invoice_id]) ?></td>
					</td><?php } ?>
					<?php if(!empty($refdata->purchase_return_id)){ ?> <td style="text-align:left"><?='Purchse Return' ?>
					</td>
					<td style="text-align:left"><?= $this->Html->link($refdata->purchase_return->voucher_no, ['controller' => 'PurchaseReturns', 'action' => 'view', $refdata->purchase_return_id]) ?></td>
					</td><?php } ?>
					<?php if(!empty($refdata->sale_return_id)){ ?> <td style="text-align:left"><?='Sales Return' ?>
					</td>
					<td style="text-align:left"><?= $this->Html->link($refdata->sale_return->voucher_no, ['controller' => 'SaleReturns', 'action' => 'view', $refdata->sale_return_id]) ?></td>
					</td><?php } ?>
					<?php if($refdata->opening_balance=='yes'){ ?> <td style="text-align:left"><?='Opening Balance' ?>
					</td>
					<td style="text-align:left"><?php echo '-'; ?></td>
					</td><?php } ?>
					<td class="rightAligntextClass"><?php if(!empty($refdata->debit)){ ?><?=$refdata->debit?><?php } ?></td>
					<td class="rightAligntextClass"><?php if(!empty($refdata->credit)){ ?><?=$refdata->credit?><?php } ?> </td>
					</tr>
					<?php }?>
					</tbody>
					</table>
				<?php }?>	
			
			</div>
		</div>
	</div>					
</div>