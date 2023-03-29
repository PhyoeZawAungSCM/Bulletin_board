<template>
  <UserRegister :isConfirm="true" />
</template>
   
<script>
import UserRegister from '../../components/User/UserRegister.vue';
export default {
  components: {
    UserRegister,
  }
}
</script>
