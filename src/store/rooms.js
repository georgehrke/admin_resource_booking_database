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

import api from './api';

const state = {
	rooms: []
};

const mutations = {
	addRoom: (state, room) => {
		state.rooms.push(room);
	},
	appendRooms: (state, rooms) => {
		state.rooms = state.rooms.concat(rooms);
	},
	editRoom: (state, roomObj) => {
		const index = state.rooms.findIndex((res) => res.id === roomObj.id);
		state.rooms.splice(index, 1, roomObj);
	},
	deleteRoom: (state, {id}) => {
		const index = state.rooms.findIndex((res) => res.id === id);
		state.rooms.splice(index, 1);
	}
};

const getters = {
	getRooms(state) {
		return state.rooms;
	}
};

const actions = {
	getRooms: (context) => {
		return api.get(OC.linkTo('admin_resource_booking_database', 'index.php') + '/api/rooms').then((response) => {
			context.commit('appendRooms', response.data);
		});
	},
	addRoom: (context, data) => {
		return api.post(OC.linkTo('admin_resource_booking_database', 'index.php') + '/api/rooms', data).then((response) => {
			data.id = response.data.id;
			context.commit('addRoom', data);
		});
	},
	editRoom: (context, {id, data}) => {
		return api.post(OC.linkTo('admin_resource_booking_database', 'index.php') + '/api/rooms/' + id, data).then(() => {
			context.commit('editRoom', data);
		});
	},
	deleteRoom: (context, {id}) => {
		return api.delete(OC.linkTo('admin_resource_booking_database', 'index.php') + '/api/rooms/' + id).then(() => {
			context.commit('deleteRoom', {id});
		});
	}
};

export default {state, mutations, getters, actions};
