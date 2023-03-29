<template>
  <nav class="navbar navbar-expand-lg bg-body-tertiary" style="background-color: #f2faf8">
    <div class="container-fluid">
      <router-link to="/posts-list" class="navbar-brand">Bulletin Board</router-link>
      <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent"
        aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
        <span class="navbar-toggler-icon"></span>
      </button>
      <div class="collapse navbar-collapse" id="navbarSupportedContent">
        <ul class="navbar-nav me-auto mb-2 mb-lg-0">
          <li class="nav-item" v-if="$store.state.auth.isLogin">
            <router-link to="/users-list" class="nav-link" aria-current="page">Users</router-link>
          </li>
          <li class="nav-item">
            <router-link to="/posts-list" class="nav-link">Posts</router-link>
          </li>
        </ul>
        <div class="d-flex">
          <ul class="navbar-nav me-auto mb-2 mb-lg-0">
            <li class="nav-item" v-if="!$store.state.auth.isLogin">
              <router-link class="nav-link" to="/login">Login</router-link>
            </li>
            <li class="nav-item" v-if="$store.state.auth.isLogin">
              <router-link class="nav-link" to="/register">Create User</router-link>
            </li>
            <li class="nav-item" v-if="$store.state.auth.isLogin">
              <div class="dropdown me-0 mt-0 m-auto">
                <button class="btn dropdown-toggle" type="button" data-bs-toggle="dropdown" aria-expanded="false">
                  {{ $store.state.auth.loginUser.name }}
                </button>
                <ul class="dropdown-menu">
                  <li>
                    <router-link class="dropdown-item" to="/profile">Profile</router-link>
                  </li>
                  <li>
                    <button class="dropdown-item" @click="$store.dispatch('Logout')">
                      Logout
                    </button>
                  </li>
                </ul>
              </div>
            </li>
          </ul>
        </div>
      </div>
    </div>
  </nav>
</template>

<script>
export default {
  name: "Headers",
};
</script>
