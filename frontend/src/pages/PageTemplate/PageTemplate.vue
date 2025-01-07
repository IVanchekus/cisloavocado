<template>
  <div class="page-template">
    <slot></slot>
  </div>
</template>

<script setup>
import { defineSlots } from 'vue';
import { ISlots } from './PageTemplate.types';

const slots = defineSlots<ISlots>();
</script>

<style scoped>
.page-template {
  height: 100px;
  padding: 19px 11.5%;
}

@media (max-width: 1248px) {
  .page-template {
    padding: 19px 15px;
  }
}
</style>