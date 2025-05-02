<template>
  <div class="card">
    <Menubar :model="menuItems" class="menubar" :pt="passThrough">
      <template #start>
        <Logo @click="router.push({ name: 'home' })"></Logo>
      </template>
      <template #end>
        <ButtonGroup v-if="!isHiddenEnterItems">
          <template v-if="!authStore.isAuth">
            <Button
              v-for="(item, index) in menuEnterItems"
              :key="index"
              :label="item.label"
              @click="item.command()"
            />
          </template>
          <template v-else>
            <Button
              v-for="(item, index) in menuAuthItems"
              :key="index"
              :label="item.label"
              @click="item.command()"
            />
          </template>
        </ButtonGroup>
      </template>
    </Menubar>
  </div>
</template>

<script setup lang="ts">
import { ButtonGroup, Button, Menubar } from "primevue";
import { computed, onMounted, ref } from "vue";
import Logo from "@/assets/logo.svg";
import router from "@/router/index";
import { useAuthStore } from "@/store/authStore";
import { useNavsStore } from "@/store/navsStore";

const authStore = useAuthStore();
const navsStore = useNavsStore();

onMounted(() => {
  navsStore.navs.forEach((nav) => {
    menuMainItems.value.push({
      label: nav.label.ru,
      command: () => {
        router.push({ name: nav.name });
      }
    })
  })
})

const menuMainItems = ref([]);

const menuEnterItems = ref([
  {
    label: "Регистрация",
    command: () => {
      router.push({ name: "register" });
    },
  },
  {
    label: "Войти",
    command: () => {
      router.push({ name: "login" });
    },
  },
]);

const menuAuthItems = ref([
  {
    label: "Профиль",
    command: () => {
      router.push({ name: "profile", params: { id: authStore.user.id } });
    },
  },
  {
    label: "Выйти",
    command: () => {
      authStore.logout();
    },
  },
]);

const isHiddenEnterItems = ref(false);

const screenWidth = ref(window.innerWidth);
window.onresize = () => (screenWidth.value = window.innerWidth);

const menuItems = computed(() => {
  if (screenWidth.value < 961) {
    onUpdateIsHiddenEnterItems(true);
    if (authStore.isAuth) {
      return [...menuMainItems.value, ...menuAuthItems.value];
    }
    return [...menuMainItems.value, ...menuEnterItems.value];
  } else {
    onUpdateIsHiddenEnterItems(false);
    return [...menuMainItems.value];
  }
});

const onUpdateIsHiddenEnterItems = (value: boolean) => {
  isHiddenEnterItems.value = value
}

const passThrough = {
  rootlist: {
    style: {
      margin: "0 auto",
    },
  },
};
</script>

<style scoped>
.menubar {
  height: 100px;
  padding: 23px 11.5%;
  background-color: #1e1e1e;
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

:deep(.p-button-label),
:deep(.p-menubar-item-label) {
  font-weight: 500;
}

:deep(.p-menubar) {
  border-radius: 0;
}

:deep(.p-button-label) {
  color: #1e1e1e;
}

:deep(.p-button) {
  background: var(--p-primary-400);
  border: 1px solid var(--p-primary-400);
}

:deep(.p-menubar-button) {
  margin-left: auto;
}
</style>
