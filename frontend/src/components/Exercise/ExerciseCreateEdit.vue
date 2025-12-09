<template>
  <div>
    <Form
      :validation-schema="schema"
      :initial-values="initialValues"
      @submit="onSave"
      class="flex flex-column row-gap-2"
    >
      <div v-for="item in fields" :key="item.name">
        <div>
          {{ item.label }}
        </div>
        <Field :name="item.name" :type="item.type" v-slot="{ field, errors }">
          <div class="flex flex-column gap-2 align-items-stretch">
            <Textarea
              v-if="item.type === 'textarea'"
              :invalid="errors.length !== 0"
              v-bind="field"
              autoResize
              rows="5"
              class="w-full"
            />
            <InputText
              v-else
              :invalid="errors.length !== 0"
              v-bind="field"
              class="w-full"
            />
          </div>
        </Field>
      </div>
      <Button type="submit" class="max-w-min">Сохранить</Button>
    </Form>
  </div>
</template>

<script lang="ts" setup>
import { InputText, Button, Textarea } from 'primevue';
import { Field, Form } from "vee-validate";
import * as yup from 'yup';
import { computed } from 'vue';

type TMode = 'create' | 'edit';

type TFormValues = {
  question: string;
  solution: string;
  answer: string;
};

const props = defineProps<{
  mode: TMode;
  initialValues?: TFormValues;
}>();

const schema = yup.object({
  question: yup.string().required(),
  solution: yup.string().required(), 
  answer: yup.string().required()
})
const emit = defineEmits(['save']);

// eslint-disable-next-line @typescript-eslint/no-explicit-any
const onSave = (value: any) => {
  emit('save', value as TFormValues);
}

const initialValues = computed<TFormValues | undefined>(() => props.initialValues);

const fields = [
  {
    name: "question",
    type: 'textarea',
    label: 'Вопрос'
  },
  {
    name: "solution",
    type: 'textarea',
    label: 'Решение'
  },
  {
    name: "answer",
    type: 'text',
    label: 'Ответ'
  }
];
</script>