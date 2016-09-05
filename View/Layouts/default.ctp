<?php
/**
 * レイアウト
 *
 * @author Shohei Nakajima <nakajimashouhei@gmail.com>
 * @link http://www.netcommons.org NetCommons Project
 * @license http://www.netcommons.org/license.txt NetCommons License
 * @copyright Copyright 2014, NetCommons Project
 */
?>
<!DOCTYPE html>
<html lang="ja">
	<head>
		<meta charset="utf-8">
		<meta http-equiv="X-UA-Compatible" content="IE=edge">
		<meta name="viewport" content="width=device-width, initial-scale=1">

		<title>
			<?php
				if (isset($pageTitle)) {
					echo h($pageTitle) . ' - ' . __d('install', 'Install');
				}
			?>
		</title>

		<?php
			echo $this->fetch('meta');

			echo $this->Html->css(
				array(
					'/components/bootstrap/dist/css/bootstrap.min.css',
					'/components/jqueryui/themes/overcast/jquery-ui.min.css',
					'/net_commons/css/style.css',
					'style'
				),
				array('plugin' => false)
			);
			echo $this->fetch('css');

			echo $this->Html->script(
				array(
					'/components/jquery/dist/jquery.min.js',
					'/components/jqueryui/jquery-ui.min.js',
					'/components/bootstrap/dist/js/bootstrap.min.js',
					'/components/angular/angular.min.js',
					'/components/angular-bootstrap/ui-bootstrap-tpls.min.js',
					'/net_commons/js/base.js',
					'/net_commons/jquery.cookie.js'
				),
				array('plugin' => false)
			);
			echo $this->fetch('script');
		?>
	</head>

	<body ng-controller="NetCommons.base">
		<div class="container">
			<?php echo $this->fetch('content'); ?>
		</div>

		<footer id="nc-system-footer" role="contentinfo">
			<div class="box-footer box-id-5">
				<div class="copyright">Powered by NetCommons</div>
			</div>
		</footer>

		<?php echo $this->element('sql_dump'); ?>

	</body>
</html>
