<?php
/**
*
* Extension User reports and stats Package.
*
* @copyright (c) 2020 webocean.info <https://www.webocean.info>
* @license GNU General Public License, version 2 (GPL-2.0)
*
*/

namespace hybridmind\user_reports_stats;

class ext extends \phpbb\extension\base {

	public function is_enableable() {
		$config = $this->container->get('config');
		return version_compare($config['version'], '3.2.0', '>=');
	}
}
