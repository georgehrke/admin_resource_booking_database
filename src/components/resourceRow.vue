<template>
	<div class="row">
		<div class="uid">{{ resource.resource_id }}</div>
		<div class="displayname">{{ resource.displayname }}</div>
		<div class="email">{{ resource.email }}</div>
		<div class="group-restrictions">{{ resource.group_restrictions }}</div>
		<div class="delete"><button @click.prevent="deleteResource">Delete Resource</button></div>
	</div>
</template>

<script>
	export default {
		name: "resource-row",
		props: ['resource'],
		methods: {
			deleteResource() {
				let id = this.resource.id;
				this.$store.dispatch('deleteResource', {id});
			}
		}
	}
</script>
