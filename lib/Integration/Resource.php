<?php
/**
 * @copyright 2018, Georg Ehrke <oc.list@georgehrke.com>
 *
 * @author Georg Ehrke <oc.list@georgehrke.com>
 *
 * @license GNU AGPL version 3 or any later version
 *
 * This program is free software: you can redistribute it and/or modify
 * it under the terms of the GNU Affero General Public License as
 * published by the Free Software Foundation, either version 3 of the
 * License, or (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU Affero General Public License for more details.
 *
 * You should have received a copy of the GNU Affero General Public License
 * along with this program.  If not, see <http://www.gnu.org/licenses/>.
 *
 */
namespace OCA\AdminResourceBookingDatabase\Integration;

use OCP\Calendar\Resource\IBackend;
use OCP\Calendar\Resource\IResource;

/**
 * Class Resource
 *
 * @package OCA\AdminResourceBookingDatabase\Integration
 */
class Resource implements IResource {

	/**
	 * @var string
	 */
	private $resource;

	/**
	 * @var string
	 */
	private $displayName;

	/**
	 * @var string
	 */
	private $email;

	/**
	 * @var array
	 */
	private $groupRestrictions;

	/**
	 * @var IBackend
	 */
	private $backend;

	/**
	 * Resource constructor.
	 *
	 * @param string $resource
	 * @param string $displayName
	 * @param string $email
	 * @param array $groupRestrictions
	 * @param IBackend $backend
	 */
	public function __construct(string $resource, string $displayName, string $email,
								   array $groupRestrictions, IBackend $backend) {
		$this->resource = $resource;
		$this->displayName = $displayName;
		$this->email = $email;
		$this->groupRestrictions = $groupRestrictions;
		$this->backend = $backend;
	}

	/**
	 * @return string
	 */
	public function getId(): string {
		return $this->resource;
	}

	/**
	 * @return string
	 */
	public function getDisplayName(): string {
		return $this->displayName;
	}

	/**
	 * @return string
	 */
	public function getEMail(): string {
		return $this->email;
	}

	/**
	 * @return array
	 */
	public function getGroupRestrictions(): array {
		return $this->groupRestrictions;
	}

	/**
	 * @return IBackend
	 */
	public function getBackend(): IBackend {
		return $this->backend;
	}
}
