<?php
/**
 * Install All Test Case
 *
 * @author Noriko Arai <arai@nii.ac.jp>
 * @author Kotaro Hokada <kotaro.hokada@gmail.com>
 * @link http://www.netcommons.org NetCommons Project
 * @license http://www.netcommons.org/license.txt NetCommons License
 * @copyright Copyright 2014, NetCommons Project
 */

App::uses('NetCommonsTestSuite', 'NetCommons.TestSuite');

/**
 * Install All Test Case
 *
 * @package NetCommons\Install\Test
 * @codeCoverageIgnore
 */
class AllInstallTest extends NetCommonsTestSuite {

/**
 * All test suite
 *
 * @return CakeTestSuite
 * @SuppressWarnings(PHPMD)
 */
	public static function suite() {
		$plugin = preg_replace('/^All([\w]+)Test$/', '$1', __CLASS__);

		$Folder = new Folder(CakePlugin::path($plugin) . 'Test' . DS . 'Case');
		$files = $Folder->tree(null, true, 'files');
		foreach ($files as $file) {
			if (preg_match('/\/All([\w]+)Test\.php$/', $file)) {
				continue;
			}
			if (substr($file, -8) === 'Test.php') {
				var_dump($file);
			}
		}

		$suite = new CakeTestSuite(sprintf('All %s Plugin tests', $plugin));
		$suite->addTestDirectoryRecursive(CakePlugin::path($plugin) . 'Test' . DS . 'Case');
		return $suite;
	}
}
