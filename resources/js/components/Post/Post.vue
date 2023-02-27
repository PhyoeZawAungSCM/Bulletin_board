<template>
  <div class="row justify-content-center">
    <div class="col-9">
      <div class="card mt-5 border-success">
        <div class="card-header bg-success">
          <h3 class="text-white">Create post</h3>
        </div>
        <error-noti v-if="getHasError" :isActive="getHasError">{{
          getMessage
        }}</error-noti>
        <div
          class="card-body justify-content-center d-block m-auto"
          style="width: 600px"
        >
          <ValidationObserver v-slot="{ handleSubmit }">
            <form @submit.prevent="handleSubmit(Summitter)">
              <div class="row mb-3">
                <div class="col-3 text-end">
                  <label for="Title">Title</label>
                  <span class="text-danger">*</span>
                </div>
                <div class="col-8">
                  <ValidationProvider
                    rules="required|max:225"
                    v-slot="{ errors }"
                  >
                    <input
                      type="text"
                      class="form-control"
                      id="Title"
                      v-model="post.title"
                    />
                    <span class="invalid-feedback">{{ errors[0] }}</span>
                  </ValidationProvider>
                </div>
              </div>
              <!-- Title input -->
              <div class="row mb-3">
                <div class="col-3 text-end">
                  <label for="Description">Description</label>
                  <span class="text-danger">*</span>
                </div>
                <div class="col-8">
                  <ValidationProvider rules="required" v-slot="{ errors }">
                    <textarea
                      class="form-control"
                      id="Description"
                      rows="3"
                      v-model="post.description"
                    ></textarea>
                    <span class="invalid-feedback">{{ errors[0] }}</span>
                  </ValidationProvider>
                </div>
              </div>
              <!-- Description input -->
              <div class="row mb-3" v-if="mode == 'edit'">
                <div class="col-3 text-end">
                  <label for="Description">Status</label>
                </div>
                <div class="col-8">
                  <div class="form-check form-switch">
                    <input
                      v-model="post.status"
                      class="form-check-input"
                      type="checkbox"
                      role="switch"
                      id="flexSwitchCheckChecked"
                    />
                  </div>
                </div>
              </div>
              <!-- Status input -->
              <!-- <div class="form-check form-switch">
                            <input class="form-check-input" type="checkbox" role="switch" id="flexSwitchCheckDisabled" disabled>
                            <label class="form-check-label" for="flexSwitchCheckDisabled">Disabled switch checkbox input</label>
                        </div>
                       -->

              <div v-if="mode == 'create'" class="row justify-content-end">
                <div class="col-9" v-if="isConfirm">
                  <button class="btn btn-primary" type="submit">Confirm</button>
                  <button class="btn btn-secondary" @click="cancelCreatePost">
                    Cancel
                  </button>
                </div>
                <!-- confirm post -->
                <div class="col-9" v-else>
                  <button class="btn btn-primary" type="submit">Create</button>
                  <button class="btn btn-secondary" @click="clear">
                    Clear
                  </button>
                </div>
                <!-- create post -->
              </div>
              <!-- buttons create view-->
              <div v-if="mode == 'edit'" class="row justify-content-end">
                <div class="col-9" v-if="isConfirm">
                  <button class="btn btn-primary" type="submit">Confirm</button>
                  <button class="btn btn-secondary" @click="cancelEditPost">
                    Cancel
                  </button>
                </div>
                <!-- confirm post -->
                <div class="col-9" v-else>
                  <button class="btn btn-primary" type="submit">Edit</button>
                  <button class="btn btn-secondary" @click="clear">
                    Clear
                  </button>
                </div>
                <!-- create post -->
              </div>
              <!-- buttons edit view-->
            </form>
          </ValidationObserver>
        </div>
      </div>
    </div>
  </div>
</template>

<script>
import { disableFormInputs } from "../../services/DisableInput";
import { mapGetters } from "vuex";
import ErrorNoti from "../Error/ErrorNoti.vue";
export default {
  components: {
    ErrorNoti,
  },
  props: {
    isConfirm: Boolean,
    mode: String,
  },
  data() {
    return {
      post: {
        title: "",
        description: "",
        status: "",
      },
    };
  },
  methods: {
    Summitter() {
      console.log("Hello i am summitter");
      if (this.mode == "edit") {
        if (this.isConfirm) {
          this.editPostConfirm();
        } else {
          console.log("Edit Post function call");
          this.editPost();
        }
      }
      if (this.mode == "create") {
        if (this.isConfirm) {
          this.createPostConfirm();
        } else {
          this.createPost();
        }
      }
    },
    createPost() {
      console.log("createPost");
      this.$store.commit("SET_TEMP_POST", this.post);
      this.$router.push("/create-post-confirm");
    },
    createPostConfirm() {
      console.log("confirm post");
      this.$store.dispatch("createPost");
    },
    editPost() {
      console.log("edit post");
      this.$store.commit("SET_TEMP_POST", this.post);
      this.$router.push("/edit-post-confirm");
    },
    editPostConfirm() {
      console.log("edit post confirm");
      this.$store.dispatch("editPost");
      this.$store.dispatch("clearPostData");
      this.$router.push("/posts-list");
    },
    clear() {
      this.post = {
        title: "",
        description: "",
        status: "",
      };
    },
    cancelEditPost() {
      this.$router.push("/edit-post");
    },
    cancelCreatePost() {
      this.$router.push("/create-post");
    },
  },
  mounted() {
    if (this.isConfirm) {
      disableFormInputs();
    }
    this.post = this.$store.state.post.tempPost;
  },
  computed: {
    ...mapGetters(["getHasError", "getMessage"]),
  },

};
</script>

<style>
</style>