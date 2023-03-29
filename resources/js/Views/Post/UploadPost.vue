<template>
  <div class="row justify-content-center">
    <div class="col-9">
      <div class="card mt-5 border-success">
        <div class="card-header bg-success">
          <h3 class="text-white">Upload CSV file</h3>
        </div>
        <error-noti v-if="getHasError" :isActive="getHasError">
          {{ getMessage }}
        </error-noti>
        <div class="card-body justify-content-center d-block m-auto" style="width: 600px">
          <div class="row mb-3 align-items-center">
            <div class="col-3 text-end">
              <label for="title">CSV file</label>
              <span class="text-danger">*</span>
            </div>
            <div class="col-8">
              <input class="form-control" type="file" id="csvFile" @change="fileUpload" ref="upload" />
            </div>
          </div>
          <div class="row justify-content-end">
            <div class="col-9">
              <button class="btn btn-primary" @click="uploadCsv">Upload</button>
              <button class="btn btn-secondary" @click="clear">Clear</button>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>

<script>
import ErrorNoti from "../../components/Error/ErrorNoti.vue";
import { mapGetters } from "vuex";
export default {
  components: {
    ErrorNoti,
  },
  data() {
    return {
      csv: undefined,
    };
  },
  methods: {
    fileUpload() {
      const csv = this.$refs.upload.files[0];
      this.csv = csv;
      if (csv.type != "text/csv") {
        this.$store.state.noti.hasError = true;
        this.$store.state.noti.message = "Please upload a csv format";
        return;
      }
      const vm = this;
      this.$papa.parse(csv, {
        complete: function (result) {
          console.log("parsing complete read", result.data);
          result.data.forEach((data) => {
            console.log(data.length);
            if (data.length < 3) {
              console.log("Less than");
              vm.$store.state.noti.hasError = true;
              vm.$store.state.noti.message =
                "All post data must have 3 columns";
              return;
            }
          });
        },
      });
    },
    uploadCsv() {
      console.log("upload csv file", this.csv);
      this.$store.dispatch("uploadCsv", this.csv);
    },
    clear() {
      this.$refs.upload.value = null;
    }
  },
  computed: {
    ...mapGetters(["getHasError", "getMessage"]),
  },
};
