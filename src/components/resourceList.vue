<template>
	<div id="resource_list">
		<div class="row">
			<div class="uuid">{{ t('admin_resource_booking_database', 'uuid') }}</div>
			<div class="displayname">{{ t('admin_resource_booking_database', 'Displayname') }}</div>
			<div class="email">{{ t('admin_resource_booking_database', 'E-Mail') }}</div>
			<div class="group-restrictions">{{ t('admin_resource_booking_database', 'Group Restrictions') }}</div>
		</div>
		<resource-row v-for="(resource, key) in resources" :resource="resource" :key="key"></resource-row>
		<form class="row" @submit.prevent="createResource">
			<div class="uuid"></div>
			<div class="displayname">
				<input id="newresourcename" type="text" required v-model="newResource.displayname"
					:placeholder="t('admin_resource_booking_database', 'Displayname')">
			</div>
			<div class="email">
				<input id="newresourceemail" type="text" required v-model="newResource.email"
					   :placeholder="t('admin_resource_booking_database', 'E-Mail')">
			</div>
			<div class="group_restrictions">
				<input id="newresourcegrouprestrictions" type="text" required v-model="newResource.group_restrictions"
					   :placeholder="t('admin_resource_booking_database', 'Group Restrictions')">
			</div>
			<div class="submit">
				<button type="submit">{{ t('admin_resource_booking_database', 'Save resource') }}</button>
			</div>
		</form>
	</div>
</template>

<script>
	import resourceRow from './resourceRow';

	export default {
		name: 'resource-list',
		props: ['resources'],
		components: {
			resourceRow
		},
		data: () => {
			return {
				newResource: {
					displayname: null,
					email: null,
					group_restrictions: null
				}
			}
		},
		methods: {
			createResource() {
				this.$store.dispatch('addResource', {
					resource_id: Math.random().toString(36).substr(2).toUpperCase(),
					displayname: this.newResource.displayname,
					email: this.newResource.email,
					group_restrictions: this.newResource.group_restrictions
				});
			}
		}
	}
</script>
