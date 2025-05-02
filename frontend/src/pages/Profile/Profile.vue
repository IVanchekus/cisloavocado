<template>
  <div>
    <div class="profile__title">Мой профиль</div>
    <div class="flex align-items-start">
      <div class="flex flex-column row-gap-2 align-items-end">
        <div class="flex align-items-center gap-2">
          <div>Имя:</div>
          <InputText :disabled="isDisabled" :value="authStore.user.name"/>
        </div>
        <div class="flex align-items-center gap-2">
          <div>Email:</div>
          <InputText :disabled="isDisabled" :value="authStore.user.email"/>
        </div>
      </div>
    </div>
  </div>
</template>

<script lang="ts" setup>
import ProfileRepository from '@/api/Profile/ProfileRepository';
import { useAuthStore } from '@/store/authStore';
import { InputText } from 'primevue';
import { onMounted, ref } from 'vue';
import { useRoute } from 'vue-router';

const authStore = useAuthStore();
const route = useRoute();
const isDisabled = ref(true);

onMounted(() => {
  getUserData();
});

const getUserData = async () => {
  if (authStore.user.id != route.params.id) {
    await ProfileRepository.getUserData(route.params.id as string);
  }
}
</script>

<style scoped lang="scss">
.profile {
  &__title {
    font-weight: 600;
    font-size: 70px;
  }
}
</style>