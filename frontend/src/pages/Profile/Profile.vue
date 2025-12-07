<template>
  <div class="flex flex-column row-gap-3">
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
    <div>
      <div>
        <Button
          :label="'Создать задание'"
          @click="onCreateExercise"
        />
      </div>
      <div>
        <Button
          v-for="(item, index) in trainingOptions"
          :key="item.id"
          variant="text"
          @click="onClickTrainingOption(item)"
          :label="`Вариант ${index + 1}`"
          :severity="item.is_solved ? 'success' : 'primary'"
        />
      </div>
    </div>
  </div>
</template>

<script lang="ts" setup>
import ProfileRepository from '@/api/Profile/ProfileRepository';
import { useAuthStore } from '@/store/authStore';
import { useGlobalStore } from '@/store/globalStore';
import { InputText, Button } from 'primevue';
import { onMounted, ref } from 'vue';
import { useRoute } from 'vue-router';
import router from '@/router';
import InformaticsRepository from '@/api/InformaticsRepository/InformaticsRepository';

const authStore = useAuthStore();
const globalStore = useGlobalStore();
const route = useRoute();

const isDisabled = ref(true);
const user = ref({});
const isMyProfile = ref(false);
const trainingOptions = ref();

onMounted(() => {
  getUserData();
  getTrainingOptionsByUser();
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

const getTrainingOptionsByUser = async () => {
  const { data } = await InformaticsRepository.getTrainingOptionsByUser();
  trainingOptions.value = data;
}

const onClickTrainingOption = (item: ITrainingOption) => {
  router.push({
    name: 'training-option-detail', params: { hash: item.hash }
  })
}

const onCreateExercise = () => {
  router.push({
    name: 'exercise-create'
  })
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