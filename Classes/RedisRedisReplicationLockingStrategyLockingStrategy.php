<?php
namespace Tourstream\RedisLockStrategy;
/***************************************************************
 *  Copyright notice
 *
 *  (c) 2017 Alexander Miehe (alexander.miehe@tourstream.eu)
 *  All rights reserved
 *
 *  You may not remove or change the name of the author above. See:
 *  http://www.gnu.org/licenses/gpl-faq.html#IWantCredit
 *
 *  This script is part of the Typo3 project. The Typo3 project is
 *  free software; you can redistribute it and/or modify
 *  it under the terms of the GNU General Public License as published by
 *  the Free Software Foundation; either version 3 of the License, or
 *  (at your option) any later version.
 *
 *  The GNU General Public License can be found at
 *  http://www.gnu.org/copyleft/gpl.html.
 *  A copy is found in the LICENSE and distributed with these scripts.
 *
 *
 *  This script is distributed in the hope that it will be useful,
 *  but WITHOUT ANY WARRANTY; without even the implied warranty of
 *  MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 *  GNU General Public License for more details.
 *
 *  This copyright notice MUST APPEAR in all copies of the script!
 ***************************************************************/
use TYPO3\CMS\Core\Locking\Exception\LockAcquireException;
use TYPO3\CMS\Core\Locking\Exception\LockAcquireWouldBlockException;

/**
 *
 */
class RedisReplicationLockingStrategy extends RedisLockStrategy
{
	/**
	 * @inheritdoc
	 */
	public function __construct($subject)
	{
		parent::__construct($subject);
		$replicationInfo = $this->redis->info('replication');
		if ($replicationInfo['role'] != 'master') {
			if ($replicationInfo['master_host']) {
				$this->redis->connect($replicationInfo['master_host'], $replicationInfo['master_port']);
				$this->redis->select((int) $config['database']);
			} else {
				throw new \TYPO3\CMS\Core\Cache\Exception('Master host could not be found.', 1549276254);
			}
		}
	}
}
