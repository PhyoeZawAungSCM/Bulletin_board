<template>
  <div v-show="isActive">
    <div class="alert alert-danger alert-dismissible fade show" role="alert">
      <slot></slot>
      <button type="button" class="btn-close" aria-label="Close" @click="close"></button>
    </div>
  </div>
</template>

<script>
export default {
  name: "ErrorNoti",
  props: {
    isActive: Boolean,
  },
  methods: {
    close() {
      this.$store.state.noti.hasError = false;
    },
  },
  mounted() {
    setTimeout(() => {
      this.$store.state.noti.hasError = false;
    }, 5000);
  },
  beforeDestroy() {
    this.$store.state.noti.hasError = false;
  },
};
</script>
