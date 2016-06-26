<?php
/**
 * Install Controller
 *
 * @author Noriko Arai <arai@nii.ac.jp>
 * @author Shohei Nakajima <nakajimashouhei@gmail.com>
 * @link http://www.netcommons.org NetCommons Project
 * @license http://www.netcommons.org/license.txt NetCommons License
 * @copyright Copyright 2014, NetCommons Project
 */

App::uses('InstallAppController', 'Install.Controller');
App::uses('InstallUtil', 'Install.Utility');

/**
 * Install Controller
 *
 * @package NetCommons\Install\Controlelr
 */
class InstallController extends InstallAppController {

/**
 * Helpers
 */
	public $helpers = array('M17n.M17n');

/**
 * beforeFilter
 *
 * @return void
 * @throws NotFoundException
 **/
	public function beforeFilter() {
		if (Configure::read('NetCommons.installed')) {
			throw new NotFoundException;
		}
		$this->Auth->allow();
		$this->layout = 'Install.default';

		$this->InstallUtil = new InstallUtil();

		$this->Components->unload('NetCommons.Permission');
	}

/**
 * ステップ 1
 * application.ymlの初期値セット
 *
 * @return void
 **/
	public function index() {
		// Initialize master database connection
		$configs = $this->InstallUtil->chooseDBByEnvironment();
		if (! $this->InstallUtil->saveDBConf($configs)) {
			$message = __d(
				'install',
				'Failed to write %s. Please check permission.',
				array(APP . 'Config' . DS . 'database.php')
			);
			$this->Session->setFlash($message);
			return;
		}

		if (! $this->InstallUtil->installApplicationYaml($this->request->query)) {
			$message = __d(
				'install',
				'Failed to write %s. Please check permission.',
				array(APP . 'Config' . DS . 'application.yml')
			);
			$this->Session->setFlash($message);
			return;
		}

		if ($this->request->is('post')) {
			$this->redirect(array('action' => 'init_permission'));
		}
	}

/**
 * ステップ 2
 * パーミッションのチェック
 *
 * @return void
 **/
	public function init_permission() {
		// Check permissions
		$permissions = array();
		$ret = true;
		// Actually we don't have to check app/Config and app/tmp here,
		// since cakephp itself cannot handle requests w/o these directories with proper permission.
		// Just a stub action for future release.
		$writables = [APP . 'Config', APP . 'tmp', ROOT . DS . 'composer.json', ROOT . DS . 'bower.json'];
		foreach ($writables as $path) {
			if (is_writable($path)) {
				$permissions[] = array(
					'message' => __d('install', '%s is writable', array($path)),
					'error' => false,
				);
			} else {
				$ret = false;
				$permissions[] = array(
					'message' => __d(
						'install', 'Failed to write %s. Please check permission.', array($path)
					),
					'error' => true,
				);
			}
		}

		// Show current page on failure
		if (!$ret) {
			foreach ($permissions as $permission) {
				CakeLog::error($permission['message']);
			}
			$this->set('permissions', $permissions);
			return;
		}

		if ($this->request->is('post')) {
			$this->redirect(array('action' => 'init_db'));
			return;
		}
		$this->set('permissions', $permissions);
	}

/**
 * ステップ 3
 * データベース設定
 *
 * @return void
 */
	public function init_db() {
		// Destroy session in order to handle ping request
		$this->Session->destroy();

		$this->set('masterDB', $this->InstallUtil->chooseDBByEnvironment());
		$this->set('errors', array());
		if ($this->request->is('post')) {
			// タイムアウトはっせいするなら適宜設定
			// set_time_limit(1800);

			$this->loadModel('Install.DatabaseConfiguration');
			$this->DatabaseConfiguration->set($this->request->data);
			if ($this->DatabaseConfiguration->validates()) {
				// Update database connection
				$this->InstallUtil->saveDBConf($this->request->data['DatabaseConfiguration']);
			} else {
				$this->response->statusCode(400);
				CakeLog::info(sprintf('Validation error: %s',
				implode(', ', array_keys($this->DatabaseConfiguration->validationErrors))));
				return;
			}

			if (!$this->InstallUtil->createDB($this->request->data['DatabaseConfiguration'])) {
				$this->response->statusCode(400);
				CakeLog::info('Failed to create database');
				return;
			}

			//$update = isset($this->request->data['update']);

			// Install migrations
			if (!$this->InstallUtil->installMigrations('master')) {
				CakeLog::error('Failed to install migrations');
				return;
			}

			$this->redirect(array('action' => 'init_admin_user'));
		}
	}

/**
 * ステップ 4
 * 管理者アカウントの登録
 *
 * @return void
 */
	public function init_admin_user() {
		if ($this->request->is('post')) {
			if (! $this->InstallUtil->saveAdminUser($this->request->data)) {
				$this->Session->setFlash(
					__d('install', 'The user could not be saved. Please try again.')
				);
				return;
			}

			$this->redirect(array('action' => 'finish'));
		}
	}

/**
 * ステップ 5
 * インストール終了
 *
 * @return void
 **/
	public function finish() {
		Configure::write('NetCommons.installed', true);
		/* Configure::write('NetCommons.installed', false); */
		$this->InstallUtil->saveAppConf();
	}

}
