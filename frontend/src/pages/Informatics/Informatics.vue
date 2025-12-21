<template>
  <div>
    <Card>
      <template #title>Список доступных вариантов</template>
      <template #content>
        <div>
          <Button
            v-for="(item, index) in trainingOptions"
            :key="item.id"
            variant="text"
            @click="onClickTrainingOption(item)"
            :label="`Вариант ${index + 1}`"
            :severity="item.is_solved ? 'success' : 'primary'"
          />
        </div>
      </template>
    </Card>
    <ConfirmDialog :draggable="false" />
  </div>
</template>

<script setup lang="ts">
import { Button, Card, useConfirm, ConfirmDialog } from 'primevue';
import InformaticsRepository from '@/api/InformaticsRepository/InformaticsRepository';
import { onMounted, ref } from 'vue';
import { ITrainingOption } from './Informatics.types';
import router from '@/router';

const confirm = useConfirm();
const trainingOptions = ref<Array<ITrainingOption>>([]);

onMounted(async () => {
  const { data } = await InformaticsRepository.getTrainingOptions();
  trainingOptions.value = data;
})

const onClickTrainingOption = (item: ITrainingOption) => {
  if (!item.is_solved) {
    router.push({
      name: 'training-option-detail', params: { hash: item.hash }
    })
    return;
  }

  confirm.require({
    message: 'Вы уверены, что хотите еще раз пройти этот вариант?',
    header: 'Подтверждение',
    icon: 'pi pi-exclamation-triangle',
    rejectProps: {
      label: 'Нет',
      severity: 'secondary'
    },
    acceptLabel: 'Да',
    accept: () => {
      router.push({
        name: 'training-option-detail', params: { hash: item.hash }
      })
    }
  })
}
</script>