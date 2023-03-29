<template>
  <div class="row justify-content-center">
    <div class="col-9">
      <div class="card mt-5 border-success">
        <div class="card-header bg-success">
          <h3 class="text-white">Forgot Password?</h3>
        </div>
        <error-noti v-if="getHasError" :isActive="getHasError">{{ getMessage }}</error-noti>
        <success-noti v-if="getHasMessage" :isActive="getHasMessage">{{ getMessage }}</success-noti>
        <ValidationObserver v-slot="{ handleSubmit }">
          <form @submit.prevent="handleSubmit(sendLink)">
            <div class="card-body justify-content-center d-block m-auto" style="width: 600px">
              <div class="row mb-3">
                <div class="col-3 text-end">
                  <label for="email">Email</label>
                  <span class="text-danger">*</span>
                </div>
                <div class="col-9">
                  <ValidationProvider name="Email" v-slot="{ errors }" rules="required|email">
                    <input type="email" class="form-control" id="email" v-model="email" />
                    <span class="invalid-feedback">{{ errors[0] }}</span>
                  </ValidationProvider>
                </div>
              </div>
              <div class="row justify-content-end">
                <div class="col-9">
                  <button class="btn btn-primary" type="submit" @submit="sendLink" :disabled='sending'>
                    {{ sending ? "Mail Sending" : "Reset Password" }}
                  </button>
                </div>
              </div>
            </div>
          </form>
        </ValidationObserver>
      </div>
    </div>
  </div>
</template>

<script>
import Axios from 'axios';
import { mapGetters } from "vuex";
import ErrorNoti from "../../../components/Error/ErrorNoti.vue";
import SuccessNoti from "../../../components/Error/SuccessNoti.vue";
export default {
  components: {
    ErrorNoti,
    SuccessNoti
  },
  data() {
    return {
      email: "",
      sending: false
    };
  },
  methods: {
    sendLink() {
      this.sending = true;
      Axios.post('api/forgot-password', { email: this.email })
        .then(response => {
          this.sending = false
          this.$store.state.noti.message = response.data.message;
          this.$store.state.noti.hasMessage = true;
          this.$router.push('/login');
        })
        .catch(error => {
          this.sending = false
          this.$store.state.noti.message = error.response.data.message;
          this.$store.state.noti.hasError = true;
          console.log(error.response.data.message);
        })
    },
  },
  computed: {
    ...mapGetters(["getHasError", "getMessage", 'getHasMessage']),
  },
};
</script>

