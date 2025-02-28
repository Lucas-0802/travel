<template>
  <header class="header">
    <div class="header-left">
      <img src="/onfly.svg" alt="Logo" class="logo" />
      <h4 class="title mt-2">{{ $t("header.title") }}</h4>
    </div>

    <div class="header-right">
    
      <div class="notification-container">
        <i class="bi bi-bell-fill mx-4 notification-icon" @click="toggleNotifications"></i>
        <span v-if="unreadCount > 0" class="notification-count">
          {{ unreadCount }}
        </span>


        <div v-if="showNotifications" class="notification-popover">
          <div class="notification-header">
            <h6>{{ $t("notifications.title") }}</h6>
            <button class="close-btn" @click="showNotifications = false">✕</button>
          </div>
          <ul class="notification-list">
            <li v-if="notifications.length === 0" class="notification-item text-muted">
              {{ $t("notifications.no_notifications") }}
            </li>
            <li v-for="(notification, index) in notifications" :key="index" class="notification-item">
              <div class="notification-content">
                {{ notification.content }}
              </div>
              <button class="btn btn-sm discard-btn" @click="discardNotification(notification.id)">
                <i class="bi bi-trash"></i> {{ $t("notifications.descart") }}
              </button>
            </li>
          </ul>
        </div>
      </div>

      <div class="dropdown">
        <button class="btn dropdown-btn dropdown-toggle" data-bs-toggle="dropdown">
          {{ $t("header.hello", { name: userName }) }}
        </button>
        <ul class="dropdown-menu">
          <li>
            <a class="dropdown-item" href="#" @click="logout">
              <i class="bi bi-box-arrow-right"></i> {{ $t("header.logout") }}
            </a>
          </li>
        </ul>
      </div>
    </div>
  </header>

  <LoadingSpinner v-if="loading" />
</template>

<script setup>
import { ref, computed, onMounted, onUnmounted } from "vue";
import { useRoute, useRouter } from "vue-router";
import { useI18n } from "vue-i18n";
import LoadingSpinner from "./LoadingSpinner.vue";
import api from "../api";
import { AxiosError } from "axios";

const { t } = useI18n();
const loading = ref(false);
const route = useRoute();
const router = useRouter();
const userName = route.query.name;

const showNotifications = ref(false);

const notifications = ref([]);

onMounted(() => {
  fetchNotifications();
});

const fetchNotifications = async () => {
  loading.value = true;
  try {
    const response = await api.get("/notification/list");
    notifications.value = response.data.notifications;
  } catch (error) {
    console.error("Erro ao buscar notificações:", error);
  } finally {
    loading.value = false;
  }
};


const unreadCount = computed(() => notifications.value.length);

const discardNotification = async (notification_id) => {
  try {
    await api.delete(`/notification/discard/${notification_id}`);
    notifications.value = notifications.value.filter(n => n.id !== notification_id);
  } catch (error) {
    console.error("Erro ao descartar notificação:", error);
  }
};

const toggleNotifications = () => {
  showNotifications.value = !showNotifications.value;
};


const handleClickOutside = (event) => {
  if (!event.target.closest(".notification-container")) {
    showNotifications.value = false;
  }
};

onMounted(() => {
  document.addEventListener("click", handleClickOutside);
});

onUnmounted(() => {
  document.removeEventListener("click", handleClickOutside);
});


const logout = async () => {
  loading.value = true;
  try {
    await api.post("/logout");
    localStorage.removeItem("token");
    localStorage.removeItem("user_type");
    router.push("/login");
  } catch (error) {
    console.error("Erro ao fazer logout:", error);
  } finally {
    loading.value = false;
  }
};
</script>

<style scoped>
.header {
  display: flex;
  justify-content: space-between;
  align-items: center;
  padding: 1rem;
  background-color: white;
  box-shadow: 0px 2px 5px rgba(0, 0, 0, 0.1);
  border-radius: 8px;
}

h4 {
  font-size: 1.4rem;
}

.header-left {
  display: flex;
  align-items: center;
}

.logo {
  width: 40px;
}

.title {
  color: var(--primary-color);
  margin-left: 10px;
}

.header-right {
  display: flex;
  align-items: center;
}

.notification-container {
  position: relative;
}

.notification-icon {
  color: var(--primary-color);
  margin-left: 1rem;
  font-size: 1.5rem;
  cursor: pointer;
  position: relative;
}

.notification-count {
  position: absolute;
  top: -5px;
  right: 15px;
  background-color: red;
  color: white;
  font-size: 12px;
  font-weight: bold;
  padding: 3px 6px;
  border-radius: 50%;
  min-width: 18px;
  text-align: center;
}

.notification-popover {
  position: absolute;
  top: 35px;
  right: 0;
  width: 500px;
  height: 300px;
  background: white;
  border: 1px solid #ddd;
  box-shadow: 0px 2px 5px rgba(0, 0, 0, 0.2);
  border-radius: 8px;
  overflow: auto;
  padding: 10px;
  z-index: 100;
}

.notification-header {
  display: flex;
  justify-content: space-between;
  align-items: center;
  padding-bottom: 10px;
  border-bottom: 1px solid #ddd;
}

.close-btn {
  background: none;
  border: none;
  font-size: 16px;
  cursor: pointer;
}

.notification-list {
  max-height: 300px;
  overflow-y: auto;
}

.notification-item {
  display: flex;
  justify-content: space-between;
  align-items: center;
  padding: 10px;
  border-bottom: 1px solid #f0f0f0;
}

.discard-btn {
  background-color: white;
  color: black;
  border: 1px solid #ccc;
  padding: 3px 6px;
  font-size: 12px;
  cursor: pointer;
}


.dropdown-btn {
  background-color: var(--orange-color);
  color: white;
  width: 170px;
  border: none;
  padding: 5px;
  cursor: pointer;
}
</style>
