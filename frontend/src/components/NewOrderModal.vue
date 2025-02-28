<template>
  <div
    class="modal fade"
    id="newOrderModal"
    tabindex="-1"
    aria-labelledby="newOrderModalLabel"
    aria-hidden="true"
    data-bs-backdrop="static"
  >
    <div class="modal-dialog">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="newOrderModalLabel">
            {{ $t("order.new_order") }}
          </h5>
          <button
            type="button"
            @click="clearFields"
            class="btn-close"
            data-bs-dismiss="modal"
            aria-label="Close"
          ></button>
        </div>
        <form @submit.prevent="submitOrder">
          <div class="modal-body">
            <div class="mb-3">
              <label for="origin" class="form-label">{{
                $t("order.origin")
              }}</label>
              <select v-model="order.origin_id" id="origin_id" class="form-control">
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
                  <label for="departure_date" class="form-label">
                    {{ $t("order.departure_date") }}
                  </label>
                  <input
                    type="date"
                    ref="departureDateInput"
                    v-model="order.departure_date"
                    id="departure_date"
                    class="form-control"
                    @change="validateDepartureDate"
                  />
                </div>
              </div>
              <div class="col-md-6">
                <div class="mb-3">
                  <label for="return_date" class="form-label">
                    {{ $t("order.return_date") }}
                  </label>
                  <input
                    type="date"
                    ref="returnDateInput"
                    v-model="order.return_date"
                    id="return_date"
                    class="form-control"
                    @change="validateReturnDate"
                  />
                </div>
              </div>
            </div>
          </div>
          <div class="modal-footer">
            <button
              type="button"
              class="btn cancel"
              @click="clearFields"
              data-bs-dismiss="modal"
            >
              {{ $t("order.cancel") }}
            </button>
            <button type="submit" class="btn new-order-btn" :disabled="loading">
              {{ loading ? $t("order.processing") : $t("order.create_order") }}
            </button>
          </div>
        </form>
      </div>
    </div>

    <LoadingSpinner v-if="loading" />

    <FeedbackMessage
      v-if="feedbackMessage"
      :message="feedbackMessage"
      :type="feedbackType"
    />
  </div>
</template>

<script setup>
import { onMounted, ref } from "vue";
import { useI18n } from "vue-i18n";
import LoadingSpinner from "./LoadingSpinner.vue";
import FeedbackMessage from "./FeedbackMessage.vue";
import api from "../api";
import { AxiosError } from "axios";
import { eventBus } from "../utils/events/eventBus";

const emit = defineEmits(["orderCreated"]);

const { t } = useI18n();

const order = ref({
  origin_id: "",
  destination_id: "",
  travel_type_id: "",
  departure_date: "",
  return_date: "",
});

const loading = ref(false);
const feedbackMessage = ref("");
const feedbackType = ref("success");
const cities = ref([]);
const types = ref([]);

const clearFields = () => {
  order.value = {
    origin: "",
    destination: "",
    departure_date: "",
    return_date: "",
  };
};

const validateDepartureDate = () => {
  const today = new Date().toISOString().split("T")[0];
  if (order.value.departure_date < today) {
    feedbackMessage.value = t("messages.invalid_departure_date");
    feedbackType.value = "error";
    order.value.departure_date = "";
  }
  validateReturnDate();
};

const validateReturnDate = () => {
  if (
    order.value.return_date &&
    order.value.return_date < order.value.departure_date
  ) {
    feedbackMessage.value = t("messages.invalid_return_date");
    feedbackType.value = "error";
    order.value.return_date = "";
  }
};

const validateOrder = () => {
  feedbackMessage.value = "";

  const today = new Date();
  today.setHours(0, 0, 0, 0);

  const departureDate = order.value.departure_date
    ? new Date(order.value.departure_date)
    : null;
  const returnDate = order.value.return_date
    ? new Date(order.value.return_date)
    : null;

  if (!order.value.origin_id) {
    feedbackMessage.value = t("messages.please_provide_the_trip_origin");
    feedbackType.value = "error";
    return false;
  }

  if (!order.value.destination_id) {
    feedbackMessage.value = t("messages.please_provide_the_trip_destination");
    feedbackType.value = "error";
    return false;
  }

  if (!order.value.travel_type_id) {
    feedbackMessage.value = t("messages.please_provide_the_trip_type");
    feedbackType.value = "error";
    return false;
  }

  if (!departureDate) {
    feedbackMessage.value = t("messages.please_provide_the_departure_date");
    feedbackType.value = "error";
    return false;
  }

  if (!returnDate) {
    feedbackMessage.value = t("messages.please_provide_the_return_date");
    feedbackType.value = "error";
    return false;
  }

  return true;
};

const submitOrder = async () => {
  if (!validateOrder()) return;

  loading.value = true;
  feedbackMessage.value = "";
   
  try {
    let response = await api.post("/travel_order/create", order.value);

    const modalElement = document.getElementById("newOrderModal");
    if (modalElement) {
      const modalInstance = bootstrap.Modal.getInstance(modalElement);
      modalInstance.hide();
    }

    order.value = {
      origin_id: "",
      destination_id: "",
      travel_type_id: "",
      departure_date: "",
      return_date: "",
    };

    feedbackMessage.value = t(`messages.${response.data.message}`);
    feedbackType.value = "success";

    eventBus.emit("refreshTable");
    eventBus.emit("refreshDashboard");
  } catch (error) {
    if (error instanceof AxiosError) {
      feedbackMessage.value = t(`messages.${error.response?.data?.message}`);
      feedbackType.value = "error";
    }
  } finally {
    loading.value = false;
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

onMounted(() => {
  getCities();
  getTypes();
});

</script>
