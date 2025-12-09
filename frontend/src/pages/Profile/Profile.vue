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
    <div v-if="isMyProfile" class="flex flex-column row-gap-4">
      <div class="flex flex-column row-gap-2">
        <div class="font-semibold">Мои задания</div>
        <div class="mb-2">
          <Button
            :label="'Создать задание'"
            @click="onCreateExercise"
          />
        </div>
        <div v-if="exercises && exercises.length">
          <div
            v-for="exercise in exercises"
            :key="exercise.id"
            class="flex align-items-center justify-content-between gap-2 mb-2"
          >
            <div class="flex-1" style="max-width: 600px; text-align: justify;">
              {{ exercise.question?.ru }}
            </div>
            <Button
              size="small"
              :label="'Редактировать'"
              @click="onEditExercise(exercise)"
            />
          </div>
        </div>
        <div v-else>
          Задания пока не созданы.
        </div>
      </div>

      <div class="flex flex-column row-gap-2">
        <div class="font-semibold">Мои варианты</div>
        <div class="mb-2">
          <Button
            :label="'Создать вариант'"
            @click="onCreateTrainingOption"
          />
        </div>
        <div v-if="trainingOptions && trainingOptions.length">
          <div
            v-for="item in trainingOptions"
            :key="item.id"
            class="flex align-items-center justify-content-between gap-2 mb-2"
          >
            <div class="flex-1" style="max-width: 600px; text-align: justify;">
              {{ item.name?.ru }}
            </div>
            <div class="flex gap-2">
              <Button
                size="small"
                :label="'Открыть'"
                @click="onClickTrainingOption(item)"
              />
              <Button
                size="small"
                :label="'Редактировать'"
                severity="secondary"
                @click="onEditTrainingOption(item)"
              />
            </div>
          </div>
        </div>
        <div v-else>
          Варианты пока не созданы.
        </div>
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
import type { ITrainingOption } from '@/pages/Informatics/Informatics.types';
import type { IExercise } from '@/components/Exercise/Exercise.types';

defineOptions({
  name: 'UserProfile',
});

type TUser = {
  id?: number;
  name?: string;
  email?: string;
};
const authStore = useAuthStore();
const globalStore = useGlobalStore();
const route = useRoute();

const isDisabled = ref(true);
const user = ref<TUser>({});
const isMyProfile = ref(false);
const trainingOptions = ref<Array<ITrainingOption>>([]);
const exercises = ref<Array<IExercise>>([]);

onMounted(() => {
  getUserData();
  getTrainingOptionsByUser();
  getMyExercises();
});

const getUserData = async () => {
  const authUser = authStore.user as TUser;
  if (String(authUser?.id) !== String(route.params.id)) {
    globalStore.changeLoading(true);
    const { data } = await ProfileRepository.getUserData(route.params.id as string);
    user.value = data;
    globalStore.changeLoading(false);
  } else{
    user.value = authUser;
    isMyProfile.value = true;
  }
}

const getTrainingOptionsByUser = async () => {
  const { data } = await InformaticsRepository.getTrainingOptionsByUser();
  trainingOptions.value = data;
}

const getMyExercises = async () => {
  const { data } = await ProfileRepository.getMyExercises();
  exercises.value = data;
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

const onEditExercise = (exercise: IExercise) => {
  router.push({
    name: 'exercise-edit',
    params: { id: exercise.id }
  });
}

const onCreateTrainingOption = () => {
  router.push({
    name: 'training-option-create'
  })
}

const onEditTrainingOption = (item: ITrainingOption) => {
  router.push({
    name: 'training-option-edit',
    params: { id: item.id }
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