<template>
    <div class="container">
        <router-view></router-view>
         <vue-progress-bar></vue-progress-bar>
    </div>
   
</template>

<script>

export default {
    name:"App",
    beforeCreate(){
        this.$store.dispatch('isAlreadyLogin');
    }
}
</script>

