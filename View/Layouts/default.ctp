<?php
$cakeDescription = __d('cake_dev', 'CakePHP: the rapid development php framework');
?>
<!DOCTYPE html>
<html lang="en">
	<head>
	<meta charset="utf-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<script src="/net_commons/jquery/jquery.js"></script>
	<script src="/net_commons/twbs/bootstrap/dist/js/bootstrap.min.js"></script>
	<script src="/net_commons/twbs/bootstrap/assets/js/docs.min.js"></script>
	<title><?php
		if (isset($pageTitle)) {
			echo h($pageTitle);
		}
		?></title>

	<!-- Bootstrap -->
	<?php
	if (isset($bootstrapMinCss) && $bootstrapMinCss) {
		echo $this->Html->css('bootstrap.min.css');
	} else {
		?><link href="/net_commons/twbs/bootstrap/dist/css/bootstrap.min.css" rel="stylesheet"><?php
	}?>

	<link href="/net_commons/twbs/bootstrap/assets/css/docs.min.css" rel="stylesheet">
	<!-- base  -->
	<link href="/net_commons/base/css/style.css" rel="stylesheet">

	<!-- themed  -->
	<?php echo $this->Html->css("style"); ?>

	<?php
		echo $this->fetch('meta');
		echo $this->fetch('css');
		echo $this->fetch('script');
	?>
</head>
	<body>

	<div class="container">
		<?php echo $this->Session->flash(); ?>
		<?php echo $this->fetch('content'); ?>
	</div>

	<!-- container-footer  -->
	<footer id="container-footer" role="contentinfo">
		<div class="box-footer box-id-5">
			<div class="copyright">Powered by NetCommons</div>
		</div>
	</footer>

		<!-- /container -->
		<?php echo $this->element('sql_dump'); ?>

	</body>
</html>
