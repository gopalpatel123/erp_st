<?php
/**
 * @Author: PHP Poets IT Solutions Pvt. Ltd.
 */
$this->set('title', 'Report Setup');
?>

<div class="row">
	<div class="col-md-8">
		<div class="portlet light ">
			<div class="portlet-title">
				<div class="caption">
					<i class="icon-cursor font-purple-intense hide"></i>
					<span class="caption-subject font-blue-steel bold ">Reports</span>
				</div>
			</div>
			<div class="portlet-body">
				<div class="row">
				
				<?php if (in_array("19", $userPages)){?>
					<div class="col-md-6">
						<div class="list-group">
							<?php echo $this->Html->link('Trail Balance', '/Ledgers/trial_balance',['escape' => false, 'class'=>'list-group-item']); ?>
							</div>
					</div>
					<?php }?>
					<?php if (in_array("20", $userPages)){?>
					<div class="col-md-6">
						<div class="list-group">
							<?php echo $this->Html->link('Account Ledger', '/Ledgers/accountLedger',['escape' => false, 'class'=>'list-group-item']); ?>
						</div>
					</div>
					<?php }?>
				</div>
				<div class="row">
				<?php if (in_array("21", $userPages)){?>
					<div class="col-md-6">
						<div class="list-group">
							<?php echo $this->Html->link('Sales Register', '/SalesInvoices/reportFilter',['escape' => false, 'class'=>'list-group-item']); ?>
							</div>
					</div>
					<?php }?>
					
					<?php if (in_array("83", $userPages)){?>
					<div class="col-md-6">
						<div class="list-group">
							<?php echo $this->Html->link('Purchase Register', '/PurchaseInvoices/reportFilter',['escape' => false, 'class'=>'list-group-item']); ?>
							</div>
					</div>
					<?php }?>
					
					
				</div>
					<div class="row">
				<?php if (in_array("87", $userPages)){?>
					<div class="col-md-6">
						<div class="list-group">
							<?php echo $this->Html->link('Sales Return Register', '/SaleReturns/reportFilter',['escape' => false, 'class'=>'list-group-item']); ?>
							</div>
					</div>
					<?php }?>
					
					<?php if (in_array("86", $userPages)){?>
					<div class="col-md-6">
						<div class="list-group">
							<?php echo $this->Html->link('Purchase Return Register', '/PurchaseReturns/reportFilter',['escape' => false, 'class'=>'list-group-item']); ?>
							</div>
					</div>
					<?php }?>
					
					
				</div>
				<div class="row">
				<?php if (in_array("22", $userPages)){?>
					<div class="col-md-6">
						<div class="list-group">
							<?php echo $this->Html->link('Profit & Loss Statement', '/accounting-entries/profit-loss-statement?from_date='.@$coreVariable["fyValidFrom"].'&to_date='.@$coreVariable["fyValidTo"],['escape' => false, 'class'=>'list-group-item']); ?>
						</div>
						</div>
						<?php }?>
						<?php if (in_array("23", $userPages)){?>
					<div class="col-md-6">
						<div class="list-group">
							<?php echo $this->Html->link('Balance Sheet', '/accounting-entries/BalanceSheet?from_date='.@$coreVariable["fyValidFrom"].'&to_date='.@$coreVariable["fyValidTo"],['escape' => false, 'class'=>'list-group-item']); ?>
							</div>
					</div>
					<?php }?>
				</div>
				<div class="row">
				<?php if (in_array("24", $userPages)){?>
					<div class="col-md-6">
						<div class="list-group">
							<?php echo $this->Html->link('Bank Reconciliation Add', '/accounting-entries/bankReconciliation',['escape' => false, 'class'=>'list-group-item']); ?>
						</div>
					</div><?php }?>
					<?php if (in_array("25", $userPages)){?>
					<div class="col-md-6">
						<div class="list-group">
							<?php echo $this->Html->link('Day Book', '/Ledgers/day_book',['escape' => false, 'class'=>'list-group-item']); ?>
						</div>
					</div>
					<?php } ?>
				</div>
				<div class="row">
				<?php if (in_array("26", $userPages)){?>
					<div class="col-md-6">
						<div class="list-group">
							<?php echo $this->Html->link('Outstanding Receivable Report', '/Ledgers/over_due_report',['escape' => false, 'class'=>'list-group-item']); ?>
							</div>
					</div>
					<?php } ?>
					<?php if (in_array("27", $userPages)){?>
					<div class="col-md-6">
						<div class="list-group">
							<?php echo $this->Html->link('Outstanding Payable Report', '/Ledgers/over_due_report_payable',['escape' => false, 'class'=>'list-group-item']); ?>
							</div>
					</div>
					<?php }?>
				</div>
				
				<div class="row">
				<?php if (in_array("85", $userPages)){?>
					<div class="col-md-6">
						<div class="list-group">
							<?php echo $this->Html->link('Stock Report', '/ItemLedgers/stock-report',['escape' => false, 'class'=>'list-group-item']); ?>
							</div>
					</div>
				
					
				<?php } ?>
				<div class="col-md-6">
						<div class="list-group">
							<?php echo $this->Html->link('GST Report', '/AccountingEntries/gstReport',['escape' => false, 'class'=>'list-group-item']); ?>
							</div>
					</div>
			</div>
			<div class="row">
				<?php if (in_array("85", $userPages)){?>
					<div class="col-md-6">
						<div class="list-group">
							<?php echo $this->Html->link('HSN Wise Sales Report', '/SalesInvoices/hsnWiseReport',['escape' => false, 'class'=>'list-group-item']); ?>
							</div>
					</div>
				
					
					<?php } ?>
					<div class="col-md-6">
						<div class="list-group">
							<?php echo $this->Html->link('HSN Wise Purcahse Report', '/PurchaseInvoices/hsnWiseReport',['escape' => false, 'class'=>'list-group-item']); ?>
							</div>
					</div>
				</div>
			<div class="row">
				<?php if (in_array("85", $userPages)){?>
					<div class="col-md-6">
						<div class="list-group">
							<?php echo $this->Html->link('Stock Report Location Wise', '/ItemLedgers/stock-report-new',['escape' => false, 'class'=>'list-group-item']); ?>
							</div>
					</div>
				
					
				<?php } ?>
				
			</div>
		</div>
	</div>
</div>



