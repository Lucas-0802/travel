<template>
  <div
    class="modal fade"
    id="filtersModal"
    tabindex="-1"
    aria-labelledby="filtersModalLabel"
    aria-hidden="true"
    data-bs-backdrop="static"
  >
    <div class="modal-dialog">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="filtersModalLabel">
            {{ $t("filters.title") }}
          </h5>
          <button
            type="button"
            class="btn-close"
            data-bs-dismiss="modal"
            aria-label="Close"
          ></button>
        </div>
        <form @submit.prevent="applyFilters">
          <div class="modal-body">
            <div class="row">
              <div class="col-md-6">
                <div class="mb-3">
                  <label for="id" class="form-label">{{
                    $t("order.id")
                  }}</label>
                  <input
                    type="text"
                    class="form-control"
                    id="id"
                    v-model="order.id"
                    placeholder="Id"
                  />
                </div>
              </div>
              <div class="col-md-6">
                <div class="mb-3">
                  <label for="status_id" class="form-label">{{
                    $t("order.status")
                  }}</label>
                  <select
                    v-model="order.status_id"
                    id="status_id"
                    class="form-control"
                  >
                    <option value="" disabled>
                      {{ $t("order.select_status") }}
                    </option>
                    <option
                      v-for="stat in status"
                      :key="stat.id"
                      :value="stat.id"
                    >
                      {{ stat.name }}
                    </option>
                  </select>
                </div>
              </div>
            </div>

            <div class="mb-3">
              <label for="origin" class="form-label">{{
                $t("order.origin")
              }}</label>
              <select
                v-model="order.origin_id"
                id="origin_id"
                class="form-control"
              >
                <option value="" disabled>
                  {{ $t("order.select_origin") }}
                </option>
                <option v-for="city in cities" :key="city.id" :value="city.id">
                  {{ city.name }}
                </option>
              </select>
            </div>

            <div class="mb-3">
              <label for="destination" class="form-label">{{
                $t("order.destination")
              }}</label>
              <select
                v-model="order.destination_id"
                id="destination_id"
                class="form-control"
              >
                <option value="" disabled>
                  {{ $t("order.select_destination") }}
                </option>
                <option v-for="city in cities" :key="city.id" :value="city.id">
                  {{ city.name }}
                </option>
              </select>
            </div>

            <div class="mb-3">
              <label for="destination" class="form-label">{{
                $t("order.type_travel")
              }}</label>
              <select
                v-model="order.travel_type_id"
                id="travel_type_id"
                class="form-control"
              >
                <option value="" disabled>
                  {{ $t("order.select_type") }}
                </option>
                <option v-for="type in types" :key="type.id" :value="type.id">
                  {{ type.name }}
                </option>
              </select>
            </div>

            <div class="row">
              <div class="col-md-6">
                <div class="mb-3">
                  <label for="departure_date" class="form-label">{{
                    $t("order.departure_date")
                  }}</label>
                  <input
                    type="date"
                    v-model="order.departure_date"
                    id="departure_date"
                    class="form-control"
                    @change="validateDates"
                  />
                </div>
              </div>
              <div class="col-md-6">
                <div class="mb-3">
                  <label for="return_date" class="form-label">{{
                    $t("order.return_date")
                  }}</label>
                  <input
                    type="date"
                    v-model="order.return_date"
                    id="return_date"
                    class="form-control"
                    @change="validateDates"
                  />
                </div>
              </div>
            </div>

            <FeedbackMessage
              v-if="feedbackMessage"
              :message="feedbackMessage"
              :type="feedbackType"
            />
          </div>

          <div class="modal-footer">
            <button type="button" class="btn cancel" @click="clearFields">
              {{ $t("order.clean_filters") }}
            </button>
            <button type="submit" class="btn new-order-btn" :disabled="loading">
              {{ loading ? $t("order.processing") : $t("order.apply_filters") }}
            </button>
          </div>
        </form>
      </div>
    </div>
  </div>
</template>

<script setup>
import { ref, onMounted } from "vue";
import { useI18n } from "vue-i18n";
import { AxiosError } from "axios";
import api from "../api";
import FeedbackMessage from "./FeedbackMessage.vue";

const { t } = useI18n();
const emit = defineEmits(["applyFilters"]);
const loading = ref(false);
const feedbackMessage = ref("");
const feedbackType = ref("error");
const cities = ref([]);
const types = ref([]);
const status = ref([]);

const order = ref({
  id: null,
  status_id: "",
  origin_id: "",
  destination_id: "",
  travel_type_id: "",
  departure_date: "",
  return_date: "",
});

const validateDates = () => {
  if (order.value.departure_date && order.value.return_date) {
    const departureDate = new Date(order.value.departure_date);
    const returnDate = new Date(order.value.return_date);

    if (returnDate < departureDate) {
      feedbackMessage.value = t("messages.invalid_return_date");
      order.value = { ...order.value, return_date: "" };
      return;
    }
  }
  feedbackMessage.value = "";
};

const applyFilters = () => {
  if (feedbackMessage.value) return;
  emit("applyFilters", { ...order.value });
  closeModal();
};

const clearFields = () => {
  order.value = {
    id: null,
    status_id: "",
    origin: "",
    destination: "",
    departure_date: "",
    return_date: "",
  };
  feedbackMessage.value = "";
  emit("applyFilters", { ...order.value });
  closeModal();
};

const closeModal = () => {
  const modalElement = document.getElementById("filtersModal");
  if (modalElement) {
    const modalInstance = bootstrap.Modal.getInstance(modalElement);
    modalInstance.hide();
  }
};

const getCities = async () => {
  try {
    const response = await api.get("/cities/list");
    cities.value = response.data.data || [];
  } catch (error) {
    if (error instanceof AxiosError) {
      feedbackMessage.value = t(`messages.${error.response?.data?.message}`);
      feedbackType.value = "error";
    }
  }
};

const getTypes = async () => {
  try {
    const response = await api.get("/travel_types/list");
    types.value = response.data.data || [];
  } catch (error) {
    if (error instanceof AxiosError) {
      feedbackMessage.value = t(`messages.${error.response?.data?.message}`);
      feedbackType.value = "error";
    }
  }
};

const getStatus = async () => {
  try {
    const response = await api.get("/travel_order_status/list");
    status.value = response.data.data || [];
  } catch (error) {
    if (error instanceof AxiosError) {
      feedbackMessage.value = t(`messages.${error.response?.data?.message}`);
      feedbackType.value = "error";
    }
  }
};

onMounted(() => {
  getCities();
  getTypes();
  getStatus();
});
</script>
