<template>
  <div class="main-container">
    <div class="register-container">
      <div class="register-container__title">Регистрация</div>
      <Form @submit="onRegister" :validation-schema="schema" class="flex flex-column row-gap-3">
        <div class="flex flex-column mw-283">
          <label class="mr-auto">Имя:</label>
          <Field name="name" type="text" v-slot="{ field, meta, errors}">
            <InputText :invalid="errors.length !== 0" v-bind="field"/>
            <Message name="name" size="small" severity="error" variant="simple">{{ errors[0] }}</Message>
          </Field>
        </div>
        <div class="flex flex-column mw-283">
          <label class="mr-auto">Email:</label>
          <Field name="email" type="email" v-slot="{ field, meta, errors}">
            <InputText :invalid="errors.length !== 0" v-bind="field" />
            <Message name="email" size="small" severity="error" variant="simple">{{ errors[0] }}</Message>
          </Field>
        </div>
        <div class="flex flex-column mw-283">
          <label class="mr-auto">Пароль:</label>
          <Field name="password" type="password" v-slot="{ field, meta, errors}">
            <InputText :invalid="errors.length !== 0" v-bind="field" />
            <Message name="password" size="small" severity="error" variant="simple">{{ errors[0] }}</Message>
          </Field>
        </div>
        <div class="flex flex-column mw-283">
          <label class="mr-auto">Подтвердите пароль:</label>
          <Field name="password_confirmation" type="password" v-slot="{ field, meta, errors}">
            <InputText :invalid="errors.length !== 0" v-bind="field" />
            <Message name="password_confirmation" size="small" severity="error" variant="simple">{{ errors[0] }}</Message>
          </Field>
        </div>
        <Button type="submit" color="primary">Зарегистрироваться</Button>
      </Form>
    </div>
  </div>
</template>

<script setup lang="ts">
import { ref } from 'vue';
import { useRouter } from 'vue-router';
import Repository from '@/api/Auth/AuthRepository';
import { Field, Form } from 'vee-validate';
import * as yup from 'yup';
import { Button, InputText, Message } from 'primevue';

const router = useRouter();
const name = ref('');
const email = ref('');
const password = ref('');
const password_confirmation = ref('');
const errors = ref({});

const schema = yup.object({
    name: yup.string().required(),
    email: yup.string().email().required(),
    password: yup.string().required(),
    password_confirmation: yup.string().required(),
})

const onRegister = async (values: any) => {
  console.log(values);
  console.log(123);
  return;
    try {
        const { data } = await Repository.register(name.value, email.value, password.value, password_confirmation.value);
        if (data.status === 'success') {
            router.push({ name: 'home' });
        }
    } catch (error: any) {
        if (error.response && error.response.data.errors) {
            errors.value = error.response.data.errors;
        }
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
</style>
