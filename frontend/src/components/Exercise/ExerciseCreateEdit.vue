<template>
  <div>
    <Form :validation-schema="schema" @submit="onSave" class="flex flex-column row-gap-2">
      <div v-for="item in fields" :key="item.name">
        <div>
          {{ item.label }}
        </div>
        <Field :name="item.name" :type="item.type" v-slot="{ field, errors }">
          <div class="flex gap-2 align-items-center">
            <InputText :invalid="errors.length !== 0" v-bind="field" />
          </div>
        </Field>
      </div>
      <Button type="submit" class="max-w-min">Сохранить</Button>
    </Form>
  </div>
</template>

<script lang="ts" setup>
import { InputText, Button } from 'primevue';
import { Field, Form } from "vee-validate";
import * as yup from 'yup';
import { ref } from 'vue';

const schema = yup.object({
  question: yup.string().required(),
  solution: yup.string().required(), 
  answer: yup.string().required()
})
const emit = defineEmits(['save']);

const onSave = (value: any) => {
  emit('save', value);
}

const fields = [
  {
    name: "question",
    type: 'text',
    label: 'Вопрос'
  },
  {
    name: "solution",
    type: 'text',
    label: 'Решение'
  },
  {
    name: "answer",
    type: 'text',
    label: 'Ответ'
  }
]

const isSaved = ref(false);

const onShowIsSave = async () => {
  isSaved.value = true

  setTimeout(() => {
    isSaved.value = false
  }, 3000);
}
</script>