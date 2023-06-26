<template>
  <div class="row justify-content-center">
    <div class="col-9">
      <div class="card mt-5 border-success">
        <div class="card-header bg-success">
          <h3 class="text-white">Create post</h3>
        </div>
        <div
          class="card-body justify-content-center d-block m-auto"
          style="width: 600px"
        >
          <div class="row mb-3">
            <div class="col-3 text-end">
              <label for="title">Title</label>
              <span class="text-danger">*</span>
            </div>
            <div class="col-8">
              <input type="email" class="form-control" id="title" />
            </div>
          </div>
          <div class="row mb-3">
            <div class="col-3 text-end">
              <label for="description">Description</label>
              <span class="text-danger">*</span>
            </div>
            <div class="col-8">
              <textarea
                class="form-control"
                id="description"
                rows="3"
              ></textarea>
            </div>
          </div>
          <div class="row justify-content-end">
            <div class="col-9">
              <button class="btn btn-primary">Create</button>
              <button class="btn btn-secondary">Clear</button>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>

<script>
export default {};
</script>

<style>
</style>