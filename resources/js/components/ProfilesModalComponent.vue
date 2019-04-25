<template>
    <div>
        <!-- Modal -->
        <div class="modal fade" id="profilesModal" tabindex="-1" role="dialog" aria-labelledby="Profiles" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Profiles</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <!-- <span aria-hidden="true">&times;</span> -->
                        </button>
                    </div>
                    <div class="modal-body">

                        <div v-if="profiles.success" class="alert alert-icon alert-success full-width" role="alert">
                            <i class="fe fe-check mr-2" aria-hidden="true"></i> {{ profiles.success }}
                        </div>

                        <div v-if="profiles.error" class="alert alert-icon alert-danger full-width" role="alert">
                            <i class="fe fe-alert-triangle mr-2" aria-hidden="true"></i> {{ profiles.error }}
                        </div>

                        <p>You can add and remove profiles to your account. We will send you a Slack message whenever any of these profiles uploads a new post.</p>

                        <profile-form-component></profile-form-component>

                        <table v-if="profiles.data.length">
                            <profile-component v-for="profile in profiles.data" :profile="profile" :key="profile.id"></profile-component>
                        </table>

                        <div v-if="this.showEmptyDataView">
                            No profiles yet. Go add some!
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
    import ProfileStore from '../ProfileStore.js';

    export default {
        data() {
            return {
                profiles: ProfileStore.data
            }
        },
        computed: {
            showEmptyDataView() {
                // Dont show 'empty data view' if there are no profiles because of a server error
                return this.profiles.data.length == 0 && this.profiles.error == '';
            }
        },
        methods: {
            getProfiles() {
                axios.get('api/profiles')
                    .then(response => {
                        this.resetMessages();

                        this.profiles.data = response.data;
                    }).catch(error => {
                        this.resetMessages();

                        this.profiles.error = 'Fetching profiles failed';
                    });
            },
            resetMessages() {
                this.profiles.success = '';
                this.profiles.error = '';
            }
        },
        created() {
            this.getProfiles();
        }
    }
</script>
