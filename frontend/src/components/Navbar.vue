<template>
  <div class="card">
    <Menubar :model="menuItems">
      <template #start>
        <Logo></Logo>
      </template>
    </Menubar>
  </div>
</template>

<script setup>
import Menubar from 'primevue/menubar';
import { ref } from 'vue';
import Logo from "@/assets/logo.svg";

const menuItems = ref([
  {
    label: "Главная",
  },
  {
    label: "Физика"
  },
  {
    label: "Информатика"
  }
])
</script>