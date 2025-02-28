<template>
  <section class="dashboard-overview">
    <div class="overview-header">
      <h4>{{ $t("dashboard.overview") }}</h4>
      <button
        v-if="user_type === 'common'"
        @click="openModal"
        class="btn background-primary-color text-white"
      >
        {{ $t("order.new_order") }}
      </button>
    </div>
    <div class="cards-container">
      <div class="card" v-for="stat in stats" :key="stat.label">
        <h5><i :class="stat.icon"></i> {{ $t(stat.label) }}</h5>
        <span :class="stat.color">{{ stat.value }}</span>
      </div>
    </div>
  </section>

  <NewOrderModal />
</template>

<script setup>
import { ref, onMounted } from "vue";
import { useI18n } from "vue-i18n";
import NewOrderModal from "./NewOrderModal.vue";
import api from "../api";
import OrdersTable from "../components/OrdersTable.vue";
import { eventBus } from "../utils/events/eventBus";

const { t } = useI18n();
const dashboardOverview = ref(null);

const refreshStatistics = () => {
  if (dashboardOverview.value) {
    dashboardOverview.value.getStatistics();
  }
};

const user_type = ref(localStorage.getItem("user_type") || "common");

const stats = ref([
  { label: "dashboard.total_orders", value: 0, color: "blue" },
  { label: "dashboard.requested", value: 0, color: "purple" },
  { label: "dashboard.approved", value: 0, color: "green" },
  { label: "dashboard.canceled", value: 0, color: "red" },
  { label: "dashboard.requested_cancellation", value: 0, color: "yellow" },
]);

const getStatistics = async () => {
  try {
    const response = await api.get("/travel_order/getStatistics");
    const statistics = response.data.statistics || {};
    const statusCounts = statistics.status_counts || {};

    stats.value[0].value = statistics.total_trips || 0;
    stats.value[1].value = statusCounts["1"] || 0;
    stats.value[2].value = statusCounts["2"] || 0;
    stats.value[3].value = statusCounts["3"] || 0;
  } catch (error) {
    errorMessage.value = t("errors.fetch_statistics");
  }
};

onMounted(() => {
  getStatistics();
  eventBus.on("refreshDashboard", getStatistics);
});

let bootstrap = window.bootstrap;

const openModal = () => {
  const modalElement = document.getElementById("newOrderModal");
  if (modalElement && bootstrap) {
    const modalInstance = new bootstrap.Modal(modalElement);
    modalInstance.show();
  }
};
</script>

<style scoped>
.dashboard-overview {
  padding: 1.5rem 0;
}
.overview-header {
  display: flex;
  justify-content: space-between;
  align-items: center;
  margin-bottom: 1rem;
}

.cards-container {
  display: flex;
  gap: 1rem;
}
.card {
  background: white;
  padding: 1rem;
  box-shadow: 0px 2px 5px rgba(0, 0, 0, 0.1);
  border-radius: 8px;
  text-align: center;
  flex: 1;
}
.blue {
  font-size: 1.5rem;
  font-weight: bold;
  color: var(--primary-color);
}

.purple {
  font-size: 1.5rem;
  font-weight: bold;
  color: var(--purple-color);
}

.green {
  font-size: 1.5rem;
  font-weight: bold;
  color: var(--success-color);
}

.red {
  font-size: 1.5rem;
  font-weight: bold;
  color: var(--error-color);
}

.yellow {
  font-size: 1.5rem;
  font-weight: bold;
  color: var(--warning-color);
}

h5 {
  color: #2c2d33;
}

span {
  color: #45464e;
}
</style>
