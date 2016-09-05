<?php
/**
 * InstallShell::getOptionParser()のテスト
 *
 * @author Noriko Arai <arai@nii.ac.jp>
 * @author Shohei Nakajima <nakajimashouhei@gmail.com>
 * @link http://www.netcommons.org NetCommons Project
 * @license http://www.netcommons.org/license.txt NetCommons License
 * @copyright Copyright 2014, NetCommons Project
 */

App::uses('NetCommonsConsoleTestCase', 'NetCommons.TestSuite');

/**
 * InstallShell::getOptionParser()のテスト
 *
 * @author Shohei Nakajima <nakajimashouhei@gmail.com>
 * @package NetCommons\Install\Test\Case\Console\Command\InstallShell
 */
class InstallConsoleCommandInstallShellGetOptionParserTest extends NetCommonsConsoleTestCase {

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
 * getOptionParser()のテスト
 *
 * @return void
 */
	public function testGetOptionParser() {
		$shell = $this->_shellName;
		$this->$shell = $this->loadShell($shell, 'h');

		//チェック
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
		$result = $this->$shell->getOptionParser();

		//チェック
		$this->assertEquals('ConsoleOptionParser', get_class($result));

		$expected = array(
			'install_start' . ' ' . __d('install', 'Install Step 1'),
			'install_permission' . ' ' . __d('install', 'Install Step 2'),
			'create_database' . ' ' . __d('install', 'Install Step 3'),
			'install_migrations' . ' ' . __d('install', 'Install Step 4'),
			'install_bower' . ' ' . __d('install', 'Install Step 5'),
			'save_administrator' . ' ' . __d('install', 'Install Step 6'),
			'install_finish' . ' ' . __d('install', 'Install End'),
		);
		$actual = array(
			$result->subcommands()['install_start']->help(strlen('install_start') + 1),
			$result->subcommands()['install_permission']->help(strlen('install_permission') + 1),
			$result->subcommands()['create_database']->help(strlen('create_database') + 1),
			$result->subcommands()['install_migrations']->help(strlen('install_migrations') + 1),
			$result->subcommands()['install_bower']->help(strlen('install_bower') + 1),
			$result->subcommands()['save_administrator']->help(strlen('save_administrator') + 1),
			$result->subcommands()['install_finish']->help(strlen('install_finish') + 1),
		);
		$this->assertEquals($expected, $actual);
	}

}
