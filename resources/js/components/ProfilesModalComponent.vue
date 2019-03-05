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
                        <table>
                            <tr v-for="profile in profiles">
                                <td><span class="avatar avatar-lg" v-bind:style="{ backgroundImage: 'url(' + profile.avatar + ')' }"></span></td>
                                <td>@{{ profile.username }}</td>
                            </tr>
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
