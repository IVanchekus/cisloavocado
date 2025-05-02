<template>
  <div>
    <div class="profile__title">Мой профиль</div>
    <div class="flex align-items-start">
      <div class="flex flex-column row-gap-2 align-items-end">
        <div class="flex align-items-center gap-2">
          <div>Имя:</div>
          <InputText v-if="isMyProfile" :disabled="isDisabled" :value="user.name"/>
          <div v-else style="width: 100px;">{{ user.name }}</div>
        </div>
        <div class="flex align-items-center gap-2">
          <div>Email:</div>
          <InputText v-if="isMyProfile" :disabled="isDisabled" :value="user.email"/>
          <div v-else style="width: 100px;">{{ user.email }}</div>
        </div>
      </div>
    </div>
  </div>
</template>

<script lang="ts" setup>
import ProfileRepository from '@/api/Profile/ProfileRepository';
import { useAuthStore } from '@/store/authStore';
import { useGlobalStore } from '@/store/globalStore';
import { InputText } from 'primevue';
import { onMounted, ref } from 'vue';
import { useRoute } from 'vue-router';

const authStore = useAuthStore();
const globalStore = useGlobalStore();
const route = useRoute();

const isDisabled = ref(true);
const user = ref({});
const isMyProfile = ref(false);

onMounted(() => {
  getUserData();
});

const getUserData = async () => {
  if (authStore.user.id != route.params.id) {
    globalStore.changeLoading(true);
    const { data } = await ProfileRepository.getUserData(route.params.id as string);
    user.value = data;
    globalStore.changeLoading(false);
  } else{
    user.value = authStore.user;
    isMyProfile.value = true;
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