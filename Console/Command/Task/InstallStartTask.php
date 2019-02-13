<?php
/**
 * Installの開始
 *
 * @author Noriko Arai <arai@nii.ac.jp>
 * @author Shohei Nakajima <nakajimashouhei@gmail.com>
 * @link http://www.netcommons.org NetCommons Project
 * @license http://www.netcommons.org/license.txt NetCommons License
 * @copyright Copyright 2014, NetCommons Project
 */

App::uses('InstallAppTask', 'Install.Console/Command');

/**
 * Installの開始
 *
 * @author Shohei Nakajima <nakajimashouhei@gmail.com>
 * @package NetCommons\Install\Console\Command
 */
class InstallStartTask extends InstallAppTask {

/**
 * Base URLのオプションKey
 *
 * @var string
 */
	const KEY_BASE_URL = 'base-url';

/**
 * デフォルト言語のオプションKey
 *
 * @var string
 */
	const KEY_LANGUAGE = 'lang';

/**
 * 有効な言語のオプションKey
 *
 * @var string
 */
	const KEY_ENABLE_LANGUAGES = 'enable-langs';

/**
 * Execution method always used for tasks
 *
 * @return void
 */
	public function execute() {
		parent::execute();

		//引数のセット
		if (isset($this->params[self::KEY_BASE_URL])) {
			$fullBaseUrl = $this->params[self::KEY_BASE_URL];
		} else {
			$fullBaseUrl = $this->in(__d('install', 'Please entry base url.'));
		}
		if (! $fullBaseUrl) {
			$message = __d(
				'install',
				'Failed to base url.'
			);
			return $this->error($message);
		}
		Configure::write('App.fullBaseUrl', $fullBaseUrl);

		//if (array_key_exists(self::KEY_ENABLE_LANGUAGES, $this->params)) {
		//	$languageEnabled = 'en,ja';
		//} else {
		//	$languageEnabled = $this->in(
		//		__d('install', 'Enable languages?'), ['en,ja', 'en', 'ja'], 'en,ja'
		//	);
		//}

		if (array_key_exists(self::KEY_LANGUAGE, $this->params)) {
			$currentLanguage = Hash::get($this->params, self::KEY_LANGUAGE, 'ja');
		} else {
			$currentLanguage = $this->in(__d('install', 'Current language?'), ['en', 'ja'], 'ja');
		}

		//application.ymlの初期化処理
		$data['language'] = strtolower($currentLanguage);
		//$data['languageEnabled'] = explode(',', strtolower($languageEnabled));
		if (! $this->InstallUtil->installApplicationYaml($data)) {
			$message = __d(
				'install',
				'Failed to write %s. Please check permission.',
				[APP . 'Config' . DS . 'application.yml']
			);
			return $this->error($message);
		}
	}

/**
 * Gets the option parser instance and configures it.
 *
 * @return ConsoleOptionParser
 */
	public function getOptionParser() {
		$parser = parent::getOptionParser();

		$parser->description(__d('install', 'NetCommons Install Step 1'))
			->addOption(self::KEY_BASE_URL, array(
				'help' => __d('install', 'Full base url. e.g.) http://localhost'),
				'required' => false
			))
			->addOption(self::KEY_LANGUAGE, array(
				'help' => __d('install', 'Current language. (en/ja)'),
				'required' => false
			));
			//->addOption(self::KEY_ENABLE_LANGUAGES, array(
			//	'help' => __d('install', 'Enable languages. (en,ja/en/ja)'),
			//	'required' => false
			//));

		return $parser;
	}
}
