<template>
  <Post :isConfirm="true" mode="edit" />
</template>

<script>
import Post from "../../components/Post/Post.vue";
export default {
  components: {
    Post,
  },
};
</script>
