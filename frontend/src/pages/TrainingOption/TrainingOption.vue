<template>
  <div>
    <div class="flex flex-column gap-3">
      <Exercise
        v-for="(exercise, index) in exercises"
        :key="index"
        :number="index + 1" :exercise="exercise"
        @save="saveExerciseAnswer($event, exercise.id)"
      />
    </div>
    <div class="flex justify-content-center">
      <Button @click="saveAnswers">Отправить</Button>
    </div>
    <Toast position="bottom-right" />
  </div>
</template>

<script setup lang="ts">
import TrainingOptionRepository from '@/api/TrainingOption/TrainingOptionRepository';
import { onMounted, ref } from 'vue';
import Exercise from '@/components/Exercise/Exercise.vue';
import { useRoute } from 'vue-router';
import { Button, Toast, useToast } from 'primevue';

const router = useRoute();
const toast = useToast();

const exercises = ref([]);
const hash = router.params.hash as string;
onMounted(async () => {
  await getAllExercises();
})

const solvedExercises = ref<Array<{exerciseId: number, answer: string}>>([]);

const getAllExercises = async () => {
  const { data } = await TrainingOptionRepository.getExercises(hash);
  exercises.value = data;
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

const saveAnswers = async () => {
  try{
    await TrainingOptionRepository.saveAnswers(solvedExercises.value, hash);
  } catch {
    toast.add({
      detail: "Something wrong",
      severity: "error",
      life: 3000,
      summary: "Error",
    });
  } 
}
</script>