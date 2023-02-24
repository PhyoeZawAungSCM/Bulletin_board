<template>
    <div class="container">
        <router-view></router-view>
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

<style>

</style>