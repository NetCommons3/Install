<?php
/**
 * InstallUtil::saveDBConf()のテスト
 *
 * @author Noriko Arai <arai@nii.ac.jp>
 * @author Shohei Nakajima <nakajimashouhei@gmail.com>
 * @link http://www.netcommons.org NetCommons Project
 * @license http://www.netcommons.org/license.txt NetCommons License
 * @copyright Copyright 2014, NetCommons Project
 */

App::uses('NetCommonsCakeTestCase', 'NetCommons.TestSuite');
App::uses('InstallUtil', 'Install.Utility');

/**
 * InstallUtil::saveDBConf()のテスト
 *
 * @author Shohei Nakajima <nakajimashouhei@gmail.com>
 * @package NetCommons\Install\Test\Case\Utility\InstallUtil
 */
class InstallUtilityInstallUtilSaveDBConfTest extends NetCommonsCakeTestCase {

/**
 * Plugin name
 *
 * @var string
 */
	public $plugin = 'install';

/**
 * setUp method
 *
 * @return void
 */
	public function setUp() {
		parent::setUp();
		$this->__prepare();
	}

/**
 * tearDown method
 *
 * @return void
 */
	public function tearDown() {
		$this->__prepare();
		parent::tearDown();
	}

/**
 * 事前準備
 *
 * @return void
 */
	private function __prepare() {
		$instance = new InstallUtil();
		if (file_exists(APP . 'Config' . DS . $instance->appYmlPrefix . 'database.php')) {
			unlink(APP . 'Config' . DS . $instance->appYmlPrefix . 'database.php');
		}
	}

/**
 * saveDBConf()のテスト
 *
 * @return void
 */
	public function testSaveDBConf() {
		//事前準備
		$instance = new InstallUtil();
		$this->assertFalse(file_exists(APP . 'Config' . DS . $instance->appYmlPrefix . 'database.php'));

		//データ生成
		$configs = array();

		//テスト実施
		$result = $instance->saveDBConf($configs);

		//チェック
		$this->assertTrue($result);
		$this->assertTrue(file_exists(APP . 'Config' . DS . $instance->appYmlPrefix . 'database.php'));
	}

}
