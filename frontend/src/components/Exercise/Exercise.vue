<template>
  <div class="exercise flex align-items-start gap-3">
    <div class="exercise__number-box">
      №{{ number }}
    </div>
    <div class="flex flex-column row-gap-2">
      <div style="text-align: justify;">
        {{ exercise.question.ru }}
      </div>
      <template v-if="exercise.solution">
        <div class="flex flex-column row-gap-1 align-items-start">
          <Button
            variant="link"
            :label="isCheckSolution ? 'Скрыть решение' : 'Показать решение'"
            @click="isCheckSolution = !isCheckSolution"
            class="p-0"
          />
          <div v-if="isCheckSolution" style="text-align: justify;">
            {{ exercise.solution?.ru }}
            Ответ: {{ exercise.answer }}
          </div>
        </div>
      </template>
      <Form :validation-schema="schema" @submit="emit('save', $event)">
        <div v-for="item in fields" :key="item.name">
          <Field :name="item.name" :type="item.type" v-slot="{ field, errors }">
            <div class="flex gap-2">
              <InputText :invalid="errors.length !== 0" v-bind="field" />
              <Button type="submit">Сохранить</Button>
            </div>
            <div style="height: 17px">
              <Transition name="fade-message">
                <Message
                  v-if="errors.length !== 0"
                  :name="field.name"
                  size="small"
                  severity="error"
                  variant="simple"
                  >{{ errors[0] }}</Message
                >
              </Transition>
            </div>
          </Field>
        </div>
      </Form>
    </div>
  </div>
</template>

<script setup lang="ts">
import { ref } from 'vue';
import { IProps } from './Exercise.types';
import { InputText, Button } from 'primevue';
import { Field, Form } from "vee-validate";
import * as yup from 'yup';
import { emit } from 'process';

const props = defineProps<IProps>();
const emit = defineEmits(['save']);

const isCheckSolution = ref(false);

const schema = yup.object({
  answer: yup.string().required()
})

const fields = [
  {
    name: 'answer',
    type: 'text',
    label: 'Ответ'
  }
]

</script>

<style scoped lang="scss">
.exercise {

  &__number-box {
    border: 1px solid var(--p-form-field-color);
    padding: 5px 10px;
    display: inline-block;
    white-space: nowrap;
  }
}

.fade-message-enter-active,
.fade-message-leave-active {
  transition:
    opacity 0.3s ease,
    transform 0.3s ease;
}
.fade-message-enter-from,
.fade-message-leave-to {
  opacity: 0;
  transform: translateY(-4px);
}
</style>