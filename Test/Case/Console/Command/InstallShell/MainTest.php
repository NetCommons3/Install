<?php
/**
 * InstallShell::main()のテスト
 *
 * @author Noriko Arai <arai@nii.ac.jp>
 * @author Shohei Nakajima <nakajimashouhei@gmail.com>
 * @link http://www.netcommons.org NetCommons Project
 * @license http://www.netcommons.org/license.txt NetCommons License
 * @copyright Copyright 2014, NetCommons Project
 */

App::uses('NetCommonsConsoleTestCase', 'NetCommons.TestSuite');

/**
 * InstallShell::main()のテスト
 *
 * @author Shohei Nakajima <nakajimashouhei@gmail.com>
 * @package NetCommons\Install\Test\Case\Console\Command\InstallShell
 */
class InstallConsoleCommandInstallShellMainTest extends NetCommonsConsoleTestCase {

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
 * setUp method
 *
 * @return void
 */
	public function setUp() {
		parent::setUp();
		Configure::write('NetCommons.installed', false);
	}

/**
 * tearDown method
 *
 * @return void
 */
	public function tearDown() {
		Configure::write('NetCommons.installed', true);
		parent::tearDown();
	}

/**
 * Shell name
 *
 * @var string
 */
	protected $_shellName = 'InstallShell';

/**
 * main()のチェック
 *
 * @return void
 */
	private function __expectsMain() {
		$shell = $this->_shellName;

		$this->$shell->expects($this->at(0))->method('out')
			->with(__d('install', '[S]tart'));
		$this->$shell->expects($this->at(1))->method('out')
			->with(__d('install', '[H]elp'));
		$this->$shell->expects($this->at(2))->method('out')
			->with(__d('install', '[Q]uit'));
	}

/**
 * インストール済みのmain()のテスト
 *
 * @return void
 */
	public function testOnInstalled() {
		Configure::write('NetCommons.installed', true);
		$shell = $this->_shellName;
		$this->$shell = $this->loadShell($shell);

		//チェック
		$this->$shell->expects($this->at(0))->method('error')
			->with(__d('install', 'Already installed.'));

		//テスト実施
		$this->$shell->main();
	}

/**
 * main()のテスト
 *
 * @return void
 */
	public function testMain() {
		$shell = $this->_shellName;
		$this->$shell = $this->loadShell($shell);

		//チェック
		$this->__expectsMain();

		//テスト実施
		$this->$shell->main();
	}

/**
 * main()のテスト[Start]
 *
 * @return void
 */
	public function testMainStart() {
		$shell = $this->_shellName;
		$this->$shell = $this->loadShell($shell, 's');

		//チェック
		$this->__expectsMain();

		$this->$shell->InstallStart = $this->getMock('InstallStart',
				array('execute'), array(), '', false);
		$this->$shell->InstallStart->expects($this->once())->method('execute')
			->will($this->returnValue(true));

		$this->$shell->InstallPermission = $this->getMock('InstallPermission',
				array('execute'), array(), '', false);
		$this->$shell->InstallPermission->expects($this->once())->method('execute')
			->will($this->returnValue(true));

		$this->$shell->CreateDatabase = $this->getMock('CreateDatabase',
				array('execute'), array(), '', false);
		$this->$shell->CreateDatabase->expects($this->once())->method('execute')
			->will($this->returnValue(true));

		$this->$shell->InstallMigrations = $this->getMock('InstallMigrations',
				array('execute'), array(), '', false);
		$this->$shell->InstallMigrations->expects($this->once())->method('execute')
			->will($this->returnValue(true));

		$this->$shell->InstallBower = $this->getMock('InstallBower',
				array('execute'), array(), '', false);
		$this->$shell->InstallBower->expects($this->once())->method('execute')
			->will($this->returnValue(true));

		$this->$shell->SaveAdministrator = $this->getMock('SaveAdministrator',
				array('execute'), array(), '', false);
		$this->$shell->SaveAdministrator->expects($this->once())->method('execute')
			->will($this->returnValue(true));

		$this->$shell->InstallFinish = $this->getMock('InstallFinish',
				array('execute'), array(), '', false);
		$this->$shell->InstallFinish->expects($this->once())->method('execute')
			->will($this->returnValue(true));

		$this->$shell->expects($this->at(3))->method('out')
			->with('<success>' . __d('install', 'Install success.') . '</success>');

		$this->$shell->expects($this->once())->method('_stop')
			->will($this->returnValue(true));

		//テスト実施
		$this->$shell->main();
	}

/**
 * main()のテスト[Help]
 *
 * @return void
 */
	public function testMainHelp() {
		$shell = $this->_shellName;
		$this->$shell = $this->loadShell($shell, 'h');

		//チェック
		$this->__expectsMain();

		$this->$shell->InstallStart = $this->getMock('InstallStart',
				array('getOptionParser'), array(), '', false);
		$this->$shell->InstallStart->expects($this->once())->method('getOptionParser')
			->will($this->returnValue(true));

		$this->$shell->InstallPermission = $this->getMock('InstallPermission',
				array('getOptionParser'), array(), '', false);
		$this->$shell->InstallPermission->expects($this->once())->method('getOptionParser')
			->will($this->returnValue(true));

		$this->$shell->CreateDatabase = $this->getMock('CreateDatabase',
				array('getOptionParser'), array(), '', false);
		$this->$shell->CreateDatabase->expects($this->once())->method('getOptionParser')
			->will($this->returnValue(true));

		$this->$shell->InstallMigrations = $this->getMock('InstallMigrations',
				array('getOptionParser'), array(), '', false);
		$this->$shell->InstallMigrations->expects($this->once())->method('getOptionParser')
			->will($this->returnValue(true));

		$this->$shell->InstallBower = $this->getMock('InstallBower',
				array('getOptionParser'), array(), '', false);
		$this->$shell->InstallBower->expects($this->once())->method('getOptionParser')
			->will($this->returnValue(true));

		$this->$shell->SaveAdministrator = $this->getMock('SaveAdministrator',
				array('getOptionParser'), array(), '', false);
		$this->$shell->SaveAdministrator->expects($this->once())->method('getOptionParser')
			->will($this->returnValue(true));

		$this->$shell->InstallFinish = $this->getMock('InstallFinish',
				array('getOptionParser'), array(), '', false);
		$this->$shell->InstallFinish->expects($this->once())->method('getOptionParser')
			->will($this->returnValue(true));

		//テスト実施
		$this->$shell->main();
	}

/**
 * main()のテスト[Quit]
 *
 * @return void
 */
	public function testMainQuit() {
		$shell = $this->_shellName;
		$this->$shell = $this->loadShell($shell, 'q');

		//チェック
		$this->__expectsMain();

		$this->$shell->expects($this->exactly(3))->method('out')
			->will($this->returnValue(true));
		$this->$shell->expects($this->once())->method('_stop')
			->will($this->returnValue(true));

		//テスト実施
		$this->$shell->main();
	}

}
