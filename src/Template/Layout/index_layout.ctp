<!DOCTYPE html>
<!-- 
Template Name: Metronic - Responsive Admin Dashboard Template build with Twitter Bootstrap 3.3.1
Version: 3.3.0
Author: KeenThemes
Website: http://www.keenthemes.com/
Contact: support@keenthemes.com
Follow: www.twitter.com/keenthemes
Like: www.facebook.com/keenthemes
Purchase: http://themeforest.net/item/metronic-responsive-admin-dashboard-template/4021469?ref=keenthemes
License: You must have a valid license purchased only from themeforest(the above link) in order to legally use the theme for your project.
-->
<!--[if IE 8]> <html lang="en" class="ie8 no-js"> <![endif]-->
<!--[if IE 9]> <html lang="en" class="ie9 no-js"> <![endif]-->
<!--[if !IE]><!-->
<html lang="en">
	<!--<![endif]-->
	<!-- BEGIN HEAD -->
	<head>
		<?= $this->Html->charset() ?>
		<meta name="viewport" content="width=device-width, initial-scale=1.0">
		<title>
			<?php echo @$title; ?>
		</title>
		<!-- BEGIN GLOBAL MANDATORY STYLES -->
		<!--<link href="http://fonts.googleapis.com/css?family=Open+Sans:400,300,600,700&subset=all" rel="stylesheet" type="text/css"/>-->
		<?php echo $this->Html->css('/assets/global/plugins/font-awesome/css/font-awesome.min.css'); ?>
		<?php echo $this->Html->css('/assets/global/plugins/simple-line-icons/simple-line-icons.min.css'); ?>
		<?php echo $this->Html->css('/assets/global/plugins/bootstrap/css/bootstrap.min.css'); ?>
		<?php echo $this->Html->css('/assets/global/plugins/uniform/css/uniform.default.css'); ?>
		<?php echo $this->Html->css('/assets/global/plugins/bootstrap-switch/css/bootstrap-switch.min.css'); ?>
		<!-- END GLOBAL MANDATORY STYLES -->
		<?= $this->fetch('PAGE_LEVEL_CSS')?>
		<!-- BEGIN THEME STYLES -->
		<?php echo $this->Html->css('/assets/global/css/components.css'); ?>
		<?php echo $this->Html->css('/assets/global/css/plugins.css'); ?>
		<?php echo $this->Html->css('/assets/admin/layout/css/layout.css'); ?>
		<?php echo $this->Html->css('/assets/admin/layout/css/themes/darkblue.css'); ?>
		<?php echo $this->Html->css('/assets/admin/layout/css/custom.css'); ?>
		<!-- END THEME STYLES -->
		<style>
		span.required{
			color:red;
		}
		.rightAligntextClass{
			text-align:right !important;
		}
		.help-block-error{
			color: red;
			font-size: 11px;
			margin: 0;
		}
		.numberOnly{
		}
		</style>
	</head>
	<!-- END HEAD -->
	<!-- BEGIN BODY -->
	<!-- DOC: Apply "page-header-fixed-mobile" and "page-footer-fixed-mobile" class to body element to force fixed header or footer in mobile devices -->
	<!-- DOC: Apply "page-sidebar-closed" class to the body and "page-sidebar-menu-closed" class to the sidebar menu element to hide the sidebar by default -->
	<!-- DOC: Apply "page-sidebar-hide" class to the body to make the sidebar completely hidden on toggle -->
	<!-- DOC: Apply "page-sidebar-closed-hide-logo" class to the body element to make the logo hidden on sidebar toggle -->
	<!-- DOC: Apply "page-sidebar-hide" class to body element to completely hide the sidebar on sidebar toggle -->
	<!-- DOC: Apply "page-sidebar-fixed" class to have fixed sidebar -->
	<!-- DOC: Apply "page-footer-fixed" class to the body element to have fixed footer -->
	<!-- DOC: Apply "page-sidebar-reversed" class to put the sidebar on the right side -->
	<!-- DOC: Apply "page-full-width" class to the body element to have full width page without the sidebar menu -->
	<body class="page-header-fixed page-quick-sidebar-over-content page-container-bg-solid">
		<!-- BEGIN HEADER -->
		<div class="page-header navbar navbar-fixed-top">
			<!-- BEGIN HEADER INNER -->
			<div class="page-header-inner">
				<!-- BEGIN LOGO -->
				<div class="page-logo" style="padding-left:2px;">
					<a href="javascript:" style="margin-top: 5px;font-size: 16px;font-weight: bold;color: white;text-decoration: none;line-height: 15px;">
						<span style="font-size:13px;"><?php echo $coreVariable['company_name']; ?></span> (<span style="font-size:13px;"><?php echo $coreVariable['location_name']; ?>)</span><br/>
						<span style="font-size:10px;">(<?php echo $coreVariable['fyValidFrom']; ?> to <?php echo $coreVariable['fyValidTo']; ?>)</span>
					</a>
					<div class="menu-toggler sidebar-toggler hide">
						<!-- DOC: Remove the above "hide" to enable the sidebar toggler button on header -->
					</div>
					<div class="top-menu">
					
					</div>
				</div>
				<!-- END LOGO -->
				<!-- BEGIN RESPONSIVE MENU TOGGLER -->
				<a href="javascript:;" class="menu-toggler responsive-toggler" data-toggle="collapse" data-target=".navbar-collapse">
				</a>
				<!-- END RESPONSIVE MENU TOGGLER -->
			</div>
			<!-- END HEADER INNER -->
		</div>
		<!-- END HEADER -->
		<div class="clearfix">
		</div>
		<!-- BEGIN CONTAINER -->
		<div class="page-container">
			<div class="page-sidebar navbar-collapse collapse">
				<!-- BEGIN SIDEBAR MENU -->
				<!-- DOC: Apply "page-sidebar-menu-light" class right after "page-sidebar-menu" to enable light sidebar menu style(without borders) -->
				<!-- DOC: Apply "page-sidebar-menu-hover-submenu" class right after "page-sidebar-menu" to enable hoverable(hover vs accordion) sub menu mode -->
				<!-- DOC: Apply "page-sidebar-menu-closed" class right after "page-sidebar-menu" to collapse("page-sidebar-closed" class must be applied to the body element) the sidebar sub menu mode -->
				<!-- DOC: Set data-auto-scroll="false" to disable the sidebar from auto scrolling/focusing -->
				<!-- DOC: Set data-keep-expand="true" to keep the submenues expanded -->
				<!-- DOC: Set data-auto-speed="200" to adjust the sub menu slide up/down speed -->
				<ul class="page-sidebar-menu" data-keep-expanded="false" data-auto-scroll="true" data-slide-speed="200">
					<?= $this->element('menu'); ?>
				</ul>
				<!-- BEGIN CONTENT -->
			</div>
			<div class="page-content-wrapper">
				<div class="page-content">
					<!-- BEGIN PAGE CONTENT-->
					<div class="row">
						<div class="col-md-12">
							<div id="toast-container" class="toast-top-right" aria-live="polite" role="alert">
								<?= $this->Flash->render() ?>
							</div>
							<?= $this->fetch('content') ?>
						</div>
					</div>
					<!-- END PAGE CONTENT-->
				</div>
			</div>
			<!-- END CONTENT -->
		</div>
		<!-- END CONTAINER -->
		<!-- BEGIN FOOTER -->
		<div class="page-footer">
			<div class="page-footer-inner">
				 2017 &copy; PHP Poets IT Solutions Pvt. Ltd.
			</div>
			<div class="scroll-to-top">
				<i class="icon-arrow-up"></i>
			</div>
		</div>
		<!-- END FOOTER -->
		<!-- BEGIN JAVASCRIPTS(Load javascripts at bottom, this will reduce page load time) -->
		<!-- BEGIN CORE PLUGINS -->
		<!--[if lt IE 9]>
		<script src="../../assets/global/plugins/respond.min.js"></script>
		<script src="../../assets/global/plugins/excanvas.min.js"></script> 
		<![endif]-->
		<?php echo $this->Html->script('/assets/global/plugins/jquery.min.js'); ?>
		<?php echo $this->Html->script('/assets/global/plugins/jquery-migrate.min.js'); ?>
		<!-- IMPORTANT! Load jquery-ui-1.10.3.custom.min.js before bootstrap.min.js to fix bootstrap tooltip conflict with jquery ui tooltip -->
		<?php echo $this->Html->script('/assets/global/plugins/jquery-ui/jquery-ui-1.10.3.custom.min.js'); ?>
		<?php echo $this->Html->script('/assets/global/plugins/bootstrap/js/bootstrap.min.js'); ?>
		<?php echo $this->Html->script('/assets/global/plugins/bootstrap-hover-dropdown/bootstrap-hover-dropdown.min.js'); ?>
		<?php echo $this->Html->script('/assets/global/plugins/jquery-slimscroll/jquery.slimscroll.min.js'); ?>
		<?php echo $this->Html->script('/assets/global/plugins/jquery.blockui.min.js'); ?>
		<?php echo $this->Html->script('/assets/global/plugins/jquery.cokie.min.js'); ?>
		<?php echo $this->Html->script('/assets/global/plugins/uniform/jquery.uniform.min.js'); ?>
		<?php echo $this->Html->script('/assets/global/plugins/bootstrap-switch/js/bootstrap-switch.min.js'); ?>
		<!-- END CORE PLUGINS -->
		<?= $this->fetch('PAGE_LEVEL_PLUGINS_JS')?>

		<?php echo $this->Html->script('/assets/global/scripts/metronic.js'); ?>
		<?php echo $this->Html->script('/assets/admin/layout/scripts/layout.js'); ?>

		<!-- BEGIN PAGE LEVEL SCRIPTS -->
		<?= $this->fetch('PAGE_LEVEL_SCRIPTS_JS')?>
		
		
		
		<!-- END PAGE LEVEL SCRIPTS -->
		<script>
		jQuery(document).ready(function() {  
			Metronic.init(); // init metronic core components
			Layout.init(); // init current layout
		});
		$(document).ready(function() {
			
			$('#transaction-date').datepicker({});
			
			
			
			$('a[role=button]').live('click',function(e) {
				e.preventDefault();
			});
			
			$('.numberOnly').die().live('keyup, change',function()
			{   var ex = /^[0-9]+\.?[0-9]*$/;
			    var evt=$(this).val();
				if(!evt.match(ex))
				{
					$(this).val('0');
				}
			});
			
		});
		function round(value, exp) {
		  if (typeof exp === 'undefined' || +exp === 0)
			return Math.round(value);

		  value = +value;
		  exp = +exp;

		  if (isNaN(value) || !(typeof exp === 'number' && exp % 1 === 0))
			return 0;

		  // Shift
		  value = value.toString().split('e');
		  value = Math.round(+(value[0] + 'e' + (value[1] ? (+value[1] + exp) : exp)));

		  // Shift back
		  value = value.toString().split('e');
		  return +(value[0] + 'e' + (value[1] ? (+value[1] - exp) : -exp));
		}
		</script>
		<?= $this->fetch('scriptBottom')?>
		<!-- END JAVASCRIPTS -->
	</body>
	<!-- END BODY -->
</html>