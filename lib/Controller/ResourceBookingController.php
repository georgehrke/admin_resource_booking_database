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
namespace OCA\AdminResourceBookingDatabase\Controller;

use OC\AppFramework\Http;
use OCA\AdminResourceBookingDatabase\Mapper\ResourceMapper;
use OCA\AdminResourceBookingDatabase\Mapper\RoomMapper;
use \OCP\AppFramework\Controller;
use OCP\AppFramework\Http\JSONResponse;
use OCP\ILogger;
use \OCP\IRequest;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;
use Symfony\Component\EventDispatcher\GenericEvent;

/**
 * Class ResourceBookingController
 *
 * @package OCA\AdminResourceBookingDatabase\Controller
 */
class ResourceBookingController extends Controller {

	/** @var ILogger */
	private $logger;

	/** @var RoomMapper */
	private $roomMapper;

	/** @var ResourceMapper */
	private $resourceMapper;

	/** @var EventDispatcherInterface */
	private $eventDispatcher;

	/**
	 * ResourceBookingController constructor.
	 *
	 * @param string $appName
	 * @param IRequest $request
	 * @param ILogger $logger
	 * @param EventDispatcherInterface $eventDispatcher
	 * @param RoomMapper $roomMapper
	 * @param ResourceMapper $resourceMapper
	 */
	public function __construct(string $appName, IRequest $request,
								ILogger $logger,
								EventDispatcherInterface $eventDispatcher,
								RoomMapper $roomMapper,
								ResourceMapper $resourceMapper) {
		parent::__construct($appName, $request);
		$this->logger = $logger;
		$this->eventDispatcher = $eventDispatcher;
		$this->roomMapper = $roomMapper;
		$this->resourceMapper = $resourceMapper;
	}

	/**
	 * @return JSONResponse
	 */
	public function getAllRooms():JSONResponse {
		$rooms = $this->roomMapper->getAll();
		return new JSONResponse($rooms);
	}

	/**
	 * @return JSONResponse
	 */
	public function getAllResources():JSONResponse {
		$resources = $this->resourceMapper->getAll();
		return new JSONResponse($resources);
	}

	/**
	 * @param int $id
	 * @return JSONResponse
	 */
	public function getRoom(int $id):JSONResponse {
		$room = $this->roomMapper->get($id);
		if (!$room) {
			return new JSONResponse([], Http::STATUS_NOT_FOUND);
		}

		return new JSONResponse($room);
	}

	/**
	 * @param int $id
	 * @return JSONResponse
	 */
	public function getResource(int $id):JSONResponse {
		$resource = $this->resourceMapper->get($id);
		if (!$resource) {
			return new JSONResponse([], Http::STATUS_NOT_FOUND);
		}

		return new JSONResponse($resource);
	}

	/**
	 * @return JSONResponse
	 */
	public function createRoom():JSONResponse {
		list($room, $displayName, $email, $group_restrictions, $isValid) = $this->getRoomParams();
		if (!$isValid) {
			return new JSONResponse([], Http::STATUS_UNPROCESSABLE_ENTITY);
		}

		try {
			$id = $this->roomMapper->create($room, $email, $displayName, $group_restrictions);
			$this->eventDispatcher->dispatch('\OCP\Calendar\Room\ForceRefreshEvent');
		} catch(\Exception $ex) {
			$this->logger->logException($ex);
			return new JSONResponse([], Http::STATUS_UNPROCESSABLE_ENTITY);
		}

		return new JSONResponse(['id' => $id], Http::STATUS_CREATED);
	}

	/**
	 * @return JSONResponse
	 */
	public function createResource():JSONResponse {
		list($resource, $displayName, $email, $group_restrictions, $isValid) = $this->getResourceParams();
		if (!$isValid) {
			return new JSONResponse([], Http::STATUS_UNPROCESSABLE_ENTITY);
		}

		try {
			$id = $this->resourceMapper->create($resource, $email, $displayName, $group_restrictions);
			$this->eventDispatcher->dispatch('\OCP\Calendar\Resource\ForceRefreshEvent');
		} catch(\Exception $ex) { // what's the db exception class?
			$this->logger->logException($ex);
			return new JSONResponse([], Http::STATUS_UNPROCESSABLE_ENTITY);
		}

		return new JSONResponse(['id' => $id], Http::STATUS_CREATED);
	}

