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
	resources: []
};

const mutations = {
	addResource: (state, resource) => {
		state.resources.push(resource);
	},
	appendResources: (state, resources) => {
		state.resources = state.resources.concat(resources);
	},
	editResource: (state, resourceObj) => {
		const index = state.resources.findIndex((res) => res.id === resourceObj.id);
		state.resources.splice(index, 1, resourceObj);
	},
	deleteResource: (state, {id}) => {
		const index = state.resources.findIndex((res) => res.id === id);
		state.resources.splice(index, 1);
	}
};

const getters = {
	getResources(state) {
		return state.resources;
	}
};

const actions = {
	getResources: (context) => {
		return api.get(OC.linkTo('admin_resource_booking_database', 'index.php') + '/api/resources').then((response) => {
			context.commit('appendResources', response.data);
		});
	},
	addResource: (context, data) => {
		return api.post(OC.linkTo('admin_resource_booking_database', 'index.php') + '/api/resources', data).then((response) => {
			data.id = response.data.id;
			context.commit('addResource', data);
		});
	},
	editResource: (context, {id, data}) => {
		return api.post(OC.linkTo('admin_resource_booking_database', 'index.php') + '/api/resources/' + id, data).then(() => {
			context.commit('editResource', data);
		});
	},
	deleteResource: (context, {id}) => {
		return api.delete(OC.linkTo('admin_resource_booking_database', 'index.php') + '/api/resources/' + id).then(() => {
			context.commit('deleteResource', {id});
		});
	}
};

export default {state, mutations, getters, actions};
