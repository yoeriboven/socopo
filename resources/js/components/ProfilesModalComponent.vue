<template>
    <div>
        <!-- Modal -->
        <div class="modal fade" id="profilesModal" tabindex="-1" role="dialog" aria-labelledby="Profiles" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="exampleModalCenterTitle">Profiles</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <ul v-if="profiles.length">
                            <li v-for="profile in profiles">
                               {{ profile.username }}
                            </li>
                        </ul>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                        <button type="button" class="btn btn-primary">Save changes</button>
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
        created() {
            axios.get('api/profiles')
              .then(response => {
                // handle success
                this.profiles = response.data;
                // console.log(response);
              });
        }
    }
</script>
