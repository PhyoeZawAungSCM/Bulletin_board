<template>
  <div>
    <nav aria-label="Page navigation example">
      <ul class="pagination justify-content-end">
        <li class="page-item">
          <button class="page-link" @click="previous" ref="previousButton">Previous</button>
        </li>
        <li v-for="pageButton in lastPage" :key="pageButton" :class="{ active: pageButton == page }"
          @click="changePageTo(pageButton)" class="page-item">
          <button class="page-link">{{ pageButton }}</button>
        </li>
        <li class="page-item">
          <button class="page-link" @click="next" ref="nextButton">Next</button>
        </li>
      </ul>
    </nav>
  </div>
</template>

<script>
export default {
  props: {
    lastPage: {
      type: Number,
      required: true,
    },
    firstViewPageNumber: {
      type: Number,
      required: false,
      default: 1,
    },
    numberOfButtonToShow: {
      type: Number,
      required: false,
      default: 4,
    },
    page: {
      type: Number,
      default: 0,
    }
  },
  data() {
    return {
      currentButtonNumber: 0,
      nextButtonNumber: 1,
    };
  },
  name: "Paginator",
  methods: {
    previous() {
      if (this.page == 1) {
        return;
      }
      this.page = this.page - 1;
      this.$emit("paginationFunction", this.page);
    },
    next() {
      if (this.page >= this.lastPage) {
        return;
      }
      this.page = this.page + 1;
      this.currentButtonNumber = this.page + 1;
      this.$emit("paginationFunction", this.page);
    },
    changePageTo(page) {
      this.page = page;
      this.$emit("paginationFunction", this.page);
    },
  },
  mounted() {
    this.page = this.firstViewPageNumber;
    // enable this if you want to load your first page from paginator
    //this.$emit("paginationFunction", this.page);
  },
};
</script>
