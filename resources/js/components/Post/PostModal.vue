<template>

<!-- Modal -->
  <div class="modal fade" id="exampleModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content">
      <div class="modal-header">
        <h1 class="modal-title fs-5" id="exampleModalLabel">{{ mode=='delete' ? 'Delete Confirm' : 'Post Detail'}}</h1>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        <div class="container">
         <div class="row mb-3">
            <div class="col-3">
                ID
            </div>
            <div class="col-9">
                {{ post.id }}
            </div>
         </div>
         <div class="row mb-3">
            <div class="col-3">
                Title
            </div>
            <div class="col-9">
                {{ post.title }}
            </div>
         </div>
         <div class="row mb-3">
            <div class="col-3">
               Description
            </div>
            <div class="col-9">
                {{post.description}}
            </div>
         </div>
         <div class="row mb-3">
            <div class="col-3">
                Status
            </div>
            <div class="col-9">
                {{ post.status }}
            </div>
         </div>
         <div v-if="mode == 'detail'">
            <div class="row mb-3">
            <div class="col-3">
                Created Date
            </div>
            <div class="col-9">
                {{post.created_at}}
            </div>
         </div>
         <div class="row mb-3">
            <div class="col-3">
               Created User
            </div>
            <div class="col-9">
                {{ post.create_user_id }}
            </div>
         </div>
         <div class="row mb-3">
            <div class="col-3">
                Updated Date
            </div>
            <div class="col-9">
                {{ post.updated_at }}
            </div>
         </div>
         <div class="row mb-3">
            <div class="col-3">
                Updated User
            </div>
            <div class="col-9">
                {{ post.updated_user_id }}
            </div>
         </div>
         </div>
        </div>
      </div>
      <div class="modal-footer">
        <div v-if="mode=='delete'">
            <button type="button" class="btn btn-secondary" @click="hideModal">Close</button>
            <button type="button" class="btn btn-danger" @click="doDelete">Delete</button>
        </div>
        <div v-else>
            <button type="button" class="btn btn-secondary" @click="hideModal">Close</button>
        </div>
      </div>
    </div>
  </div>
</div> 
</template>

<script>
import {Modal} from 'bootstrap/dist/js/bootstrap.bundle';
import { http } from '../../services/http_service';
export default {
  props:{
    mode:String,
  },
  data(){
    return{
        modal:null,
        post :{}
    }
   
  },

    methods: {
      showModal(id) {
        console.log("showModal");
        http().get(`api/posts/${id}`)
        .then(response => {
          this.post= response.data;
        });
        this.modal.show();
      },
      hideModal() {
        this.modal.hide();
      },
      doDelete(){
        console.log("delete");
        this.$store.dispatch('deletePost',this.post.id)
        this.hideModal();
      }
   },
   mounted(){
     this.modal = new Modal('#exampleModal', {
    });
   }
}
</script>

<style>

</style>