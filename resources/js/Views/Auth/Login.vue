<template>
  <div class="row justify-content-center">
    <div class="col-9">
      <div class="card mt-5 border-success">
        <div class="card-header bg-success">
          <h3 class="text-white">Login</h3>
        </div>
        <error-noti v-if="getHasError" :isActive="getHasError">{{
          getMessage
        }}</error-noti>
        <success-noti v-if="getHasMessage" :isActive="getHasMessage">{{
          getMessage
        }}</success-noti>
        <div class="card-body justify-content-center d-block m-auto" style="width: 700px">
          <ValidationObserver v-slot="{ handleSubmit }">
            <form @submit.prevent="handleSubmit(Login)">
              <div class="row mb-3">
                <div class="col-4 text-end">
                  <label for="email">Email</label>
                  <span class="text-danger">*</span>
                </div>
                <div class="col-8">
                  <ValidationProvider name="Email" rules="required|max:50|email" v-slot="{ errors }">
                    <input type="email" class="form-control" id="email" v-model="user.email" />
                    <span class="invalid-feedback">{{ errors[0] }}</span>
                  </ValidationProvider>
                </div>
              </div>
              <!-- Email Field -->
              <div class="row mb-3">
                <div class="col-4 text-end">
                  <label for="password">Password</label>
                  <span class="text-danger">*</span>
                </div>
                <div class="col-8">
                  <ValidationProvider name="Password" rules="required|min:6|max:20" v-slot="{ errors }">
                    <input type="password" class="form-control" id="password" v-model="user.password" />
                    <span class="invalid-feedback">{{ errors[0] }}</span>
                  </ValidationProvider>
                </div>
              </div>
              <!-- Password Field -->
              <div class="row mb-3 justify-content-end">
                <div class="col-8">
                  <div class="row">
                    <div class="col-6">
                      <div class="form-check">
                        <input class="form-check-input" type="checkbox" id="flexCheckChecked"
                          v-model="user.remember_me" />
                        <label class="form-check-label" for="flexCheckChecked">
                          Remember me
                        </label>
                      </div>
                    </div>
                    <div class="col-6 d-flex justify-content-end">
                      <router-link to="/forgotten-password">Forgotten Password?</router-link>
                    </div>
                  </div>
                </div>
              </div>
              <!-- Remember Me -->
              <div class="row justify-content-end mb-3">
                <div class="col-8">
                  <div class="container">
                    <div class="row">
                      <button class="btn btn-primary" type="submit">
                        Login
                      </button>
                    </div>
                  </div>
                </div>
              </div>
              <!-- Login Button -->
            </form>
          </ValidationObserver>
          <!-- end of form -->
        </div>
      </div>
    </div>
  </div>
</template>
<script>
import ErrorNoti from "../../components/Error/ErrorNoti.vue";
import SuccessNoti from "../../components/Error/SuccessNoti.vue";
import { mapGetters } from "vuex";
export default {
  components: {
    ErrorNoti,
    SuccessNoti,
  },
  data() {
    return {
      user: {
        email: "",
        password: "",
        remember_me: false,
      },
    };
  },
  methods: {
    Login() {
      // Here to make login function to login user
      this.$store.dispatch("Login", this.user);
    },
  },
  computed: {
    ...mapGetters(["getHasError", "getMessage", "getHasMessage"]),
  },
};
</script>

<style>
a {
  text-decoration: none;
}
</style>