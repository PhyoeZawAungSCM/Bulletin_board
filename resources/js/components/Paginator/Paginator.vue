<template>
    <div>
        <nav aria-label="Page navigation example">
            <ul class="pagination justify-content-end">
                <li class="page-item" >
                    <button class="page-link" @click="previous" ref="previousButton">Previous</button>
                </li>

                <li
                    v-for="pageButton in lastPage"
                    :key="pageButton"
                    :class="{ active: pageButton == page }"
                    @click="changePageTo(pageButton)"
                    class="page-item"
                >
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
    },
    data() {
        return {
            page: 0,
            currentButtonNumber: 0,
            nextButtonNumber: 1,
        };
    },
    name: "Paginator",
    methods: {
        previous() {
            if(this.page == 1){
                return;
            }
            this.page = this.page - 1;
            this.$emit("paginationFunction", this.page);
        },
        next() {
            if(this.page >= this.lastPage){
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
    // enable this if you want to load your first page from paginator
    // watch: {
    //     page(newPage, oldPage) {
    //         console.log("this is the page number:", newPage);
    //         console.log("this is the last page:", this.lastPage);
    //         if (newPage == 1) {
    //             this.$refs.previousButton.disabled = true;
    //         } else {
    //             this.$refs.previousButton.disabled = false;
    //         }
    //         if (newPage == this.lastPage) {
    //             console.log("reach last page");
    //             this.$refs.nextButton.disabled = true;
    //         } else {
    //             this.$refs.nextButton.disabled = false;
    //         }
    //     },
    // },

    // that is not using in my project but plan to use this later
};
</script>

<style>

</style>
