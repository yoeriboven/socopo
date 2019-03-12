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
                        <profile-form-component></profile-form-component>
                        <table>
                            <profile-component v-for="profile in profiles" :profile="profile" :key="profile.id"></profile-component>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
    export default {
        data() {
            return {
                profiles: []
            }
        },
        methods: {
            getProfiles() {
                axios.get('api/profiles')
                    .then(response => {
                        this.profiles = response.data;
                    });
            }
        },
        created() {
            this.getProfiles();
        }
    }
</script>
