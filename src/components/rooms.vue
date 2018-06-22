<template>
	<div>
		<room-list :rooms="rooms"></room-list>
	</div>
</template>

<script>
	import roomList from './roomList';

	export default {
		name: "rooms",
		components: {roomList},
		mounted() {
			this.$store.dispatch('getRooms');
		},
		computed: {
			rooms() {
				return this.$store.getters.getRooms;
			}
		}
	}
</script>
