<!--
  - @copyright 2018, Georg Ehrke <oc.list@georgehrke.com>
  -
  - @author Georg Ehrke <oc.list@georgehrke.com>
  -
  - @license GNU AGPL version 3 or any later version
  -
  - This program is free software: you can redistribute it and/or modify
  - it under the terms of the GNU Affero General Public License as
  - published by the Free Software Foundation, either version 3 of the
  - License, or (at your option) any later version.
  -
  - This program is distributed in the hope that it will be useful,
  - but WITHOUT ANY WARRANTY; without even the implied warranty of
  - MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
  - GNU Affero General Public License for more details.
  -
  - You should have received a copy of the GNU Affero General Public License
  - along with this program. If not, see <http://www.gnu.org/licenses/>.
  -
  -->

<template>
	<div id="admin_resource_booking_database">
		<form id="Resources" class="section">
			<h2>{{ t('admin_resource_booking_database', 'Resources') }}</h2>
			<resources></resources>
		</form>

		<form id="Rooms" class="section">
			<h2>{{ t('admin_resource_booking_database', 'Rooms') }}</h2>
			<rooms></rooms>
		</form>
	</div>
</template>

<script>
import resources from './components/resources';
import rooms from './components/rooms';

export default {
	name: 'App',
	components: {
		resources, rooms
	}
}
</script>
