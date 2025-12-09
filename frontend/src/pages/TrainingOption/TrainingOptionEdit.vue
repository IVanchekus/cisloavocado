<template>
  <div class="flex flex-column row-gap-3" v-if="isLoaded">
    <div class="text-xl font-semibold">Редактирование варианта</div>
    <div class="flex flex-column row-gap-2" style="max-width: 600px;">
      <div>
        <div class="mb-1">Название варианта</div>
        <InputText v-model="name" class="w-full" />
      </div>
      <div>
        <div class="mb-1">Описание</div>
        <Textarea v-model="description" class="w-full" auto-resize rows="4" />
      </div>
    </div>

    <div class="flex flex-column row-gap-2" style="max-width: 600px;">
      <div class="font-semibold">Задания в варианте</div>
      <div class="flex gap-2 align-items-center">
        <Dropdown
          v-model="selectedExerciseId"
          :options="exerciseOptions"
          option-label="label"
          option-value="value"
          placeholder="Выберите задание"
          class="w-full"
        />
        <Button
          :disabled="!selectedExerciseId"
          label="Добавить"
          @click="onAddExercise"
        />
      </div>
      <div v-if="selectedExercises.length">
        <div
          v-for="exercise in selectedExercises"
          :key="exercise.id"
          class="flex align-items-center justify-content-between gap-2 mb-2"
        >
          <div class="flex-1" style="text-align: justify;">
            {{ exercise.question?.ru }}
          </div>
          <div class="flex gap-2">
            <Button
              size="small"
              label="Удалить из варианта"
              severity="danger"
              @click="onRemoveExercise(exercise.id)"
            />
            <Button
              size="small"
              label="Редактировать задание"
              @click="onEditExercise(exercise.id)"
            />
          </div>
        </div>
      </div>
      <div v-else class="text-color-secondary">
        Вариант пока без заданий.
      </div>
    </div>

    <div>
      <Button label="Сохранить изменения" @click="onSave" />
    </div>
  </div>
</template>

<script lang="ts" setup>
import { onMounted, ref, computed } from 'vue';
import { useRoute, useRouter } from 'vue-router';
import ProfileRepository from '@/api/Profile/ProfileRepository';
import TrainingOptionRepository from '@/api/TrainingOption/TrainingOptionRepository';
import type { IExercise } from '@/components/Exercise/Exercise.types';
import { InputText, Textarea, Button, Dropdown } from 'primevue';

const route = useRoute();
const router = useRouter();

const name = ref('');
const description = ref('');

const availableExercises = ref<Array<IExercise>>([]);
const selectedExercises = ref<Array<IExercise>>([]);
const selectedExerciseId = ref<number | null>(null);
const isLoaded = ref(false);

onMounted(async () => {
  const [{ data: exercisesData }, { data: optionData }] = await Promise.all([
    ProfileRepository.getMyExercises(),
    TrainingOptionRepository.getTrainingOption(route.params.id as string),
  ]);

  availableExercises.value = exercisesData;

  name.value =
    typeof optionData.name === 'string'
      ? optionData.name
      : optionData.name?.ru ?? '';
  description.value =
    typeof optionData.description === 'string'
      ? optionData.description
      : optionData.description?.ru ?? '';

  selectedExercises.value = optionData.exercises ?? [];
  isLoaded.value = true;
});

const exerciseOptions = computed(() =>
  availableExercises.value.map((exercise) => ({
    label: exercise.question?.ru ?? `Задание #${exercise.id}`,
    value: exercise.id,
  })),
);

const onAddExercise = () => {
  if (!selectedExerciseId.value) return;
  const exists = selectedExercises.value.some(
    (e) => e.id === selectedExerciseId.value,
  );
  if (exists) return;
  const exercise = availableExercises.value.find(
    (e) => e.id === selectedExerciseId.value,
  );
  if (exercise) {
    selectedExercises.value.push(exercise);
  }
};

const onRemoveExercise = (exerciseId: number) => {
  selectedExercises.value = selectedExercises.value.filter(
    (e) => e.id !== exerciseId,
  );
};

const onEditExercise = (exerciseId: number) => {
  router.push({
    name: 'exercise-edit',
    params: { id: exerciseId },
  });
};

const onSave = async () => {
  if (!name.value.trim()) {
    return;
  }

  const id = route.params.id as string;

  await TrainingOptionRepository.updateTrainingOption(id, {
    name: name.value.trim(),
    description: description.value.trim() || null,
    exercise_ids: selectedExercises.value.map((e) => e.id),
  });

  router.back();
};
</script>


