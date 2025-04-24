<template>
  <div>
    <Card>
      <template #title>Список доступных вариантов</template>
      <template #content>
        <div>
          <Button
            v-for="(item, index) in trainingOptions"
            :key="item.id"
            variant="link"
            @click="router.push({ name: 'training-option-detail', params: { hash: item.hash } })"
          >
            Вариант {{ index + 1 }}
          </Button>
        </div>
      </template>
    </Card>
  </div>
</template>

<script setup lang="ts">
import { Button, Card } from 'primevue';
import InformaticsRepository from '@/api/InformaticsRepository/InformaticsRepository';
import { onMounted, ref } from 'vue';
import { ITrainingOption } from './Informatics.types';
import router from '@/router';

const trainingOptions = ref<Array<ITrainingOption>>([]);

onMounted(async () => {
  const { data } = await InformaticsRepository.getTrainingOptions();
  trainingOptions.value = data;
})
</script>