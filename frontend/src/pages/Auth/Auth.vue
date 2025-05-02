<template>
  <div class="main-container">
    <div class="flex flex-column">
      <div class="register-container">
        <Logo
          class="mx-auto"
          style="position: absolute; top: -80px"
          @click="router.push({ name: 'home' })"
        ></Logo>
        <div class="register-container__title mb-2">
          {{ type === "login" ? "Вход" : "Регистрация" }}
        </div>
        <Form
          @submit="onAuth"
          :validation-schema="schema"
          class="flex flex-column align-items-center"
        >
          <div
            v-for="item in fields"
            :key="item.name"
            class="flex flex-column mw-283"
          >
            <label class="mr-auto">{{ item.label }}:</label>
            <Field
              :name="item.name"
              :type="item.type"
              v-slot="{ field, errors }"
            >
              <Password
                v-if="['password', 'password_confirmation'].includes(item.type)"
                :invalid="errors.length !== 0"
                v-bind="field"
                :feedback="false"
              />
              <InputText v-else :invalid="errors.length !== 0" v-bind="field" />
              <div style="height: 17px">
                <Transition name="fade-message">
                  <Message
                    v-if="errors.length !== 0"
                    :name="field.name"
                    size="small"
                    severity="error"
                    variant="simple"
                    >{{ errors[0] }}</Message
                  >
                </Transition>
              </div>
            </Field>
          </div>
          <div class="flex flex-column mt-1 mb-2 row-gap-1">
            <Button type="submit" color="primary" :loading="isLoading">{{
              type === "login" ? "Войти" : "Зарегистрироваться"
            }}</Button>
            <Button
              severity="info"
              variant="text"
              @click="
                type === 'login'
                  ? router.push({ name: 'register' })
                  : router.push({ name: 'login' })
              "
            >
              {{ type === "login" ? "Или зарегистрироваться" : "Или войти" }}
            </Button>
          </div>
        </Form>
      </div>
    </div>
    <Toast position="bottom-right" />
  </div>
</template>

<script setup lang="ts">
import { computed, ref } from "vue";
import { useRouter } from "vue-router";
import Repository from "@/api/Auth/AuthRepository";
import { Field, Form } from "vee-validate";
import * as yup from "yup";
import { Button, InputText, Message, Toast, Password } from "primevue";
import Logo from "@/assets/logo.svg";
import { IProps } from "./Auth.types";
import { useToast } from "primevue";
import { useAuthStore } from "@/store/authStore";

const toast = useToast();
const router = useRouter();
const authStore = useAuthStore();
const isLoading = ref(false);

const props = defineProps<IProps>();

const schema = computed(() => {
  const baseSchema: Record<string, any> = {
    email: yup.string().email().required(),
    password: yup.string().required(),
  };

  if (props.type === "register") {
    baseSchema.name = yup.string().required();
    baseSchema.password_confirmation = yup
      .string()
      .oneOf([yup.ref("password")])
      .required();
  }

  return yup.object(baseSchema);
});

const fields = computed(() => {
  const authFields = [
    { name: "email", type: "email", label: "Email" },
    { name: "password", type: "password", label: "Пароль" },
  ];

  const registerFields = [
    { name: "name", type: "text", label: "Имя" },
    {
      name: "password_confirmation",
      type: "password",
      label: "Подтвердите пароль",
    },
  ];

  props.type === "register"
    ? authFields.unshift(registerFields[0]) &&
      authFields.push(registerFields[1])
    : authFields;

  return authFields;
});

const onRegister = async (values: any) => {
  try {
    isLoading.value = true;
    const { data } = await Repository.register(values);
    isLoading.value = false;
    if (data.status === "success") {
      await authStore.login();
      router.push({ name: "home" });
    }
  } catch (error: any) {
    toast.add({
      detail: "Something wrong",
      severity: "error",
      life: 3000,
      summary: "Error",
    });
  }
};

const onLogin = async (values: any) => {
  try {
    isLoading.value = true;
    const { data } = await Repository.login(values);
    isLoading.value = false;
    if (data.status === "success") {
      await authStore.login();
      router.push({ name: "home" });
    }
  } catch (error: any) {
    toast.add({
      detail: "Something wrong",
      severity: "error",
      life: 3000,
      summary: "Error",
    });
  }
};

const onAuth = async (values: any) => {
  if (props.type === "login") {
    onLogin(values);
  } else {
    onRegister(values);
  }
};
</script>

<style scoped lang="scss">
.main-container {
  height: 100%;
  width: 100%;
  display: flex;
  justify-content: center;
  align-items: center;
}

.register-container {
  display: flex;
  justify-content: center;
  align-items: center;
  flex-direction: column;
  min-width: 536px;
  min-height: 521px;
  background-color: white;
  color: #1e1e1e;
  border-radius: 16px;
  position: relative;

  label {
    font-weight: 500;
    font-size: 25px;
  }

  &__title {
    font-weight: 600;
    font-size: 60px;
  }
}

.mw-283 {
  min-width: 283px;
}

.fade-message-enter-active,
.fade-message-leave-active {
  transition:
    opacity 0.3s ease,
    transform 0.3s ease;
}
.fade-message-enter-from,
.fade-message-leave-to {
  opacity: 0;
  transform: translateY(-4px);
}

:deep(.p-inputtext) {
  background: white;
  color: black;
  width: 100%;
}
</style>
