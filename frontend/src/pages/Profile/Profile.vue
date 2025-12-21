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
      <template v-if="isStudentOnly">
        <div class="flex flex-column row-gap-2">
          <div class="font-semibold">Мои решённые варианты</div>
          <div v-if="solvedTrainingOptions.length" class="table-wrap">
            <table class="profile__table">
              <thead>
                <tr>
                  <th>Вариант</th>
                  <th>Оценка</th>
                  <th>Проверил</th>
                  <th>Дата</th>
                </tr>
              </thead>
              <tbody>
                <tr v-for="row in solvedTrainingOptions" :key="row.id">
                  <td style="max-width: 520px;">
                    {{ row.trainingOption?.name?.ru ?? '—' }}
                  </td>
                  <td>{{ row.mark ?? '—' }}</td>
                  <td>{{ row.verifier?.name ?? 'Не проверено' }}</td>
                  <td>{{ formatDate(row.verified_at ?? row.finished_at) }}</td>
                </tr>
              </tbody>
            </table>
          </div>
          <div v-else>
            Решённые варианты пока отсутствуют.
          </div>
        </div>
      </template>

      <template v-else>
        <div class="flex flex-column row-gap-2">
          <div class="font-semibold">Мои задания</div>
          <div class="mb-2">
            <Button
              v-if="canCreateExercises"
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
              v-if="canCreateTrainingOptions"
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
                  v-if="canCreateTrainingOptions"
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
      </template>
    </div>
  </div>
</template>

<script lang="ts" setup>
import ProfileRepository from '@/api/Profile/ProfileRepository';
import { useAuthStore } from '@/store/authStore';
import { useGlobalStore } from '@/store/globalStore';
import { InputText, Button } from 'primevue';
import { computed, onMounted, ref } from 'vue';
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
const solvedTrainingOptions = ref<any[]>([]);

const canCreateExercises = computed(() => {
  return isMyProfile.value && authStore.hasAnyRole(["admin", "teacher"]);
});

const canCreateTrainingOptions = computed(() => {
  return isMyProfile.value && authStore.hasAnyRole(["admin", "teacher"]);
});

const isStudentOnly = computed(() => {
  return (
    isMyProfile.value &&
    authStore.hasRole("student") &&
    !authStore.hasAnyRole(["admin", "teacher"])
  );
});

onMounted(() => {
  getUserData();

  if (isStudentOnly.value) {
    getSolvedTrainingOptions();
  } else {
    getTrainingOptionsByUser();
    getMyExercises();
  }
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

const getSolvedTrainingOptions = async () => {
  const { data } = await ProfileRepository.getMySolvedTrainingOptions();
  solvedTrainingOptions.value = data;
}

const formatDate = (iso: string | null | undefined) => {
  if (!iso) return '—';
  const d = new Date(iso);
  if (Number.isNaN(d.getTime())) return String(iso);
  return d.toLocaleString("ru-RU", {
    year: "numeric",
    month: "2-digit",
    day: "2-digit",
    hour: "2-digit",
    minute: "2-digit",
  });
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

  &__table {
    width: 100%;
    border-collapse: collapse;

    th,
    td {
      text-align: left;
      padding: 12px 10px;
      border-bottom: 1px solid var(--p-surface-200);
      vertical-align: top;
    }

    th {
      font-weight: 600;
      color: var(--p-surface-0);
      background: #1e1e1e;
      position: sticky;
      top: 0;
      z-index: 1;
    }
  }
}

.table-wrap {
  width: 100%;
  overflow: auto;
  max-height: 70vh;
  border: 1px solid var(--p-surface-200);
  border-radius: 10px;
}
</style>