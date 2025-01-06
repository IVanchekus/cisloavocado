<template>
  <div class="card">
    <Menubar
      :model="menuItems"
      class="menubar"
      :pt="{ rootlist: { style: { 'margin': '0 auto' } } }"
    >
      <template #start>
        <Logo></Logo>
      </template>
      <template #end>
        <ButtonGroup>
          <Button label="Регистрация" />
          <Button label="Войти" />
        </ButtonGroup>
      </template>
    </Menubar>
  </div>
</template>

<script setup>
import { ButtonGroup, Button, Menubar } from 'primevue';
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

<style scoped>
.menubar {
  height: 100px;
  padding: 23px 11.5%;
}

@media (max-width: 1248px) {
  .menubar {
    padding: 23px 15px;
  }
}

.menubar__item {
  margin: 0 auto;
}

:deep(.p-menubar-item-link:hover) {
  color: #F9EC78;
}

:deep(.p-menubar-end) {
  margin-left: 0;
}

:deep(.p-button-label), :deep(.p-menubar-item-label) {
  font-weight: 500;
}
</style>