<template>
  <div>Здесь будет регистрацию
    <Button @click="test">123</Button>
  </div>
</template>

<script setup lang="ts">
import Repository from '@/api/Auth/AuthRepository';
import { Button } from 'primevue';

const test = async () => {
  await Repository.user();
}
</script>