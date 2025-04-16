<template>
  <div class="main-container">
    <div class="flex flex-column">
      <Logo class="mx-auto mb-3"></Logo>
      <div class="register-container">
        <div class="register-container__title mb-2">{{ type === 'login' ? 'Вход' : 'Регистрация' }}</div>
        <Form @submit="onAuth" :validation-schema="schema" class="flex flex-column align-items-center">
          <div v-for="field in fields" :key="field.name" class="flex flex-column mw-283">
            <label class="mr-auto">{{ field.label }}:</label>
            <Field :name="field.name" :type="field.type" v-slot="{ field, errors }">
              <InputText :invalid="errors.length !== 0" v-bind="field" style="background: white; color: black" />
              <div style="height: 17px;">
                <Transition name="fade-message">
                  <Message v-if="errors.length !== 0" :name="field.name" size="small" severity="error" variant="simple">{{ errors[0] }}</Message>
                </Transition>
              </div>
            </Field>
          </div>
          <Button type="submit" color="primary" class="mt-2">{{ type === 'login' ? 'Войти' : 'Зарегистрироваться' }}</Button>
        </Form>
      </div>
    </div>
    <Toast position="bottom-right" />
  </div>
</template>

<script setup lang="ts">
import { computed } from 'vue';
import { useRouter } from 'vue-router';
import Repository from '@/api/Auth/AuthRepository';
import { Field, Form } from 'vee-validate';
import * as yup from 'yup';
import { Button, InputText, Message, Toast } from 'primevue';
import Logo from "@/assets/logo.svg";
import { IProps } from './Auth.types';
import { useToast } from 'primevue';

const toast = useToast();
const router = useRouter();

const props = defineProps<IProps>();

const schema = computed(() => {
  const baseSchema: Record<string, any> = {
    email: yup.string().email().required(),
    password: yup.string().required(),
  }

  if (props.type === 'register') {
    baseSchema.name = yup.string().required();
    baseSchema.password_confirmation = yup.string().oneOf([yup.ref('password')]).required();
  }

  return yup.object(baseSchema)
})

const fields = computed(() => {
  const authFields = [
    { name: 'email', type: 'email', label: 'Email' },
    { name: 'password', type: 'password', label: 'Пароль' },
  ]

  const registerFields = [
    { name: 'name', type: 'text', label: 'Имя' },
    { name: 'password_confirmation', type: 'password', label: 'Подтвердите пароль' },
  ]

  props.type === 'register'
    ? authFields.unshift(registerFields[0])
      && authFields.push(registerFields[1])
    : authFields;

  return authFields;
});

const onRegister = async (values: any) => {
  try {
    const { data } = await Repository.register(values);
    if (data.status === 'success') {
      router.push({ name: 'home' });
    }
  } catch (error: any) {
    toast.add({ detail: 'Something wrong', severity: 'error', life: 3000, summary: 'Error' });
  }
};

const onLogin = async (values: any) => {
  try {
    const { data } = await Repository.login(values);
    if (data.status === 'success') {
      router.push({ name: 'home' });
    }
  } catch (error: any) {
    toast.add({ detail: 'Something wrong', severity: 'error', life: 3000, summary: 'Error' });
  }
}

const onAuth = async (values: any) => {
  if (props.type === 'login') {
    onLogin(values);
  } else {
    onRegister(values);
  }
}
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
  color: #1E1E1E;
  border-radius: 16px;

  label {
    font-weight: 500;
    font-size: 25px
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
  transition: opacity 0.3s ease, transform 0.3s ease;
}
.fade-message-enter-from,
.fade-message-leave-to {
  opacity: 0;
  transform: translateY(-4px);
}
</style>
