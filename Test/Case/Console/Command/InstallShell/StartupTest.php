<?php
/**
 * InstallShell::startup()のテスト
 *
 * @author Noriko Arai <arai@nii.ac.jp>
 * @author Shohei Nakajima <nakajimashouhei@gmail.com>
 * @link http://www.netcommons.org NetCommons Project
 * @license http://www.netcommons.org/license.txt NetCommons License
 * @copyright Copyright 2014, NetCommons Project
 */

App::uses('NetCommonsConsoleTestCase', 'NetCommons.TestSuite');

/**
 * InstallShell::startup()のテスト
 *
 * @author Shohei Nakajima <nakajimashouhei@gmail.com>
 * @package NetCommons\Install\Test\Case\Console\Command\InstallShell
 */
class InstallConsoleCommandInstallShellStartupTest extends NetCommonsConsoleTestCase {

/**
 * Fixtures
 *
 * @var array
 */
	public $fixtures = array();

/**
 * Plugin name
 *
 * @var string
 */
	public $plugin = 'install';

/**
 * Shell name
 *
 * @var string
 */
	protected $_shellName = 'InstallShell';

/**
 * startup()のテスト
 *
 * @return void
 */
	public function testStartup() {
		$shell = $this->_shellName;
		$this->$shell = $this->loadShell($shell);

		//チェック
		$this->$shell->expects($this->at(1))->method('out')
			->with(__d('install', 'NetCommons Install'));

		//テスト実施
		$this->$shell->startup();
	}

}
