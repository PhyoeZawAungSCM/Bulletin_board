<template>
  <div class="row justify-content-center">
    <div class="col-9">
      <div class="card mt-5 border-success">
        <div class="card-header bg-success">
          <h3 class="text-white">Profile</h3>
        </div>
        <div class="card-body justify-content-center d-block m-auto mt-3" style="width: 100%">
          <div class="container">
            <div class="row">
              <div class="col-4">
                <img :src="`${$store.state.baseURL}/${$store.state.auth.loginUser.profile}`" class="d-block"
                  style="width: 200px" />
              </div>
              <div class="col-8">
                <div class="row mb-3">
                  <div class="col-4">Name</div>
                  <div class="col-5">
                    {{ $store.state.auth.loginUser.name }}
                  </div>
                </div>
                <div class="row mb-3">
                  <div class="col-4">Type</div>
                  <div class="col-5">
                    {{
                      $store.state.auth.loginUser.type == "0" ? "Admin" : "User"
                    }}
                  </div>
                </div>
                <div class="row mb-3">
                  <div class="col-4">Email</div>
                  <div class="col-5">
                    {{ $store.state.auth.loginUser.email }}
                  </div>
                </div>
                <div class="row mb-3">
                  <div class="col-4">Phone</div>
                  <div class="col-5">
                    {{ $store.state.auth.loginUser.phone }}
                  </div>
                </div>
                <div class="row mb-3">
                  <div class="col-4">Date of birth</div>
                  <div class="col-5">{{ $store.state.auth.loginUser.dob ? changeDateFormat($store.state.auth.loginUser.dob)
                    : '' }}</div>
                </div>
                <div class="row mb-3">
                  <div class="col-4">Address</div>
                  <div class="col-5">
                    {{ $store.state.auth.loginUser.address }}
                  </div>
                </div>
                <div class="row mb-3">
                  <div class="col-8">
                    <button class="btn btn-primary" @click="$router.push('/edit-profile')">
                      Edit Profile
                    </button>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>

<script>
import { changeDateFormat } from '../../services/ChangeDateFormat';

export default {
  methods: {
    changeDateFormat(date) {
      return changeDateFormat(date);
    }
  }
};
</script>
