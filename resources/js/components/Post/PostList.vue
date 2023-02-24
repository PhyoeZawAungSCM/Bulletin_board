<template>
  <div class="border border-secondary rounded-top my-4">
    <div class="d-block bg-success py-2 align-items-center">
      <h4 class="text-white ps-3">Post list</h4>
    </div>
    <success-noti v-if="getHasMessage" :isActive="getHasMessage">{{ getMessage }}</success-noti>
    <form @submit.prevent="searchPost">
      <div class="d-flex justify-content-end py-2 align-items-center">
        <label for="keyword" class="me-3 fw-semibold">Search:</label>
        <input
          type="text"
          v-model="searchInput"
          name="keyword"
          class="form-control me-3"
          style="width: 240px"
          @keydown.enter="onPressEnter(searchPost)"
        />
        <button class="btn btn-success me-3" style="width: 120px" type="submit">
          Search
        </button>
        <button
          class="btn btn-success me-3"
          style="width: 120px"
          @click="createPost"
        >
          Create
        </button>
        <button
          class="btn btn-success me-3"
          style="width: 120px"
          @click="uploadPost"
        >
          Upload
        </button>
        <button
          class="btn btn-success me-3"
          style="width: 120px"
          @click="downloadPost"
        >
          Download
        </button>
      </div>
    </form>
    <div class="mx-3">
      <table
        class="table table-striped align-middle text-center mt-3 table-hover"
      >
        <thead style="background-color: #78b3a4" class="text-white">
          <tr>
            <th>Post Title</th>
            <th>Post Description</th>
            <th>Posted User</th>
            <th>Posted Date</th>
            <th v-if="$store.state.auth.isLogin">Operations</th>
          </tr>
        </thead>
        <tbody class="fw-semibold text-black-50">
          <tr v-if="getPosts.length == 0">
            <td colspan="5">No data available in table</td>
          </tr>
          <tr v-for="post in getPosts" :key="post.id">
            <td @click="openModal(post.id, 'detail')">{{ post.title }}</td>
            <td @click="openModal(post.id, 'detail')">
              {{ post.description }}
            </td>
            <td @click="openModal(post.id, 'detail')">{{ post.name }}</td>
            <td @click="openModal(post.id, 'detail')">
              {{ changeFormat(post.created_at) }}
            </td>
            <td v-if="$store.state.auth.isLogin">
              <button class="btn btn-primary" @click="editPost(post.id)">
                Edit
              </button>
              <button
                class="btn btn-danger"
                @click="openModal(post.id, 'delete')"
              >
                Delete
              </button>
            </td>
          </tr>
        </tbody>
      </table>
      <nav aria-label="Page navigation example">
        <ul class="pagination justify-content-end">
          <li class="page-item">
            <button class="page-link" @click="previous">Previous</button>
          </li>
          <li
            class="page-item"
            :class="page == p ? 'active' : ''"
            v-for="p in $store.state.post.lastPage"
            :key="p"
          >
            <button class="page-link" @click="loadNewPage(p)">{{ p }}</button>
          </li>
          <li class="page-item">
            <button class="page-link" @click="next">Next</button>
          </li>
        </ul>
      </nav>
    </div>
    <modal ref="postmodal" :mode="mode" />
  </div>
</template>

<script>
import Modal from "./PostModal.vue";
import { mapGetters } from "vuex";
import { changeDateFormat } from "../../services/ChangeDateFormat";
import SuccessNoti from "../../components/Error/SuccessNoti.vue";
export default {
  components: {
    Modal,
    SuccessNoti,
  },
  data() {
    return {
      mode: "detail",
      searchInput: "",
      page: 1,
    };
  },
  methods: {
    openModal(id, mode) {
      this.mode = mode;
      console.log("openModal");
      this.$refs.postmodal.showModal(id);
    },
    createPost() {
      this.$router.push("/create-post");
    },
    editPost(id) {
      this.$store.dispatch("getPostData", id);
    },
    searchPost() {
      this.page = 1;
      this.$store.dispatch("getPosts", { search: this.searchInput, page: "" });
    },
    loadNewPage(page) {
      this.page = page;
      this.$store.dispatch("getPosts", {
        search: this.searchInput,
        page: this.page,
      });
    },
    previous() {
      console.log("Previous");
      if (this.page > 1) {
        this.page -= 1;
        this.loadNewPage(this.page);
      }
    },
    next() {
      console.log("next");
      if (this.page < this.$store.state.post.lastPage) {
        this.page = this.page + 1;
        this.loadNewPage(this.page);
      }
    },
    uploadPost() {
      this.$router.push("/upload-post");
    },
    downloadPost() {
      this.$store.dispatch("downloadPost");
    },
    changeFormat(date) {
      return changeDateFormat(date);
    },
  },
  computed: {
    ...mapGetters(["getPosts", "getHasMessage", "getMessage"]),
  },
};
</script>

<style>
</style>