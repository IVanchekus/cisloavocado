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
  </div>
</template>

<script setup lang="ts">
import InformaticsRepository from '@/api/Informatics/InformaticsRepository';
import { onMounted, ref } from 'vue';
import Exercise from '@/components/Exercise/Exercise.vue';

const exercises = ref([]);

onMounted(async () => {
  await getAllExercises();
})

const solvedExercises = ref<Array<{exerciseId: number, answer: string}>>([]);

const getAllExercises = async () => {
  const { data } = await InformaticsRepository.getAllExercises();
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
  await InformaticsRepository.saveAnswers(solvedExercises.value);
}
</script>