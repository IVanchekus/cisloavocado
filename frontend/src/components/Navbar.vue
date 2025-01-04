<template>
  <div class="card">
    <Menubar :model="menuItems" style="height: 100px;">
      <template #start>
        <Logo></Logo>
      </template>
      <template #item="{ item }">
        {{ item.label }}
      </template>
      <template #end>
        <router-link to="/login">
          <Button label="Войти" />
        </router-link>
      </template>
    </Menubar>
  </div>
</template>

<script setup>
import Menubar from 'primevue/menubar';
import { Button } from 'primevue';
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