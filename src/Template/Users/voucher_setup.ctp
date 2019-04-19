<?php
/**
 * @Author: PHP Poets IT Solutions Pvt. Ltd.
 */
$this->set('title', 'Voucher Setup');
?>

<div class="row">
	<div class="col-md-12">
		<div class="portlet light ">
			<div class="portlet-title">
				<div class="caption">
					<i class="icon-cursor font-purple-intense hide"></i>
					<span class="caption-subject font-blue-steel bold ">Vouchers</span>
				</div>
			</div>
			<div class="portlet-body">
				<div class="row">
					<?php
					$target=array("28","29");
					if(!empty(count(array_intersect($userPages, $target)))){?>
					<div class="col-md-3">
						<span class="caption-subject bold ">Sales Voucher</span>
						<div class="list-group">
						<?php if (in_array("28", $userPages)){?>
							<?php echo $this->Html->link($this->Html->tag('i', '', ['class' => 'fa fa-plus-square menuCss']).' Create', '/salesVouchers/Add',['escape' => false, 'class'=>'list-group-item']); ?>
							<?php }?>
							
							<?php if (in_array("29", $userPages)){?>
							<?php echo $this->Html->link($this->Html->tag('i', '', ['class' => 'fa fa-list-ul menuCss']).' List', '/salesVouchers',['escape' => false, 'class'=>'list-group-item']); ?> 
							<?php }?>
						</div>
					</div>
					<?php }?>
					
					<?php
					$target=array("31","32");
					if(!empty(count(array_intersect($userPages, $target)))){?>
					<div class="col-md-3">
						<span class="caption-subject bold ">Purchase Voucher</span>
						<div class="list-group">
						<?php if (in_array("31", $userPages)){?>
							<?php echo $this->Html->link($this->Html->tag('i', '', ['class' => 'fa fa-plus-square menuCss']).' Create', '/PurchaseVouchers/Add',['escape' => false, 'class'=>'list-group-item']); ?>
							<?php }?>
							
							<?php if (in_array("32", $userPages)){?>
							<?php echo $this->Html->link($this->Html->tag('i', '', ['class' => 'fa fa-list-ul menuCss']).' List', '/PurchaseVouchers',['escape' => false, 'class'=>'list-group-item']); ?>
							<?php }?>
						</div>
					</div>
					<?php }?>
					<?php
$target=array("34","35");
if(!empty(count(array_intersect($userPages, $target)))){?>
					<div class="col-md-3">
						<span class="caption-subject bold ">Credit Note</span>
						<div class="list-group">
						<?php if (in_array("34", $userPages)){?>
							<?php echo $this->Html->link($this->Html->tag('i', '', ['class' => 'fa fa-plus-square menuCss']).' Create', '/CreditNotes/Add',['escape' => false, 'class'=>'list-group-item']); ?>
							<?php }?>
							<?php if (in_array("35", $userPages)){?>
							<?php echo $this->Html->link($this->Html->tag('i', '', ['class' => 'fa fa-list-ul menuCss']).' List', '/CreditNotes',['escape' => false, 'class'=>'list-group-item']); ?>
							<?php }?>
						</div>
					</div>
					<?php }?>
					<?php
$target=array("37","38");
if(!empty(count(array_intersect($userPages, $target)))){?>
					<div class="col-md-3">
						<span class="caption-subject bold ">Debit Note</span>
						<div class="list-group">
						<?php if (in_array("37", $userPages)){?>
							<?php echo $this->Html->link($this->Html->tag('i', '', ['class' => 'fa fa-plus-square menuCss']).' Create', '/DebitNotes/Add',['escape' => false, 'class'=>'list-group-item']); ?>
							<?php }?>
							<?php if (in_array("38", $userPages)){?>
							<?php echo $this->Html->link($this->Html->tag('i', '', ['class' => 'fa fa-list-ul menuCss']).' List', '/DebitNotes',['escape' => false, 'class'=>'list-group-item']); ?>
							<?php }?> 
						</div>
					</div>
					<?php }?>
					
					<?php
