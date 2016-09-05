<?php
/**
 * Config/routesのテスト
 *
 * @author Noriko Arai <arai@nii.ac.jp>
 * @author Shohei Nakajima <nakajimashouhei@gmail.com>
 * @link http://www.netcommons.org NetCommons Project
 * @license http://www.netcommons.org/license.txt NetCommons License
 * @copyright Copyright 2014, NetCommons Project
 */

App::uses('NetCommonsRoutesTestCase', 'NetCommons.TestSuite');

/**
 * Config/routesのテスト
 *
 * @author Shohei Nakajima <nakajimashouhei@gmail.com>
 * @package NetCommons\Install\Test\Case\Config
 */
class RoutesTest extends NetCommonsRoutesTestCase {

/**
 * Plugin name
 *
 * @var string
 */
	public $plugin = 'install';

/**
 * DataProvider
 *
 * ### 戻り値
 *  - url URL
 *  - expected 期待値
 *  - settingMode セッティングモード
 *
 * @return array
 */
	public function dataProvider() {
		//テストデータ
		return array(
			array(
				'url' => '/install/index',
				'expected' => array(
					'plugin' => 'install', 'controller' => 'install', 'action' => 'index',
				),
				'settingMode' => false
			),
			array(
				'url' => '/install/install/init_permission',
				'expected' => array(
					'plugin' => 'install', 'controller' => 'install', 'action' => 'init_permission',
				),
				'settingMode' => false
			),
		);
	}

}
