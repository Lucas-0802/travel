<template>
  <transition name="fade">
    <div v-if="show" class="feedback-message" :class="type">
      <i :class="iconClass"></i> {{ message }}
    </div>
  </transition>
</template>

<script setup>
import { ref, watchEffect, computed } from "vue";

const props = defineProps({
  message: String,
  type: {
    type: String,
    default: "success",
  },
});
const iconClass = computed(() => {
  return props.type === "success"
    ? "bi bi-check-circle-fill icon-color-success"
    : "bi bi-x-circle-fill icon-color-error";
});

const show = ref(false);

watchEffect(() => {
  if (props.message) {
    show.value = true;
    setTimeout(() => {
      show.value = false;
    }, 3000);
  }
});
</script>

<style scoped>
.feedback-message {
  position: fixed;
  bottom: 20px;
  right: 20px;
  padding: 15px;
  border-radius: 5px;
  color: var(--secondary-color);
  z-index: 1051;
  background-color: white;
  width: 500px;
  text-align: center;
}

.icon-color-success {
  color: var(--success-color);
}

.icon-color-error {
  color: var(--error-color);
}

.fade-enter-active,
.fade-leave-active {
  transition: opacity 0.5s;
}
.fade-enter,
.fade-leave-to {
  opacity: 0;
}
</style>
