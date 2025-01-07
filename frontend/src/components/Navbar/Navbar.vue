<template>
  <div class="card">
    <Menubar
      :model="menuItems"
      class="menubar"
      :pt="passThrough"
    >
      <template #start>
        <Logo @click="router.push({ name: 'home' });"></Logo>
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
import router from "@/router/index";

const menuItems = ref([
  {
    label: "Главная",
    command: () => {
      router.push({ name: 'home' });
    }
  },
  {
    label: "Математика",
    command: () => {
      router.push({ name: 'maths' });
    }
  },
  {
    label: "Физика",
    command: () => {
      router.push({ name: 'physics' });
    }
  }
])

const passThrough = {
  rootlist: {
    style: {
      'margin': '0 auto'
    }
  }
}
</script>

<style scoped>
.menubar {
  height: 100px;
  padding: 23px 11.5%;
  background-color: #1E1E1E;
}

@media (max-width: 1248px) {
  .menubar {
    padding: 23px 15px;
  }
}

.menubar__item {
  margin: 0 auto;
}

:deep(.p-menubar-item-link) {
  color: white;
}

:deep(.p-menubar-item-link:hover) {
  color: var(--p-primary-200);
}

:deep(.p-menubar-end) {
  margin-left: 0;
}

:deep(.p-button-label), :deep(.p-menubar-item-label) {
  font-weight: 500;
}

:deep(.p-menubar) {
  border-radius: 0;
}

:deep(.p-button-label) {
  color: #1E1E1E;
}

:deep(.p-button) {
  background: var(--p-primary-400);
  border: 1px solid var(--p-primary-400);
}
</style>