<template>
	<div>
		<form @submit.prevent="addProfile">
            <div class="form-group">
                <div class="input-group">
                    <input type="text" v-model="username" class="form-control" placeholder="@...">
                    <span class="input-group-append">
                        <button class="btn btn-primary" v-bind:class="{ 'btn-loading': loading }" type="submit" :dusk="`add-username`">Add profile</button>
                    </span>
                </div>
            </div>
		</form>
	</div>
</template>

<script>
    import ProfileStore from '../ProfileStore.js';

	export default {
		data() {
			return {
				profiles : ProfileStore.data,
                username : '',
                loading: false
			}
		},
		computed: {
    		validInput: function() {
                return this.username.length >= 3 && this.username.length <= 30;
        	}
        },
        methods: {
        	addProfile() {
        		if (!this.validInput) {
                    this.profiles.error = 'Username not found on Instagram';
                    return;
        		}

        		this.loading = true;

        		axios.post('api/profiles/', {
        			username : this.username
    			})
                .then(response => {
                	this.$parent.resetMessages();

                    this.profiles.success = response.data.message;
                    this.username = '';

                    if (response.data.profile) {
                        this.profiles.data.unshift(response.data.profile);
                    }
                })
                .catch(error => {
                	this.$parent.resetMessages();

                    if (!error.response) {
                        this.profiles.error = 'Adding profile failed';
                    }

                    // 422 means there are validation errors
                    if (error.response.status == 422 && error.response.data.errors.username) {
                        console.log(error.response.data.errors.username[0]);
                        this.profiles.error = error.response.data.errors.username[0];
                    } else if (error.response.data) {
                        this.profiles.error = error.response.data.message;
                    }
				})
				.then(() => {
					this.loading = false;
				});
        	}
        }
    }
</script>