	/**
	 * @param int $id
	 * @return JSONResponse
	 */
	public function editRoom(int $id):JSONResponse {
		list($displayName, $email, $group_restrictions, $isValid) = $this->getRoomParams(false);
		if (!$isValid) {
			return new JSONResponse([], Http::STATUS_UNPROCESSABLE_ENTITY);
		}

		try {
			$this->roomMapper->edit($id, $email, $displayName, $group_restrictions);
			$this->eventDispatcher->dispatch('\OCP\Calendar\Room\ForceRefreshEvent');
		} catch(\Exception $ex) {
			$this->logger->logException($ex);
			return new JSONResponse([], Http::STATUS_UNPROCESSABLE_ENTITY);
		}

		return new JSONResponse();
	}

	/**
	 * @param int $id
	 * @return JSONResponse
	 */
	public function editResource(int $id):JSONResponse {
		list($displayName, $email, $group_restrictions, $isValid) = $this->getResourceParams(false);
		if (!$isValid) {
			return new JSONResponse([], Http::STATUS_UNPROCESSABLE_ENTITY);
		}

		try {
			$this->resourceMapper->edit($id, $email, $displayName, $group_restrictions);
			$this->eventDispatcher->dispatch('\OCP\Calendar\Resource\ForceRefreshEvent');
		} catch(\Exception $ex) {
			$this->logger->logException($ex);
			return new JSONResponse([], Http::STATUS_UNPROCESSABLE_ENTITY);
		}

		return new JSONResponse();
	}

	/**
	 * @param int $id
	 * @return JSONResponse
	 */
	public function deleteRoom(int $id):JSONResponse {
		$this->roomMapper->delete($id);
		$this->eventDispatcher->dispatch('\OCP\Calendar\Room\ForceRefreshEvent');
		return new JSONResponse();
	}

	/**
	 * @param int $id
	 * @return JSONResponse
	 */
	public function deleteResource(int $id):JSONResponse {
		$this->resourceMapper->delete($id);
		$this->eventDispatcher->dispatch('\OCP\Calendar\Resource\ForceRefreshEvent');
		return new JSONResponse();
	}

	/**
	 * reads and validates resource parameters
	 * @param bool $needsResourceParam
	 * @return array
	 */
	private function getResourceParams(bool $needsResourceParam=true):array {
		$resource = trim((string) $this->request->getParam('resource_id'));
		$displayname = trim((string) $this->request->getParam('displayname'));
		$email = trim((string) $this->request->getParam('email'));
		$groupRestrictions = trim((string) $this->request->getParam('group_restrictions'));
		$groupRestrictions = \json_decode($groupRestrictions);

		if ($needsResourceParam) {
			$validate = !\in_array('', [$resource, $displayname, $email, $groupRestrictions], false) && \is_array($groupRestrictions);
			return [$resource, $displayname, $email, $groupRestrictions, $validate];
		}

		$validate = !\in_array('', [$displayname, $email, $groupRestrictions], false) && \is_array($groupRestrictions);
		return [$displayname, $email, $groupRestrictions, $validate];
	}

	/**
	 * reads and validates room parameters
	 * @param $needsRoomParam
	 * @return array
	 */
	private function getRoomParams(bool $needsRoomParam=true):array {
		$room = trim((string) $this->request->getParam('resource_id'));
		$displayname = trim((string) $this->request->getParam('displayname'));
		$email = trim((string) $this->request->getParam('email'));
		$groupRestrictions = trim((string) $this->request->getParam('group_restrictions'));
		$groupRestrictions = \json_decode($groupRestrictions);

		if ($needsRoomParam) {
			$validate = !\in_array('', [$room, $displayname, $email, $groupRestrictions], false) && \is_array($groupRestrictions);
			return [$room, $displayname, $email, $groupRestrictions, $validate];
		}

		$validate = !\in_array('', [$displayname, $email, $groupRestrictions], false) && \is_array($groupRestrictions);
		return [$displayname, $email, $groupRestrictions, $validate];
	}
}
