<template>
	<div>
		<resource-list :resources="resources"></resource-list>
	</div>
</template>

<script>
	import resourceList from './resourceList';

	export default {
		name: "resources",
		components: {resourceList},
		mounted() {
			this.$store.dispatch('getResources');
		},
		computed: {
			resources() {
				return this.$store.getters.getResources;
			}
		}
	}
</script>
