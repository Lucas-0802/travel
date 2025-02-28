<template>
  <section ref="ordersTable" class="orders-table">
    <div class="table-header row d-flex justify-content-between">
      <div class="col-md-6">
        <h5>{{ $t("orders.title") }}</h5>
      </div>
      <div class="col-md-6 d-flex justify-content-end">
        <button
          @click="openModal"
          class="btn background-primary-color text-white align-self-end"
        >
          <i class="bi bi-funnel"></i>
        </button>
      </div>
    </div>

    <div class="table-container">
      <table class="table mt-4">
        <thead>
          <tr class="text-center">
            <th scope="col">{{ $t("orders.id") }}</th>
            <th scope="col">{{ $t("orders.requester") }}</th>
            <th scope="col">{{ $t("orders.travel_type") }}</th>
            <th scope="col">{{ $t("orders.origin") }}</th>
            <th scope="col">{{ $t("orders.destination") }}</th>
            <th scope="col">{{ $t("orders.period") }}</th>
            <th scope="col">{{ $t("orders.status") }}</th>
            <th scope="col">{{ $t("orders.actions") }}</th>
          </tr>
        </thead>
        <tbody>
          <tr
            class="text-center"
            v-for="order in paginatedOrders"
            :key="order.id"
          >
            <td scope="row">{{ order.id }}</td>
            <td>{{ order.requester }}</td>
            <td>{{ order.travel_type }}</td>
            <td>{{ order.origin }}</td>
            <td>{{ order.destination }}</td>
            <td>{{ order.period }}</td>
            <td>
              <span :class="['badge', getStatusClass(order.status)]">
                {{ order.status }}
              </span>
            </td>
            <td>
              <div v-if="hasActions(order)" class="dropdown">
                <button
                  class="btn dropdown-toggle"
                  type="button"
                  data-bs-toggle="dropdown"
                >
                  {{ $t("orders.select") }}
                </button>
                <ul class="dropdown-menu">
                  <li
                    v-if="user_type === 'admin' && order.status === 'requested'"
                  >
                    <a
                      class="dropdown-item"
                      href="#"
                      @click="changeStatus(order, 'approve')"
                    >
                      <i class="bi bi-check2-circle"></i>
                      {{ $t("orders.approve") }}
                    </a>
                  </li>
                  <li
                    v-if="user_type === 'admin' && order.status === 'requested'"
                  >
                    <a
                      class="dropdown-item"
                      href="#"
                      @click="changeStatus(order, 'cancel')"
                    >
                      <i class="bi bi-x-circle"></i>
                      {{ $t("orders.cancel") }}
                    </a>
                  </li>
                  <li
                    v-if="
                      user_type === 'admin' &&
                      order.status === 'requested_cancellation'
                    "
                  >
                    <a
                      class="dropdown-item"
                      href="#"
                      @click="changeStatus(order, 'cancel')"
                    >
                      <i class="bi bi-check2-circle"></i>
                      {{ $t("orders.accept") }}
                    </a>
                  </li>
                  <li
                    v-if="
                      user_type === 'admin' &&
                      order.status === 'requested_cancellation'
                    "
                  >
                    <a
                      class="dropdown-item"
                      href="#"
                      @click="changeStatus(order, 'approve')"
                    >
                      <i class="bi bi-x-circle"></i>
                      {{ $t("orders.reject") }}
                    </a>
                  </li>
                  <li
                    v-if="user_type === 'common' && order.status === 'approved'"
                  >
                    <a
                      class="dropdown-item"
                      href="#"
                      @click="changeStatus(order, 'request_cancellation')"
                    >
                      <i class="bi bi-x-circle"></i>
                      {{ $t("orders.request_cancellation") }}
                    </a>
                  </li>
                </ul>
              </div>
              <span v-else>-</span>
            </td>
          </tr>
        </tbody>
      </table>
      <nav class="pagination-container row d-flex justify-content-end">
        <div class="col-md-3 d-flex justify-content-end">
          <ul class="pagination">
            <li class="page-item" :class="{ disabled: currentPage === 1 }">
              <button
                class="page-link"
                @click="prevPage"
                :disabled="currentPage === 1"
              >
                {{ $t("pagination.previous") }}
              </button>
            </li>

            <li
              v-for="page in visiblePages"
              :key="page"
              class="page-item"
              :class="{ active: page === currentPage }"
            >
              <button class="page-link" @click="goToPage(page)">
                {{ page }}
              </button>
            </li>

            <li
              class="page-item"
              :class="{ disabled: currentPage === totalPages }"
            >
              <button
                class="page-link"
                @click="nextPage"
                :disabled="currentPage === totalPages"
              >
                {{ $t("pagination.next") }}
              </button>
            </li>
          </ul>
        </div>
      </nav>
    </div>

    <FiltersModal @applyFilters="applyFilters" />

    <FeedbackMessage
      v-if="feedbackMessage"
      :message="feedbackMessage"
      :type="feedbackType"
    />
  </section>
  <LoadingSpinner v-if="loading" />
</template>

