<template>
  <div class="row justify-content-center">
    <div class="col-9">
      <div class="card mt-5 border-success">
        <div class="card-header bg-success">
          <h3 class="text-white">Reset Password</h3>
        </div>
        <div
          class="card-body justify-content-center d-block m-auto"
          style="width: 700px"
          v-if="checking"
        >
          checking
        </div>
        <div
          class="card-body justify-content-center d-block m-auto"
          style="width: 700px"
          v-if="!checking && !isValidToken"
        >
          The link inactive ,
          <router-link to="/forgotten-password"> request new link ? </router-link>
        </div>
        <div
          class="card-body justify-content-center d-block m-auto"
          style="width: 700px"
          v-if="isValidToken"
        >
          <ValidationObserver v-slot="{ handleSubmit }">
            <form @submit.prevent="handleSubmit(resetPassword)">
              <div class="row mb-3">
                <div class="col-4 text-end">
                  <label for="password">Password</label>
                  <span class="text-danger">*</span>
                </div>
                <div class="col-8">
                  <ValidationProvider
                    v-slot="{ errors }"
                    vid="password"
                    rules="required|min:6|max:20"
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
                  <label for="passwordConfirm">Password Confirmation</label>
                  <span class="text-danger">*</span>
                </div>
                <div class="col-8">
                  <ValidationProvider
                    v-slot="{ errors }"
                    rules="required|min:6|max:20|confirm:password"
                  >
                    <input
                      type="password"
                      class="form-control"
                      id="passwordConfirm"
                      v-model="passwordConfirm"
                    />
                    <span class="invalid-feedback">{{ errors[0] }}</span>
                  </ValidationProvider>
                </div>
              </div>

              <div class="row justify-content-end">
                <div class="col-8">
                  <button
                    class="btn btn-primary"
                    type="submit"
                  >
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
import Axios from "axios";
import { http } from "../../../services/http_service";
export default {
  props:{
    token:String,
  },
  data() {
    return {
      checking: true,
      isValidToken: false,
      password: "",
      passwordConfirm: "",
    };
  },
  methods: {
    resetPassword() {
      http()
        .post("api/check-token", { token: this.token })
        .then((response) => {
          http()
            .post("api/reset-password", {
              token: this.token,
              password: this.password,
              password_confirmation: this.passwordConfirm,
            })
            .then((response) => {
              this.$store.state.noti.hasMessage = true;
              this.$store.state.noti.message = "Password reset success please login"
              this.$router.push('/login');

              console.log(response);
            })
            .catch((error) => {
              console.log(error);
            });
        })
        .catch( error => {
           this.checking = false;
        })
    },
  },
  mounted() {

    http()
      .post("api/check-token", { token: this.token })
      .then((Response) => {
        this.checking = false;
        this.isValidToken = true;
        console.log(Response.data);
      })
      .catch((error) => {
        this.checking = false;
        console.log();
      });
  },
};
</script>

<style>
</style>