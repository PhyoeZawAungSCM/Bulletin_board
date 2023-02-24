<template>
   <nav aria-label="Page navigation example">
        <ul class="pagination justify-content-end">
            <li class="page-item"><button class="page-link" @click="previous">Previous</button></li>
            <li class="page-item" :class=" page == p ? 'active' : ''" v-for="p in $store.state.post.lastPage" :key="p"><button class="page-link" @click="loadNewPage(p)">{{ p}}</button></li>
            <li class="page-item"><button class="page-link" @click="next">Next</button></li>
        </ul>
    </nav>
</template>

<script>
export default {
    props:{
        data:Object,
    },
    name:'Paginator'

    // that is not using in my project but plan to use this later 
}
</script>

<style>

</style>