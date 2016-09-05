<?php
/**
 * InstallController::beforeFilter()のテスト
 *
 * @author Noriko Arai <arai@nii.ac.jp>
 * @author Shohei Nakajima <nakajimashouhei@gmail.com>
 * @link http://www.netcommons.org NetCommons Project
 * @license http://www.netcommons.org/license.txt NetCommons License
 * @copyright Copyright 2014, NetCommons Project
 */

App::uses('NetCommonsControllerTestCase', 'NetCommons.TestSuite');

/**
 * InstallController::beforeFilter()のテスト
 *
 * @author Shohei Nakajima <nakajimashouhei@gmail.com>
 * @package NetCommons\Install\Test\Case\Controller\InstallController
 */
class InstallControllerBeforeFilterTest extends NetCommonsControllerTestCase {

/**
 * Fixtures
 *
 * @var array
 */
	public $fixtures = array(
	);

/**
 * Plugin name
 *
 * @var string
 */
	public $plugin = 'install';

/**
 * Controller name
 *
 * @var string
 */
	protected $_controller = 'install';

/**
 * index()アクションのGetリクエストテスト
 *
 * @return void
 */
	public function testBeforeFilter() {
		Configure::write('NetCommons.installed', false);

		//テスト実行
		$this->assertTrue($this->controller->Components->loaded('NetCommons.Permission'));
		$this->_testGetAction(array('action' => 'index'), array('method' => 'assertNotEmpty'), null, 'view');

		//チェック
		$this->assertNotEmpty($this->view);
		$this->assertFalse($this->controller->Components->loaded('NetCommons.Permission'));
	}

/**
 * インストール済みのテスト
 *
 * @return void
 */
	public function testBeforeFilterOnExceptionError() {
		Configure::write('NetCommons.installed', true);

		//テスト実行
		$this->_testGetAction(array('action' => 'index'), null, 'NotFoundException', 'view');
	}

}
