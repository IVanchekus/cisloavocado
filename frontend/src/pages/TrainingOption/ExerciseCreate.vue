<template>
  <ExerciseCreateEdit :mode="'create'" @save="onSave" />
</template>

<script lang="ts" setup>
import router from '@/router';
import { useAuthStore } from '@/store/authStore';
import TrainingOptionRepository from '@/api/TrainingOption/TrainingOptionRepository';
import ExerciseCreateEdit from '@/components/Exercise/ExerciseCreateEdit.vue';

type TUser = {
  id?: number;
};

const authStore = useAuthStore();

const onSave = async (value: unknown) => {
  await TrainingOptionRepository.createExercise(value);

  const user = authStore.user as TUser;
  const userId = user.id;
  if (userId) {
    router.push({
      name: 'profile',
      params: { id: userId },
    });
  }
}
</script>