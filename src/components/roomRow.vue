<template>
	<div class="row">
		<div class="uid">{{ room.resource_id }}</div>
		<div class="displayname">{{ room.displayname }}</div>
		<div class="email">{{ room.email }}</div>
		<div class="group-restrictions">{{ room.group_restrictions }}</div>
		<div class="delete"><button @click.prevent="deleteRoom">Delete Room</button></div>
	</div>
</template>

<script>
	export default {
		name: "room-row",
		props: ['room'],
		methods: {
			deleteRoom() {
				let id = this.room.id;
				this.$store.dispatch('deleteRoom', {id});
			}
		}
	}
</script>
