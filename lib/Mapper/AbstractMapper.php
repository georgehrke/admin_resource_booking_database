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
namespace OCA\AdminResourceBookingDatabase\Mapper;

use OCP\IDBConnection;

/**
 * Class AbstractMapper
 *
 * @package OCA\AdminResourceBookingDatabase\Mapper
 */
abstract class AbstractMapper {

	/** @var IDBConnection */
	private $db;

	/**
	 * AbstractMapper constructor.
	 *
	 * @param IDBConnection $db
	 */
	public function __construct(IDBConnection $db) {
		$this->db = $db;
	}

	/**
	 * returns the DB table name which holds the mappings
	 * @return string
	 */
	abstract protected function getTableName():string;

	/**
	 * @return array
	 */
	public function getAll():array {
		$query = $this->db->getQueryBuilder();
		$query->select('*')
			->from($this->getTableName());
		$stmt = $query->execute();

		$rows = [];
		while($row = $stmt->fetch(\PDO::FETCH_ASSOC)) {
			$row['group_restrictions'] = \json_decode($row['group_restrictions']);
			$rows[] = $row;
		}

		return $rows;
	}

	/**
	 * @param int $id
	 * @return array
	 */
	public function get(int $id):array {
		$query = $this->db->getQueryBuilder();
		$query->select('*')
			->from($this->getTableName())
			->where($query->expr()->eq('id', $query->createNamedParameter($id)));
		$stmt = $query->execute();

		$row = $stmt->fetch(\PDO::FETCH_ASSOC);
		if(!$row) {
			return null;
		}

		$row['group_restrictions'] = \json_decode($row['group_restrictions']);
		return $row;
	}

	/**
	 * @param string $resourceId
	 * @return array
	 */
	public function getByResourceId(string $resourceId):array {
		$query = $this->db->getQueryBuilder();
		$query->select('*')
			->from($this->getTableName())
			->where($query->expr()->eq('resource_id', $query->createNamedParameter($resourceId)));
		$stmt = $query->execute();

		$row = $stmt->fetch(\PDO::FETCH_ASSOC);
		if(!$row) {
			return null;
		}

		$row['group_restrictions'] = \json_decode($row['group_restrictions']);
		return $row;
	}

	/**
	 * @param string $resource
	 * @param string $email
	 * @param string $displayname
	 * @param array $groupRestrictions
	 * @return int
	 */
	public function create(string $resource, string $email, string $displayname, array $groupRestrictions) {
		$query = $this->db->getQueryBuilder();
		$query->insert($this->getTableName())
			->values([
				'resource_id' => $query->createNamedParameter($resource),
				'email' => $query->createNamedParameter($email),
				'displayname' => $query->createNamedParameter($displayname),
				'group_restrictions' => $query->createNamedParameter(\json_encode($groupRestrictions)),
			])
			->execute();

		return $query->getLastInsertId();
	}

	/**
	 * @param int $id
	 * @param string $email
	 * @param string $displayname
	 * @param array $groupRestrictions
	 */
	public function edit(int $id, string $email, string $displayname, array $groupRestrictions) {
		$query = $this->db->getQueryBuilder();
		$query->update($this->getTableName())
			->set('email', $query->createNamedParameter($email))
			->set('displayname', $query->createNamedParameter($displayname))
			->set('group_restrictions', $query->createNamedParameter(\json_encode($groupRestrictions)))
			->where($query->expr()->eq('id', $query->createNamedParameter($id)))
			->execute();
	}

	/**
	 * @param int $id
	 */
	public function delete(int $id) {
		$query = $this->db->getQueryBuilder();
		$query->delete($this->getTableName())
			->where($query->expr()->eq('id', $query->createNamedParameter($id)))
			->execute();
	}
}
