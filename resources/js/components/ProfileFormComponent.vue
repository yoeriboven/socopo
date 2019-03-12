<template>
	<div>
		<form @submit.prevent="addProfile">
			<input type="text" name="username" v-model="username" />
			<button type="submit" :dusk="`add-username`">Add</button>
		</form>

		<div v-if="this.loading">
			Loading
		</div>

		<div v-if="this.message" style="color:green;" class="success-alert">
			{{ this.message }}
		</div>

		<div v-if="this.errors" style="color:red;">
			{{ JSON.stringify(this.errors) }}
		</div>
	</div>
</template>

<script>
	export default {
		data() {
			return {
				username : '',
				message : '',
				errors : null,
				loading: false
			}
		},
		computed: {
    		profiles: function() {
    			return this.$parent.profiles;
    		},
    		validInput: function() {
        		return this.username.length < 3;
        	}
        },
        methods: {
        	addProfile() {
        		if (!this.validInput) {
        			// Errormessage: Username is too short
        		}

        		this.loading = true;

        		axios.post('api/profiles/', {
        			username : this.username
    			})
                .then(response => {
                	this.resetMessages();

                    this.message = response.data.message;
                    this.profiles.unshift(response.data.profile);
                })
                .catch(error => {
                	this.resetMessages();

                	// Only do this if status code is 422
				    this.errors = error.response.data.errors;
				})
				.then(() => {
					this.loading = false;
				});
        	},
        	resetMessages() {
        		this.message = '';
        		this.errors = null;
        	}
        }
    }
</script>
