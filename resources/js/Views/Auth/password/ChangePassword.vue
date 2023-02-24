<template>
  <div class="row justify-content-center">
    <div class="col-9">
      <div class="card mt-5 border-success">
        <div class="card-header bg-success">
          <h3 class="text-white">Change Password</h3>
        </div>
        <error-noti :isActive="hasError">{{ passwordError }}</error-noti>
        <div
          class="card-body justify-content-center d-block m-auto"
          style="width: 700px"
        >
          <ValidationObserver v-slot="{ handleSubmit }">
            <form @submit.prevent="handleSubmit(changePassword())">
              <div class="row mb-3">
                <div class="col-4 text-end">
                  <label for="password">Current Password</label>
                  <span class="text-danger">*</span>
                </div>
                <div class="col-8">
                  <ValidationProvider
                    rules="required|min:6|max:20"
                    v-slot="{ errors }"
                  >
                    <input
                      type="password"
                      class="form-control"
                      id="password"
                      v-model="password"
                    />
                    <span class="invalid-feedback">{{ errors[0] }}</span>
                  </ValidationProvider>
                </div>
              </div>
              <div class="row mb-3">
                <div class="col-4 text-end">
                  <label for="newPassword">New Password</label>
                  <span class="text-danger">*</span>
                </div>
                <div class="col-8">
                  <ValidationProvider
                    rules="required|min:6|max:20"
                    v-slot="{ errors }"
                    vid="newPassword"
                  >
                    <input
                      type="password"
                      class="form-control"
                      id="newPassword"
                      v-model="newPassword"
                    />
                    <span class="invalid-feedback">{{ errors[0] }}</span>
                  </ValidationProvider>
                </div>
              </div>
              <div class="row mb-3">
                <div class="col-4 text-end">
                  <label for="newPasswordConfirm">New Confirm Password</label>
                  <span class="text-danger">*</span>
                </div>
                <div class="col-8">
                  <ValidationProvider
                    rules="required|min:6|max:20|confirm:newPassword"
                    v-slot="{ errors }"
                  >
                    <input
                      type="password"
                      class="form-control"
                      id="newPasswordConfirm"
                      v-model="newConfirmPassword"
                    />
                    <span class="invalid-feedback">{{ errors[0] }}</span>
                  </ValidationProvider>
                </div>
              </div>

              <div class="row justify-content-end">
                <div class="col-8">
                  <button class="btn btn-primary" type="submit">
                    Update Password
                  </button>
                </div>
              </div>
            </form>
          </ValidationObserver>
        </div>
      </div>
    </div>
  </div>
</template>

<script>
import { http } from "../../../services/http_service";
import errorNoti from "../../../components/Error/ErrorNoti.vue";
export default {
  components: {
    errorNoti,
  },
  data() {
    return {
      password: "",
      newPassword: "",
      newConfirmPassword: "",
      passwordError: "",
      hasError: false,
    };
  },
  methods: {
    changePassword() {
      console.log("change password");
      http()
        .post("api/change-password", {
          password: this.password,
          newPassword: this.newPassword,
          newPassword_confirmation: this.newConfirmPassword,
        })
        .then((response) => {
          this.$router.push("/posts-list");
          this.$store.state.noti.hasMessage = true;
          this.$store.state.noti.message = response.data.message;
          console.log(response);
        })
        .catch((error) => {
          this.hasError = true;
          this.passwordError = error.response.data.error;
        });
    },
  },
};
</script>

<style>
</style>