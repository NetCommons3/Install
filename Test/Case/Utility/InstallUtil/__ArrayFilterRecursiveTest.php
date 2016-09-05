<?php
/**
 * InstallUtil::__arrayFilterRecursive()のテスト
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
 * InstallUtil::__arrayFilterRecursive()のテスト
 *
 * @author Shohei Nakajima <nakajimashouhei@gmail.com>
 * @package NetCommons\Install\Test\Case\Utility\InstallUtil
 */
class PrivateInstallUtilityInstallUtilArrayFilterRecursiveTest extends NetCommonsCakeTestCase {

/**
 * Plugin name
 *
 * @var string
 */
	public $plugin = 'install';

/**
 * __arrayFilterRecursive()のテスト
 *
 * @return void
 */
	public function testArrayFilterRecursive() {
		//データ生成
		$input = array('aaaa', array('object' => new InstallUtil()));
		$callback = function ($val) {
			return !is_object($val);
		};

		//テスト実施
		$instance = new InstallUtil();
		$result = $this->_testReflectionMethod(
			$instance, '__arrayFilterRecursive', array($input, $callback)
		);

		//チェック
		$expected = array(
			'aaaa', array()
		);
		$this->assertEquals($expected, $result);
	}

}
