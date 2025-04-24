<template>
  <div>
    <div class="flex flex-column row-gap-2">
      <div>{{ trainingOption?.name.ru }}</div>
      <div>{{ trainingOption?.description.ru }}</div>
    </div>
    <Divider></Divider>
    <div class="flex flex-column gap-3">
      <Exercise
        v-for="(exercise, index) in exercises"
        :key="index"
        :number="index + 1" :exercise="exercise"
        @save="saveExerciseAnswer($event, exercise.id)"
      />
      <ProgressSpinner v-if="isLoading"></ProgressSpinner>
    </div>
    <Divider></Divider>
    <div class="flex justify-content-center">
      <Button @click="saveAnswers">Отправить</Button>
    </div>
    <Toast position="bottom-right" />
    <ConfirmDialog :draggable="false" />
  </div>
</template>

<script setup lang="ts">
import TrainingOptionRepository from '@/api/TrainingOption/TrainingOptionRepository';
import { onMounted, ref } from 'vue';
import Exercise from '@/components/Exercise/Exercise.vue';
import { onBeforeRouteLeave, useRoute } from 'vue-router';
import { Button, Toast, useToast, Divider, useConfirm, ConfirmDialog, ProgressSpinner } from 'primevue';
import { IExercise } from '@/components/Exercise/Exercise.types';

const router = useRoute();
const toast = useToast();
const confirm = useConfirm();

const isLoading = ref(true);
const isSolved = ref(false);

const exercises = ref<Array<IExercise>>([]);
const trainingOption = ref();
const hash = router.params.hash as string;
onMounted(async () => {
  await initTrainingOption();
})

const solvedExercises = ref<Array<{exerciseId: number, answer: string}>>([]);

const initTrainingOption = async () => {
  isLoading.value = true;
  const { data } = await TrainingOptionRepository.getExercises(hash);
  exercises.value = data.exercises;
  trainingOption.value = data.trainingOption;
  isLoading.value = false;
}

const saveExerciseAnswer = (data: {answer: string}, id: number) => {
  const exercise = solvedExercises.value.find(exercise => exercise.exerciseId === id)
  if (exercise) {
    exercise.answer = data.answer;
  } else{
    solvedExercises.value.push({
      exerciseId: id,
      ...data
    });
  }
}

onBeforeRouteLeave((to, from, next) => {
  if (solvedExercises.value.length > 0 && !isSolved.value) {
    confirm.require({
      message: 'Вы уверены что хотите уйти? Ваши изменения не будут сохранены.',
      header: 'Подтверждение',
      icon: 'pi pi-exclamation-triangle',
      rejectProps: {
        label: 'Нет',
        severity: 'secondary'
      },
      acceptLabel: 'Да',
      accept: () => {
        next();
      }
    })
  } else {
    next();
  }
})

const saveAnswers = async () => {
  if (solvedExercises.value.length === 0) {
    toast.add({
      detail: "Вы не ответили ни на один вопрос",
      severity: "error",
      life: 3000,
      summary: "Error",
    });
    return;
  }
  confirm.require({
      message: 'Вы уверены что хотите сохранить ответ?',
      header: 'Подтверждение',
      icon: 'pi pi-exclamation-triangle',
      rejectProps: {
        label: 'Нет',
        severity: 'secondary'
      },
      acceptLabel: 'Да',
      accept: async () => {
        try{
          await TrainingOptionRepository.saveAnswers(solvedExercises.value, hash);
          isSolved.value = true;
        } catch {
          toast.add({
            detail: "Something wrong",
            severity: "error",
            life: 3000,
            summary: "Error",
          });
        } 
      }
    })
}
</script>