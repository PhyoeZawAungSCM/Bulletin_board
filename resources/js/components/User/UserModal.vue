<template>
  <div>
    <!-- Modal -->
    <div
      class="modal fade"
      id="exampleModal"
      tabindex="-1"
      aria-labelledby="exampleModalLabel"
      aria-hidden="true"
    >
      <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
          <div class="modal-header">
            <h1 class="modal-title fs-5" id="exampleModalLabel">
              {{ mode == "delete" ? "Delete Confirm" : "User Detail" }}
            </h1>
            <button
              type="button"
              class="btn-close"
              data-bs-dismiss="modal"
              aria-label="Close"
            ></button>
          </div>
          <div class="modal-body">
            <div class="container">
              <div class="row justify-content-between">
                <div class="col-4">
                  <img
                    :src="`${$store.state.baseURL}/${user.profile}`"
                    style="width: 150px"
                  />
                </div>
                <div class="col-7">
                  <div class="row mb-3">
                    <div class="col-5">ID</div>
                    <div class="col-7">
                      {{ user.id }}
                    </div>
                  </div>
                  <div class="row mb-3">
                    <div class="col-5">name</div>
                    <div class="col-7">
                      {{ user.name }}
                    </div>
                  </div>
                  <div class="row mb-3">
                    <div class="col-5">
                       Email
                    </div>
                    <div class="col-7">
                      {{ user.email }}
                    </div>
                  </div>
                  <div class="row mb-3">
                    <div class="col-5">Phone</div>
                    <div class="col-7">
                      {{ user.phone }}
                    </div>
                  </div>
                  
                    <div class="row mb-3">
                      <div class="col-5">Date of Birth</div>
                      <div class="col-7">
                        {{changeDateFormat(user.dob) }}
                      </div>
                    </div>
                    <div class="row mb-3">
                      <div class="col-5">Address</div>
                      <div class="col-7">
                        {{ user.address }}
                      </div>
                    </div>
                    <div v-if="mode == 'detail'">
                    <div class="row mb-3">
                      <div class="col-5">Created Date</div>
                      <div class="col-7">
                        {{ changeDateFormat( user.created_at )}}
                      </div>
                    </div>
                    <div class="row mb-3">
                      <div class="col-5">Created User</div>
                      <div class="col-7">
                        {{ user.creatorName }}
                      </div>
                    </div>
                    <div class="row mb-3">
                      <div class="col-5">Updated Date</div>
                      <div class="col-7">
                        {{changeDateFormat(user.updated_at)  }}
                      </div>
                    </div>
                    <div class="row mb-3">
                      <div class="col-5">Updated User</div>
                      <div class="col-7">
                        {{ user.updatorName }}
                      </div>
                    </div>
                  </div>
                </div>
              </div>
            </div>
          </div>
          <div class="modal-footer">
            <div v-if="mode == 'delete'">
              <button
                type="button"
                class="btn btn-secondary"
                @click="hideModal"
              >
                Close
              </button>
              <button type="button" class="btn btn-danger" @click="doDelete()">
                Delete
              </button>
            </div>
            <div v-else>
              <button
                type="button"
                class="btn btn-secondary"
                @click="hideModal"
              >
                Close
              </button>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>
  
  <script>
import { Modal } from "bootstrap/dist/js/bootstrap.bundle";
import { http } from "../../services/http_service";
import { changeDateFormat } from '../../services/ChangeDateFormat';
export default {
  props: {
    mode: String,
  },
  data() {
    return {
      modal: null,
      user: {},
    };
  },
  methods: {
    showModal(id) {
      console.log("showModal");
      http()
        .get(`api/users/${id}`)
        .then((response) => {
          this.user = response.data[0];
        });
      this.modal.show();
      this.modal.show();
    },
    hideModal() {
      this.modal.hide();
    },
    doDelete() {
      console.log("delete");
      this.$store.dispatch('deleteUser',this.user.id);
      this.hideModal();
    },
    changeDateFormat(date){
      return changeDateFormat(date);
    }
  },
  mounted() {
    this.modal = new Modal("#exampleModal", {});
  },
};
</script>
  
  <style>
</style>