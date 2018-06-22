<template>
	<div id="room_list">
		<div class="row">
			<div class="uuid">{{ t('admin_resource_booking_database', 'uuid') }}</div>
			<div class="displayname">{{ t('admin_resource_booking_database', 'Displayname') }}</div>
			<div class="email">{{ t('admin_resource_booking_database', 'E-Mail') }}</div>
			<div class="group-restrictions">{{ t('admin_resource_booking_database', 'Group Restrictions') }}</div>
		</div>
		<room-row v-for="(room, key) in rooms" :room="room" :key="key"></room-row>
		<form class="row" @submit.prevent="createRoom">
			<div class="uuid"></div>
			<div class="displayname">
				<input id="newroomname" type="text" required v-model="newRoom.displayname"
					   :placeholder="t('admin_resource_booking_database', 'Displayname')">
			</div>
			<div class="email">
				<input id="newroomemail" type="text" required v-model="newRoom.email"
					   :placeholder="t('admin_resource_booking_database', 'E-Mail')">
			</div>
			<div class="group_restrictions">
				<input id="newroomgrouprestrictions" type="text" required v-model="newRoom.group_restrictions"
					   :placeholder="t('admin_resource_booking_database', 'Group Restrictions')">
			</div>
			<div class="submit">
				<button type="submit">{{ t('admin_resource_booking_database', 'Save room') }}</button>
			</div>
		</form>
	</div>
</template>

<script>
	import roomRow from './roomRow';

	export default {
		name: 'room-list',
		props: ['rooms'],
		components: {
			roomRow
		},
		data: () => {
			return {
				newRoom: {
					displayname: null,
					email: null,
					group_restrictions: null
				}
			}
		},
		methods: {
			createRoom() {
				this.$store.dispatch('addRoom', {
					resource_id: Math.random().toString(36).substr(2).toUpperCase(),
					displayname: this.newRoom.displayname,
					email: this.newRoom.email,
					group_restrictions: this.newRoom.group_restrictions
				});
			}
		}
	}
</script>
