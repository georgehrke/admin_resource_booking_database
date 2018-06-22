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

use OCA\AdminResourceBookingDatabase\Mapper\ResourceMapper;
use OCP\Calendar\Resource\IBackend;
use OCP\Calendar\Resource\IResource;

/**
 * Class ResourceBackend
 *
 * @package OCA\AdminResourceBookingDatabase\Integration
 */
class ResourceBackend implements IBackend {

	/** @var ResourceMapper */
	private $mapper;

	/**
	 * ResourceBackend constructor.
	 *
	 * @param ResourceMapper $mapper
	 */
	public function __construct(ResourceMapper $mapper) {
		$this->mapper = $mapper;
	}

	/**
	 * @return IResource[]
	 */
	public function getAllResources(): array {
		$resources = $this->mapper->getAll();
		$return = [];

		foreach($resources as $resource) {
			$return[] = $this->rowToResource($resource);
		}

		return $return;
	}

	/**
	 * @param string $id
	 * @return IResource|null
	 */
	public function getResource($id):IResource {
		$resource = $this->mapper->getByResourceId($id);
		if (!$resource) {
			return null;
		}

		return $this->rowToResource($resource);
	}

	/**
	 * @return string[]
	 */
	public function listAllResources(): array {
		$resources = $this->mapper->getAll();
		$return = [];

		foreach($resources as $resource) {
			$return[] = $resource['resource_id'];
		}

		return $return;
	}

	/**
	 * @return string
	 */
	public function getBackendIdentifier(): string {
		return 'admin_resource_booking_database';
	}

	/**
	 * @param array $row
	 * @return IResource
	 */
	private function rowToResource(array $row):IResource {
		return new Resource(
			$row['resource_id'],
			$row['displayname'],
			$row['email'],
			$row['group_restrictions'],
			$this
		);
	}
}
