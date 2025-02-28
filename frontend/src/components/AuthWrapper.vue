<template>
  <div class="form-container shadow rounded p-4">
    <img
      src="/onfly.svg"
      alt="Illustration"
      class="img-fluid mx-auto d-block mb-3 logo"
    />

    <!-- Login Screen -->
    <div v-if="isLoginForm">
      <h3 class="text-center mb-3">
        {{ $t("app.welcome", { appName: $t("app.title") }) }}
      </h3>
      <h4 class="text-center">{{ $t("app.login") }}</h4>

      <form @submit.prevent="login">
        <div class="mb-3">
          <label for="loginEmail" class="form-label">{{
            $t("app.email")
          }}</label>
          <input
            type="email"
            class="form-control"
            id="loginEmail"
            v-model="loginData.email"
            required
          />
        </div>

        <div class="mb-2">
          <label for="loginPassword" class="form-label">{{
            $t("app.password")
          }}</label>
          <input
            type="password"
            class="form-control"
            id="loginPassword"
            v-model="loginData.password"
            required
          />
        </div>

        

        <p class="text-center mt-3">
          {{ $t("app.no_account") }}
          <a href="#" @click="toggleForm" class="register-link">{{
            $t("app.sign_up")
          }}</a>
        </p>

        <div class="d-flex justify-content-center">
          <button
            type="submit"
            class="btn btn-lg background-primary-color text-white w-100"
          >
            {{ $t("app.sign_in") }}
          </button>
        </div>
      </form>
    </div>

    <!-- Registration Screen -->
    <div v-else-if="!isLoginForm">
      <h3 class="text-center mb-3">{{ $t("app.register") }}</h3>
      <h4 class="text-center">{{ $t("app.create_account") }}</h4>

      <form @submit.prevent="register">
        <div class="mb-3">
          <label for="registerName" class="form-label">{{
            $t("app.name")
          }}</label>
          <input
            type="text"
            class="form-control"
            id="registerName"
            v-model="registerData.name"
            required
          />
        </div>

        <div class="mb-3">
          <label for="registerEmail" class="form-label">{{
            $t("app.email")
          }}</label>
          <input
            type="email"
            class="form-control"
            id="registerEmail"
            v-model="registerData.email"
            required
          />
        </div>

        <div class="mb-2">
          <label for="registerPassword" class="form-label">{{
            $t("app.password")
          }}</label>
          <input
            type="password"
            class="form-control"
            id="registerPassword"
            v-model="registerData.password"
            required
          />
        </div>

        <div class="mb-2">
          <label for="registerPasswordConfirmation" class="form-label">{{
            $t("app.passwordConfirmation")
          }}</label>
          <input
            type="password"
            class="form-control"
            id="registerPasswordConfirmation"
            v-model="registerData.password_confirmation"
            required
          />
        </div>

        <p class="text-center mt-3">
          {{ $t("app.has_account") }}
          <a href="#" @click="toggleForm" class="login-link">{{
            $t("app.sign_in")
          }}</a>
        </p>

        <div class="d-flex justify-content-center">
          <button
            type="submit"
            class="btn btn-lg background-primary-color text-white w-100"
          >
            {{ $t("app.sign_up") }}
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
</template>


<script setup lang="ts">
import { ref } from "vue";
import { useRouter } from "vue-router";
import LoadingSpinner from "./LoadingSpinner.vue";
import FeedbackMessage from "./FeedbackMessage.vue";
import { useI18n } from "vue-i18n";
import api from "../api";
import { AxiosError } from "axios";

const { t } = useI18n();

const router = useRouter();
const loading = ref(false);
const feedbackMessage = ref("");
const feedbackType = ref("success");
const isLoginForm = ref(true);

const loginData = ref({
  email: "",
  password: "",
});

const registerData = ref({
  name: "",
  email: "",
  password: "",
  password_confirmation: "",
});



const login = async () => {
  loading.value = true;
  feedbackMessage.value = "";

  try {
    const response = await api.post("/login", loginData.value);
    if (response.data.token) {
      localStorage.setItem("token", response.data.token);
      localStorage.setItem("user_type", response.data.user.user_type);
      api.defaults.headers.Authorization = `Bearer ${response.data.token}`;
    }
    loginData.value = { email: "", password: "" };
    feedbackMessage.value = t(`messages.${response.data.message}`);
    feedbackType.value = "success";
       
    router.push({
      path: "/home",
      query: { name: response.data.user.name },
    });    
    
  } catch (error) {
    if(error instanceof AxiosError) {
      if(error.response?.status === 401) {
        feedbackMessage.value = t(`messages.invalid_credentials`);
      } else {
        feedbackMessage.value = t(`messages.${error.response?.data?.message}`);
      }
      feedbackType.value = "error";
    }
  } finally {
    loading.value = false;
  }
};

const register = async () => {
  loading.value = true;
  feedbackMessage.value = "";

  try {
    const response = await api.post("/register", {
      name: registerData.value.name,
      email: registerData.value.email,
      password: registerData.value.password,
      password_confirmation: registerData.value.password_confirmation,
    });

    feedbackMessage.value = t(`messages.${response.data.message}`);
    feedbackType.value = "success";

    isLoginForm.value = true;
    registerData.value = {
      name: "",
      email: "",
      password: "",
      password_confirmation: "",
    };
  } catch (error) {
    if(error instanceof AxiosError) {
      const message: string[] = Object.keys((error as AxiosError).response?.data?.messages).map((key) => {
        return (error as AxiosError).response?.data?.messages[key][0]
      });
      feedbackMessage.value = t(`messages.${message[0]}`);
      feedbackType.value = "error";
    }
  } finally {
    loading.value = false;
  }
};

const toggleForm = () => {
  isLoginForm.value = !isLoginForm.value;
};

</script>

<style scoped>
.half-screen {
  height: 50vh;
}

.logo {
  width: 70px;
}

h4 {
  color: var(--secondary-color);
  font-size: 1rem;
}

.form-container {
  position: absolute;
  top: 50%;
  left: 50%;
  transform: translate(-50%, -50%);
  width: 30%;
  height: 600px;
  min-width: 350px;
  min-height: 70vh;
  max-height: 90vh;
  overflow-y: auto;
  background-color: var(--gray-color);
}

span {
  color: var(--primary-color);
  font-weight: bolder;
}

.register-link,
.login-link {
  font-weight: bold;
  color: var(--primary-color);
  text-decoration: none;
}

.register-link:hover,
.login-link:hover {
  text-decoration: underline;
}

.btn-lg {
  width: 100%;
}
</style>
