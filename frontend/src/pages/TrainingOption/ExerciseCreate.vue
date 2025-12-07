<template>
  <ExerciseCreateEdit :mode="'create'" @save="onSave" />
</template>

<script lang="ts" setup>
import TrainingOptionRepository from '@/api/TrainingOption/TrainingOptionRepository';
import ExerciseCreateEdit from '@/components/Exercise/ExerciseCreateEdit.vue';

const onSave = async (value: any) => {
  await TrainingOptionRepository.createExercise(value)
    .then(() => {
      
    });
}
</script>