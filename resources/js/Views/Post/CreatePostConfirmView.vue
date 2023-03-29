<template>
  <Post :isConfirm="true" mode="create" />
</template>

<script>
import Post from "../../components/Post/Post.vue";
export default {
  components: {
    Post,
  },
};
</script>
