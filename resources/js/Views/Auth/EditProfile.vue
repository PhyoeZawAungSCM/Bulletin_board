<template>
  <div class="row justify-content-center">
    <div class="col-9">
      <div class="card mt-5 border-success">
        <div class="card-header bg-success">
          <h3 class="text-white">Profile Edit</h3>
        </div>
        <div class="card-body justify-content-center d-block m-auto" style="width: 600px">
          <ValidationObserver ref="form">
            <form @submit.prevent="Update">
              <div class="row mb-3 align-items-center">
                <div class="col-4 text-end">
                  <label for="name">Name</label>
                  <span class="text-danger">*</span>
                </div>
                <div class="col-8">
                  <ValidationProvider name="Name" rules="required" v-slot="{ errors }">
                    <input type="text" class="form-control" id="name" v-model="user.name" />
                    <span class="invalid-feedback">{{ errors[0] }}</span>
                  </ValidationProvider>
                </div>
              </div>
              <!-- Name input -->
              <div class="row mb-3 align-items-center">
                <div class="col-4 text-end">
                  <label for="email">E-mail Address</label>
                  <span class="text-danger">*</span>
                </div>
                <div class="col-8">
                  <ValidationProvider name="Email" rules="required|email|max:50" v-slot="{ errors }">
                    <input type="email" class="form-control" id="email" v-model="user.email" />
                    <span class="invalid-feedback">{{ errors[0] }}</span>
                  </ValidationProvider>
                </div>
              </div>
              <div class="row mb-3 align-items-center">
                <div class="col-4 text-end">
                  <label for="type">Type</label>
                  <span class="text-danger">*</span>
                </div>
                <div class="col-8">
                  <ValidationProvider name="Type" rules="required" v-slot="{ errors }">
                    <select class="form-select" aria-label="Default select example" id="type" v-model="user.type">
                      <option v-if="$store.state.auth.loginUser.type == '0'" value="0" selected>
                        Admin
                      </option>
                      <option value="1">User</option>
                    </select>
                    <span class="invalid-feedback">{{ errors[0] }}</span>
                  </ValidationProvider>
                </div>
              </div>
              <!-- type input -->
              <div class="row mb-3 align-items-center">
                <div class="col-4 text-end">
                  <label for="phone">Phone</label>
                </div>
                <div class="col-8">
                  <input type="text" class="form-control" id="phone" v-model="user.phone" />
                </div>
              </div>
              <!-- phone input -->
              <div class="row mb-3 align-items-center">
                <div class="col-4 text-end">
                  <label for="dob">Date of birth</label>
                </div>
                <div class="col-8">
                  <input type="date" class="form-control" id="dob" v-model="user.dob" />
                </div>
              </div>
              <!-- date of birth input -->
              <div class="row mb-3 align-items-center">
                <div class="col-4 text-end">
                  <label for="address">Address</label>
                  <span class="text-danger">*</span>
                </div>
                <div class="col-8">
                  <ValidationProvider name="Address" rules="required" v-slot="{ errors }">
                    <input type="text" class="form-control" id="address" v-model="user.address" />
                    <span class="invalid-feedback">{{ errors[0] }}</span>
                  </ValidationProvider>
                </div>
              </div>
              <!-- Address input -->
              <div class="row mb-3 align-items-center" v-if="!previewImage && user.profile != ''">
                <div class="col-4 text-end">
                  <label for="profile">Old Profile</label>
                </div>
                <div class="col-8">
                  <div class="mb-3">
                    <img :src="`${$store.state.baseURL}/${user.profile}`" alt="..." style="width: 100px" />
                  </div>
                </div>
              </div>
              <div class="row mb-3 align-items-center" v-if="previewImage">
                <div class="col-4 text-end">
                  <label for="profile">New Profile</label>
                </div>
                <div class="col-8">
                  <div class="mb-3">
                    <img :src="previewImage" alt="..." style="width: 100px" />
                  </div>
                </div>
              </div>

              <!-- Old profile -->
              <div class="row mb-3 align-items-center">
                <div class="col-4 text-end">
                  <label for="profile">New Profile</label>
                  <span class="text-danger">*</span>
                </div>
                <div class="col-8">
                  <div class="mb-3">
                    <ValidationProvider name="Profile" rules="image|size:2048" ref="provider" v-slot="{ errors }">
                      <input class="form-control" type="file" id="profile" @change="uploadData" ref="profile" />
                      <span class="invalid-feedback">{{ errors[0] }}</span>
                    </ValidationProvider>
                  </div>
                </div>
              </div>

              <!-- profile input -->
              <div class="row justify-content-end">
                <div class="col-8">
                  <button class="btn btn-primary" type="submit">edit</button>
                  <button class="btn btn-secondary" type="button" @click="clear">
                    Clear
                  </button>
                  <router-link to="/change-password" class="ms-3">Change passord</router-link>
                </div>
              </div>
            </form>
            <!-- end of form -->
          </ValidationObserver>
          <!-- ValidationObserver -->
        </div>
        <!-- end of card body -->
      </div>
    </div>
  </div>
</template>

<script>
export default {
  data() {
    return {
      user: {
        name: "",
        email: "",
        type: "",
        phone: "",
        dob: "",
        address: "",
        profile: null,
      },
      previewImage: null,
    };
  },
  methods: {
    Update() {
      this.$refs.form.handleSubmit(() => {
        this.$store.dispatch("updateProfile", this.user);
      });
    },
    async uploadData(e) {
      const { valid } = await this.$refs.provider.validate(e);
      if (valid) {
        let file = this.$refs.profile.files.item(0);
        this.user.profile = file;
        this.previewImage = URL.createObjectURL(file);
      }
    },
    clear() {
      this.user = {
        name: "",
        email: "",
        type: "",
        phone: "",
        dob: "",
        address: "",
        profile: "",
      };
      this.$refs.profile.value = null;
      this.previewImage = null;
      this.$refs.form.reset();
    },
  },
  props: {
    isConfirm: Boolean,
  },
  mounted() {
    this.user = this.$store.state.auth.loginUser;
  },
};
</script>
