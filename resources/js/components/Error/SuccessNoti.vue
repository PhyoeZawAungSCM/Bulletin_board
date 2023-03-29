<template>
  <div v-show="isActive">
    <div class="alert alert-success alert-dismissible fade show" role="alert">
      <slot></slot>
      <button type="button" class="btn-close" aria-label="Close" @click="close"></button>
    </div>
  </div>
</template>
  
<script>
export default {
  props: {
    isActive: Boolean,
  },
  methods: {
    close() {
      this.$store.state.noti.hasMessage = false;
    },
  },
  mounted() {
    setTimeout(() => {
      this.$store.state.noti.hasMessage = false;
    }, 3000);
  },
  beforeDestroy() {
    this.$store.state.noti.hasMessage = false;
  },
};
</script>
