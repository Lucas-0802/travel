<template>
  <div class="container-fluid vh-100 p-0">
    <div class="row half-screen background-primary-color d-flex align-items-start">
      <div class="d-flex align-items-center gap-2 header-logo">
        <img
          src="/logo_branca.png"
          :alt="$t('app.alt_logo')"
          class="img-fluid logo"
        />
        <h2 class="text-white title mt-2">{{ $t('app.title') }}</h2>
      </div>
    </div>

    <div class="row half-screen bg-white"></div>

    <AuthWrapper />
  </div>
</template>

<style scoped>
h2 {
  font-size: 0.9rem;
}

.logo {
  width: 50px;
  height: 60px;
}

.header-logo {
  position: absolute;
  top: 10px;
  left: 10px;
  display: flex;
  align-items: center;
}
</style>

<script setup lang="ts">
import AuthWrapper from "../components/AuthWrapper.vue";
</script>
