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
return [
    'routes' => [
    	['name' => 'resource_booking#getAllRooms', 'url' => '/api/rooms', 'verb' => 'GET'],
    	['name' => 'resource_booking#getRoom', 'url' => '/api/rooms/{id}', 'verb' => 'GET'],
		['name' => 'resource_booking#getAllResources', 'url' => '/api/resources', 'verb' => 'GET'],
		['name' => 'resource_booking#getResource', 'url' => '/api/resources/{id}', 'verb' => 'GET'],

    	['name' => 'resource_booking#createRoom', 'url' => '/api/rooms', 'verb' => 'POST'],
		['name' => 'resource_booking#createResource', 'url' => '/api/resources', 'verb' => 'POST'],

		['name' => 'resource_booking#editRoom', 'url' => '/api/rooms/{id}', 'verb' => 'POST'],
		['name' => 'resource_booking#editResource', 'url' => '/api/resources/{id}', 'verb' => 'POST'],

		['name' => 'resource_booking#deleteRoom', 'url' => '/api/rooms/{id}', 'verb' => 'DELETE'],
		['name' => 'resource_booking#deleteResource', 'url' => '/api/resources/{id}', 'verb' => 'DELETE'],
    ]
];
