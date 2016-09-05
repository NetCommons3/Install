<?php
/**
 * InstallUtil::__construct()のテスト
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
 * InstallUtil::__construct()のテスト
 *
 * @author Shohei Nakajima <nakajimashouhei@gmail.com>
 * @package NetCommons\Install\Test\Case\Utility\InstallUtil
 */
class PrivateInstallUtilityInstallUtilConstructTest extends NetCommonsCakeTestCase {

/**
 * Plugin name
 *
 * @var string
 */
	public $plugin = 'install';

/**
 * __construct()のテスト
 *
 * @return void
 */
	public function testConstruct() {
		//データ生成
		Configure::write('Security.salt', 'testSalt');
		Configure::write('Security.cipherSeed', 'testCipherSeed');

		$salt = Configure::read('Security.salt');
		$cipherSeed = Configure::read('Security.cipherSeed');

		//テスト実施
		$instance = new InstallUtil();

		//チェック
		$this->assertEquals(get_class($instance), 'InstallUtil');
		$this->assertEquals($salt, Configure::read('Security.salt'));
		$this->assertEquals($cipherSeed, Configure::read('Security.cipherSeed'));
	}

/**
 * Configure::read('Security.salt')がデフォルトの時のテスト
 *
 * @return void
 */
	public function testByDefaultSalt() {
		//データ生成
		Configure::write('Security.salt', 'DYhG93b0qyJfIxfs2guVoUubWwvniR2G0FgaC9mi');
		Configure::write('Security.cipherSeed', 'testCipherSeed');

		$salt = Configure::read('Security.salt');
		$cipherSeed = Configure::read('Security.cipherSeed');

		//テスト実施
		$instance = new InstallUtil();

		//チェック
		$this->assertEquals(get_class($instance), 'InstallUtil');
		$this->assertNotEquals($salt, Configure::read('Security.salt'));
		$this->assertNotEquals($cipherSeed, Configure::read('Security.cipherSeed'));
	}

/**
 * Configure::read('Security.cipherSeed')がデフォルトの時のテスト
 *
 * @return void
 */
	public function testByDefaultCipherSeed() {
		//データ生成
		Configure::write('Security.salt', 'testSalt');
		Configure::write('Security.cipherSeed', '76859309657453542496749683645');

		$salt = Configure::read('Security.salt');
		$cipherSeed = Configure::read('Security.cipherSeed');

		//テスト実施
		$instance = new InstallUtil();

		//チェック
		$this->assertEquals(get_class($instance), 'InstallUtil');
		$this->assertNotEquals($salt, Configure::read('Security.salt'));
		$this->assertNotEquals($cipherSeed, Configure::read('Security.cipherSeed'));
	}

}
