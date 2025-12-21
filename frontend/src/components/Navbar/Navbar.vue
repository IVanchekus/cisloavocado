<template>
  <div class="card">
    <Menubar :model="menuItems" class="menubar" :pt="passThrough">
      <template #start>
        <Logo @click="router.push({ name: 'home' })"></Logo>
      </template>
      <template #end>
        <ButtonGroup>
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
import { computed, ref } from "vue";
import Logo from "@/assets/logo.svg";
import router from "@/router/index";
import { useAuthStore } from "@/store/authStore";
import { useNavsStore } from "@/store/navsStore";

const authStore = useAuthStore();
const navsStore = useNavsStore();

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

const menuMainItems = computed(() => {
  const all = (navsStore.navs as any[]) ?? [];
  const byId = new Map<number, any>();
  all.forEach((n) => byId.set(n.id, n));

  const childrenByParent = new Map<number, any[]>();
  all.forEach((n) => {
    if (!n.parent_id) return;
    const pid = Number(n.parent_id);
    const arr = childrenByParent.get(pid) ?? [];
    arr.push(n);
    childrenByParent.set(pid, arr);
  });

  const roots = all.filter((n) => !n.parent_id);

  const buildItem = (nav: any): any => {
    const kids = childrenByParent.get(Number(nav.id)) ?? [];
    const label = nav.label?.ru ?? nav.label?.en ?? nav.name;

    if (kids.length) {
      return {
        label,
        items: kids.map((c) => buildItem(c)),
      };
    }

    if (!nav.component) {
      // родитель без компонента и без детей — не кликабелен
      return { label };
    }

    return {
      label,
      command: () => router.push({ name: nav.name }),
    };
  };

  return roots.map(buildItem);
});

const menuItems = computed(() => {
  // Главная навигация всегда берётся из БД. Кнопки "Войти/Профиль/Выйти" всегда справа.
  return [...menuMainItems.value];
});

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
