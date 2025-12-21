<template>
  <ExerciseCreateEdit
    v-if="formValues"
    :mode="'edit'"
    :initial-values="formValues"
    @save="onSave"
  />
</template>

<script lang="ts" setup>
import { onMounted, ref } from 'vue';
import { useRoute, useRouter } from 'vue-router';
import TrainingOptionRepository from '@/api/TrainingOption/TrainingOptionRepository';
import ExerciseCreateEdit from '@/components/Exercise/ExerciseCreateEdit.vue';

type TFormValues = {
  question: string;
  solution: string;
  answer: string;
};

const route = useRoute();
const router = useRouter();

const formValues = ref<TFormValues | undefined>();

onMounted(async () => {
  const id = route.params.id as string;
  const { data } = await TrainingOptionRepository.getExercise(id);

  formValues.value = {
    question: data.question?.ru ?? '',
    solution: data.solution?.ru ?? '',
    answer: data.answer ?? '',
  };
});

const onSave = async (value: TFormValues) => {
  const id = route.params.id as string;
  await TrainingOptionRepository.updateExercise(id, value);
  router.back();
};
</script>