$target=array("40","41");
if(!empty(count(array_intersect($userPages, $target)))){?>
					<div class="col-md-3">
						<span class="caption-subject bold ">Receipt Voucher</span>
						<div class="list-group">
						<?php if (in_array("40", $userPages)){?>
							<?php echo $this->Html->link($this->Html->tag('i', '', ['class' => 'fa fa-plus-square menuCss']).' Create', '/Receipts/Add',['escape' => false, 'class'=>'list-group-item']); ?>
							<?php }?>
							<?php if (in_array("41", $userPages)){?>
							<?php echo $this->Html->link($this->Html->tag('i', '', ['class' => 'fa fa-list-ul menuCss']).' List', '/Receipts',['escape' => false, 'class'=>'list-group-item']); ?>
							<?php }?>
						</div>
					</div>
					<?php }?>
					<?php
$target=array("43","44");
if(!empty(count(array_intersect($userPages, $target)))){?>
					<div class="col-md-3">
						<span class="caption-subject bold ">Payment Voucher</span>
						<div class="list-group">
						<?php if (in_array("43", $userPages)){?>
							<?php echo $this->Html->link($this->Html->tag('i', '', ['class' => 'fa fa-plus-square menuCss']).' Create', '/Payments/Add',['escape' => false, 'class'=>'list-group-item']); ?>
							<?php }?>
							<?php if (in_array("44", $userPages)){?>
							<?php echo $this->Html->link($this->Html->tag('i', '', ['class' => 'fa fa-list-ul menuCss']).' List', '/Payments',['escape' => false, 'class'=>'list-group-item']); ?>
							<?php }?>
						</div>
					</div>
					<?php }?>
					<?php
$target=array("46","47");
if(!empty(count(array_intersect($userPages, $target)))){?>
					<div class="col-md-3">
						<span class="caption-subject bold ">Journal Voucher</span>
						<div class="list-group">
						<?php if (in_array("46", $userPages)){?>
							<?php echo $this->Html->link($this->Html->tag('i', '', ['class' => 'fa fa-plus-square menuCss']).' Create', '/JournalVouchers/Add',['escape' => false, 'class'=>'list-group-item']); ?>
							<?php }?>
							<?php if (in_array("47", $userPages)){?>
							<?php echo $this->Html->link($this->Html->tag('i', '', ['class' => 'fa fa-list-ul menuCss']).' List', '/JournalVouchers',['escape' => false, 'class'=>'list-group-item']); ?>
							<?php }?>
						</div>
					</div><?php }?>
					<?php
$target=array("49","50");
if(!empty(count(array_intersect($userPages, $target)))){?>
					<div class="col-md-3">
						<span class="caption-subject bold ">Contra Voucher</span>
						<div class="list-group">
						<?php if (in_array("49", $userPages)){?>
							<?php echo $this->Html->link($this->Html->tag('i', '', ['class' => 'fa fa-plus-square menuCss']).' Create', '/ContraVouchers/Add',['escape' => false, 'class'=>'list-group-item']); ?>
							<?php }?>
							<?php if (in_array("50", $userPages)){?>
							<?php echo $this->Html->link($this->Html->tag('i', '', ['class' => 'fa fa-list-ul menuCss']).' List', '/ContraVouchers',['escape' => false, 'class'=>'list-group-item']); ?>
							<?php }?>
						</div>
					</div>
					<?php }?>
					<?php
$target=array("52","53");
if(!empty(count(array_intersect($userPages, $target)))){?>
					<div class="col-md-3">
						<span class="caption-subject bold ">Stock Journals</span>
						<div class="list-group">
						<?php if (in_array("52", $userPages)){?>
							<?php echo $this->Html->link($this->Html->tag('i', '', ['class' => 'fa fa-plus-square menuCss']).' Create', '/StockJournals/Add',['escape' => false, 'class'=>'list-group-item']); ?>
							<?php }?>
							<?php if (in_array("53", $userPages)){?>
							<?php echo $this->Html->link($this->Html->tag('i', '', ['class' => 'fa fa-list-ul menuCss']).' List', '/StockJournals',['escape' => false, 'class'=>'list-group-item']); ?>
							<?php }?>
						</div>
					</div>
					<?php }?>
				</div>
			</div>
		</div>
	</div>
	
</div>