<script setup>
import { ref, computed, onMounted } from "vue";
import { useI18n } from "vue-i18n";
import api from "../api";
import { formatDate } from "../utils/formatDate";
import { getStatusClass } from "../utils/statusUtils";
import FiltersModal from "./FiltersModal.vue";
import FeedbackMessage from "./FeedbackMessage.vue";
import { AxiosError } from "axios";
import { eventBus } from "../utils/events/eventBus";

const user_type = ref(localStorage.getItem("user_type") || "common");
const { t } = useI18n();
const orders = ref([]);
const loading = ref(false);
const errorMessage = ref("");
const currentPage = ref(1);
const itemsPerPage = ref(5);
const filters = ref({});
const feedbackMessage = ref("");
const feedbackType = ref("success");

const openModal = () => {
  const modalElement = document.getElementById("filtersModal");
  if (modalElement) {
    const modalInstance = new bootstrap.Modal(modalElement);
    modalInstance.show();
  }
};

const hasActions = (order) => {      
  if (
    (user_type.value === "admin" && order.status === "requested") ||
    (user_type.value === "admin" && order.status === "requested_cancellation") ||
    (user_type.value === "common" && order.status === "approved")
  ) {
    return true;
  }
  return false;
};

const fetchOrders = async (appliedFilters = {}) => {
  loading.value = true;
  errorMessage.value = "";
  filters.value = appliedFilters;

  try {
    const response = await api.get("/travel_order/list", {
      params: filters.value,
    });    
    orders.value = response.data.orders.map((order) => ({
      id: order.id,
      requester: order.user.name,
      travel_type: order.travel_type.name,
      origin: order.origin.name,
      destination: order.destination.name,
      period: `${formatDate(order.departure_date)} - ${formatDate(
        order.return_date
      )}`,
      status: order.status.name,
      departure_date: order.departure_date,
      return_date: order.return_date,
    }));
    currentPage.value = 1;
  } catch (error) {
    if (error instanceof AxiosError) {
      feedbackMessage.value = t(`messages.${error.response?.data?.message}`);
      feedbackType.value = "error";
    }
  } finally {
    loading.value = false;
  }
};

const changeStatus = async (order, newStatus) => {
  
  const statusMap = {
    approve: 2,
    cancel: 3,
    request_cancellation: 4,
  };

  const status_id = statusMap[newStatus];
  
  try {
    const response = await api.put(`/travel_order/update_status/${order.id}`, {
      status_id,
    });
    feedbackMessage.value = t(`messages.${response.data.message}`);
    feedbackType.value = "success";
    fetchOrders();
    eventBus.emit("refreshDashboard");
  } catch (error) {
    if (error instanceof AxiosError) {
      feedbackMessage.value = t(`messages.${error.response?.data?.message}`);
      feedbackType.value = "error";
    }
  }
};

const applyFilters = (newFilters) => {
  fetchOrders(newFilters);
};

onMounted(() => {
  fetchOrders(); 
  eventBus.on("refreshTable", () => {
    fetchOrders();
  });
});

const totalPages = computed(() =>
  Math.ceil(orders.value.length / itemsPerPage.value)
);

const paginatedOrders = computed(() => {
  const start = (currentPage.value - 1) * itemsPerPage.value;
  return orders.value.slice(start, start + itemsPerPage.value);
});

const nextPage = () => {
  if (currentPage.value < totalPages.value) currentPage.value++;
};

const prevPage = () => {
  if (currentPage.value > 1) currentPage.value--;
};

const goToPage = (page) => {
  currentPage.value = page;
};

const visiblePages = computed(() => {
  const pages = [];
  const total = totalPages.value;
  let start = Math.max(1, currentPage.value - 2);
  let end = Math.min(total, currentPage.value + 2);

  for (let i = start; i <= end; i++) {
    pages.push(i);
  }
  return pages;
});

defineExpose({ fetchOrders });
</script>

<style scoped>
.orders-table {
  background: white;
  padding: 1rem;
  box-shadow: 0px 2px 5px rgba(0, 0, 0, 0.1);
  border-radius: 8px;
  margin-top: 1rem;
  min-height: 60vh;
  max-height: 60vh;
  display: flex;
  flex-direction: column;
  overflow: auto;
}

.table-container {
  flex: 1;
  display: flex;
  flex-direction: column;
  overflow: auto;
}

.table {
  width: 100%;
  border-collapse: collapse;
  table-layout: fixed;
}

.pagination {
  display: flex;
  justify-content: center;
  margin-top: 10px;
  padding-top: 10px;
}

.pagination button {
  background: var(--primary-color);
  color: white;
  border: none;
  padding: 5px 10px;
  cursor: pointer;
  margin: 0 5px;
}

.pagination button:disabled {
  background: #ccc;
  cursor: not-allowed;
}

.table th {
  color: #2c2d33;
}

.table td {
  color: #6e7079;
}

.badge-primary {
  background-color: #5570f1;
  color: white;
}
.badge-success {
  background-color: #519c66;
  color: white;
}
.badge-danger {
  background-color: #ea5018;
  color: white;
}

.badge-warning {
  background-color: #f7b924;
  color: white;
}
</style>
