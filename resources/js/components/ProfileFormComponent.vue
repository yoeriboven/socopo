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

		<div v-if="this.error" style="color:red;">
			{{ this.error }}
		</div>
	</div>
</template>

<script>
	export default {
		data() {
			return {
				username : '',
				message : '',
				error : '',
				loading: false
			}
		},
		computed: {
    		profiles: function() {
    			return this.$parent.profiles;
    		},
    		validInput: function() {
                return this.username.length >= 3 && this.username.length <= 30;
        	}
        },
        methods: {
        	addProfile() {
        		if (!this.validInput) {
                    this.error = 'Username not found on Instagram';
                    return;
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

                    if (!error.response) {
                        this.errorMessage = 'Adding profile failed';
                    }

                    // 422 means there are validation errors
                    if (error.response.status == 422 && error.response.data.errors.username) {
                        this.error = error.response.data.errors.username[0];
                    } else if (error.response.data) {
                        this.error = error.response.data[0];
                    }
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
