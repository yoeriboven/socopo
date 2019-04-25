<template>
	<tr>
        <td><span class="avatar avatar-lg" :style="{ backgroundImage: 'url(' + profile.avatar + ')' }"></span></td>
        <td>{{ '@' + profile.username }}</td>
        <td><a href="#" class="icon" @click="destroy" :dusk="`delete-profile-${profile.id}`"><i class="fe fe-trash"></i></a></td>
    </tr>
</template>

<script>
    import ProfileStore from '../ProfileStore.js';

    export default {
        props: ['profile'],

        data() {
            return {
                profiles: ProfileStore.data
            }
        },

        methods: {
        	destroy() {
        		axios.delete('api/profiles/' + this.profile.id)
                    .then(response => {
                        this.$parent.resetMessages();

                    	this.profiles.data.splice(this.profiles.data.indexOf(this.profile), 1);

                        this.profiles.success = 'Profile removed';
                    })
                    .catch(function (error) {
                        this.profiles.error = 'Removing profile failed';
					});
        	}
        }
    }
</script>
