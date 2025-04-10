<template>
  <div>Здесь будет регистрацию
    <Button @click="test">123</Button>
  </div>
</template>

<script setup lang="ts">
import { Button } from 'primevue';
import axios from 'axios';

const test = async () => {
  const response = await axios.get('http://localhost/backend/api/users', { });
}
</script>