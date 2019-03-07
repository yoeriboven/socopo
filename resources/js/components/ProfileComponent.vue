<template>
	<tr>
        <td><span class="avatar avatar-lg" :style="{ backgroundImage: 'url(' + profile.avatar + ')' }"></span></td>
        <td>{{ '@' + profile.username }}</td>
        <td><button @click="destroy" :dusk="`delete-profile-${profile.id}`">Delete</button></td>
    </tr>
</template>

<script>
    export default {
        props: ['profile'],

        computed: {
    		profiles: function() {
    			return this.$parent.profiles;
    		}
        },

        methods: {
        	destroy() {
        		axios.delete('api/profiles/' + this.profile.id)
                    .then(response => {
                    	this.profiles.splice(this.profiles.indexOf(this.profile), 1);
                        console.log('Response: ' + response);
                    })
                    .catch(function (error) {
					    console.log('Error: ' + error);
					});
        	}
        }
    }
</script>
