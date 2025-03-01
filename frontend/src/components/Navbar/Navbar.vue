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
        <ButtonGroup v-if ="!isHiddenEnterItems">
          <!-- <Button label="Регистрация" />
          <Button label="Войти" @click="router.push({ name: 'login' })"/> -->
          <Button v-for="item in menuEnterItems" :key="item.label" :label="item.label" @click="item.command()"/>
        </ButtonGroup>
      </template>
    </Menubar>
  </div>
</template>

<script setup>
import { ButtonGroup, Button, Menubar } from 'primevue';
import { computed, ref } from 'vue';
import Logo from "@/assets/logo.svg";
import router from "@/router/index";

const menuMainItems = ref([
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

const menuEnterItems = ref([
  {
    label: "Регистрация",
    command: () => {
      router.push({ name: 'register' });
    }
  },
  {
    label: "Войти",
    command: () => {
      router.push({ name: 'login' });
    }
  }
])

const isHiddenEnterItems = ref(false);

const screenWidth = ref(window.innerWidth);
window.onresize = () => screenWidth.value = window.innerWidth; 

const menuItems = computed(() => {
  if (screenWidth.value < 961) {
    isHiddenEnterItems.value = true
    return [
      ...menuMainItems.value,
      ...menuEnterItems.value
    ]
  } else {
    isHiddenEnterItems.value = false
    return [
      ...menuMainItems.value
    ]
  }
});

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

@media (max-width: 961px) {
  .menubar {
    padding: 23px 15px;
  }
}

.menubar__item {
  margin: 0 auto;
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

:deep(.p-menubar-button) {
  margin-left: auto;
}
</style>