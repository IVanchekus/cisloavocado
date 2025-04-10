<template>
    <div class="register-container">
        <h2>Регистрация</h2>
        <form @submit.prevent="register">
            <div>
                <label>Имя</label>
                <input v-model="name" type="text" />
                <span v-if="errors.name">{{ errors.name[0] }}</span>
            </div>
            <div>
                <label>Email</label>
                <input v-model="email" type="email" />
                <span v-if="errors.email">{{ errors.email[0] }}</span>
            </div>
            <div>
                <label>Пароль</label>
                <input v-model="password" type="password" />
                <span v-if="errors.password">{{ errors.password[0] }}</span>
            </div>
            <div>
                <label>Подтвердите пароль</label>
                <input v-model="password_confirmation" type="password" />
            </div>
            <button type="submit">Зарегистрироваться</button>
        </form>
    </div>
</template>

<script setup lang="ts">
import { ref } from 'vue';
import axios from 'axios';
import { useRouter } from 'vue-router';

const router = useRouter();
const name = ref('');
const email = ref('');
const password = ref('');
const password_confirmation = ref('');
const errors = ref({});

const register = async () => {
    errors.value = {};
    try {
        const response = await axios.post('http://localhost/backend/api/register', {
            name: name.value,
            email: email.value,
            password: password.value,
            password_confirmation: password_confirmation.value,
        });

        localStorage.setItem('token', response.data.token);
        router.push('/dashboard'); // Перенаправление после успешной регистрации
    } catch (error: any) {
        if (error.response && error.response.data.errors) {
            errors.value = error.response.data.errors;
        }
    }
};
</script>

<style scoped>
.register-container {
    max-width: 400px;
    margin: auto;
    padding: 20px;
    border: 1px solid #ddd;
    border-radius: 5px;
}
</style>
