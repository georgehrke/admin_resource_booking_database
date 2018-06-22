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

use OCA\AdminResourceBookingDatabase\Mapper\RoomMapper;
use OCP\Calendar\Room\IBackend;
use OCP\Calendar\Room\IRoom;

/**
 * Class RoomBackend
 *
 * @package OCA\AdminResourceBookingDatabase\Integration
 */
class RoomBackend implements IBackend {

	/** @var RoomMapper */
	private $mapper;

	/**
	 * RoomBackend constructor.
	 *
	 * @param RoomMapper $mapper
	 */
	public function __construct(RoomMapper $mapper) {
		$this->mapper = $mapper;
	}

	/**
	 * @return IRoom[]
	 */
	public function getAllRooms(): array {
		$rooms = $this->mapper->getAll();
		$return = [];

		foreach($rooms as $room) {
			$return[] = $this->rowToRoom($room);
		}

		return $return;
	}

	/**
	 * @param string $id
	 * @return IRoom
	 */
	public function getRoom($id):IRoom {
		$resource = $this->mapper->getByResourceId($id);
		if (!$resource) {
			return null;
		}

		return $this->rowToRoom($resource);
	}

	/**
	 * @return string[]
	 */
	public function listAllRooms(): array {
		$rooms = $this->mapper->getAll();
		$return = [];

		foreach($rooms as $room) {
			$return[] = $room['resource_id'];
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
	 * @return IRoom
	 */
	private function rowToRoom(array $row): IRoom {
		return new Room(
			$row['resource_id'],
			$row['displayname'],
			$row['email'],
			$row['group_restrictions'],
			$this
		);
	}
}
