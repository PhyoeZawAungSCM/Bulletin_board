<template>
  <div class="border border-secondary rounded-top my-4">
    <div class="d-block bg-success py-2 align-items-center">
      <h4 class="text-white ps-3">User list</h4>
    </div>
    <success-noti v-if="getHasMessage" :isActive="getHasMessage">{{getMessage}}</success-noti>
    <div class="d-flex justify-content-end py-2 align-items-center ms-3">
      <label for="keyword" class="me-3 fw-semibold">Name:</label>
      <input
        v-model="name"
        type="text"
        name="keyword"
        class="form-control me-3"
      />
      <label for="keyword" class="me-3 fw-semibold">Email:</label>
      <input
        v-model="email"
        type="text"
        name="keyword"
        class="form-control me-3"
      />
      <label for="keyword" class="me-3 fw-semibold">From:</label>
      <input
        v-model="startDate"
        type="date"
        name="keyword"
        class="form-control me-3"
      />
      <label for="keyword" class="me-3 fw-semibold">To:</label>
      <input
        v-model="endDate"
        type="date"
        name="keyword"
        class="form-control me-3"
      />
      <button
        class="btn btn-success me-3"
        style="width: 120px"
        @click="searchUser"
      >
        Search
      </button>
    </div>
    <div class="mx-3">
      <div class="table-responsive">
        <table
          class="table table-striped align-middle text-center mt-3 table-hover text-nowrap"
    
        >
          <thead style="background-color: #78b3a4" class="text-white">
            <tr>
              <th>No</th>
              <th>Name</th>
              <th>Email</th>
              <th>Type</th>
              <th>Phone</th>
              <th>Date of Birth</th>
              <th>Address</th>
              <th>Created Date</th>
              <th>Updated Date</th>
              <th>Operations</th>
            </tr>
          </thead>
          <tbody class="fw-semibold text-black-50">
            <tr v-if="getAllUsers.length == 0">
              <td colspan="10">No data available in table</td>
            </tr>
            <tr
              style="cursor: pointer"
              v-for="user in getAllUsers"
              :key="user.id"
            >
              <td @click="openModal(user.id, 0)">{{ user.id }}</td>
              <td @click="openModal(user.id, 0)">{{ user.name }}</td>
              <td @click="openModal(user.id, 0)">{{ user.email }}</td>
              <td @click="openModal(user.id, 0)">
                {{ user.type == 0 ? "admin" : "user" }}
              </td>
              <td @click="openModal(user.id, 0)">{{ user.phone }}</td>
              <td @click="openModal(user.id, 0)">{{ user.dob }}</td>
              <td @click="openModal(user.id, 0)">{{ user.address }}</td>
              <td @click="openModal(user.id, 0)">
                {{ changeFormat(user.created_at) }}
              </td>
              <td @click="openModal(user.id, 0)">
                {{ changeFormat(user.updated_at) }}
              </td>
              <td>
                <button class="btn btn-danger" @click="openModal(user.id, 1)">
                  Delete
                </button>
              </td>
            </tr>
          </tbody>
        </table>
      </div>
      <nav aria-label="Page navigation example">
        <ul class="pagination justify-content-end">
          <li class="page-item">
            <button class="page-link" @click="previous">Previous</button>
          </li>
          <li
            class="page-item"
            :class="page == p ? 'active' : ''"
            v-for="p in $store.state.user.lastPage"
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
    <UserModal ref="userModal" :mode="mode" />
  </div>
</template>

<script>
import UserModal from "./UserModal.vue";
import { mapGetters } from "vuex";
import { changeDateFormat } from "../../services/ChangeDateFormat";
import SuccessNoti from '../Error/SuccessNoti.vue';
export default {
  components: {
    UserModal,
    SuccessNoti,
  },
  data() {
    return {
      mode: "",
      name: "",
      email: "",
      startDate: "",
      endDate: "",
      page: 1,
    };
  },
  methods: {
    openModal(id, mode) {
      //0 for mode of detail
      //1 for mode of delete
      console.log(mode);
      switch (mode) {
        case 0:
          this.mode = "detail";
          break;
        case 1:
          this.mode = "delete";
          break;
        default:
          this.mode = "detail";
          break;
      }
      this.$refs.userModal.showModal(id);
    },
    searchUser() {
      this.$store.dispatch("getUsers", {
        name: this.name,
        email: this.email,
        startDate: this.startDate,
        endDate: this.endDate,
        page: "",
      });
    },
    loadNewPage(page) {
      this.page = page;
      this.$store.dispatch("getUsers", {
        name: this.name,
        email: this.email,
        startDate: this.startDate,
        endDate: this.endDate,
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
      if (this.page < this.$store.state.user.lastPage) {
        this.page = this.page + 1;
        this.loadNewPage(this.page);
      }
    },
    changeFormat(date) {
      return changeDateFormat(date);
    },
  },
  computed: {
    ...mapGetters(["getAllUsers",'getHasMessage','getMessage']),
  },
};
</script>

<style>
.table-wrapper {
  overflow: auto;
  display: inline-block;
}
</style>