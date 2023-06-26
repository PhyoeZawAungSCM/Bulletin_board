<template>
  <div>
    <Header/>
    <router-view></router-view>
    <Footer/>
  </div>
</template>

<script>
import Header from '../components/Common/Header.vue';
import Footer from '../components/Common/Footer.vue';
import { isLogin } from '../services/Auth_service';
export default {
components:{
    Header,
    Footer,
},
mounted(){
        this.$store.dispatch('getPosts',{search:'',page:1});
        if(isLogin()){
          this.$store.dispatch('getUsers',{name:'',email:'',startDate:'',endDate:'',page:1});
        }
        
    }
}
</script>

<style>

</style>