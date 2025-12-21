<template>
  <div class="flex flex-column row-gap-3">
    <div class="admin-users__title">Создание пользователей и роли</div>

    <Card>
      <template #title>Создать пользователя</template>
      <template #content>
        <div class="flex flex-column row-gap-2" style="max-width: 520px;">
          <div class="flex flex-column row-gap-1">
            <div class="text-sm">Имя</div>
            <InputText v-model="createForm.name" class="w-full" />
          </div>
          <div class="flex flex-column row-gap-1">
            <div class="text-sm">Email</div>
            <InputText v-model="createForm.email" class="w-full" />
          </div>
          <div class="flex flex-column row-gap-1">
            <div class="text-sm">Пароль</div>
            <Password v-model="createForm.password" :feedback="false" toggleMask class="w-full" inputClass="w-full" />
          </div>
          <div class="flex flex-column row-gap-1">
            <div class="text-sm">Роли</div>
            <MultiSelect
              v-model="createForm.role_ids"
              :options="rolesOptions"
              optionLabel="label"
              optionValue="id"
              display="chip"
              placeholder="Выберите роли"
              class="w-full"
            />
          </div>

          <div class="flex gap-2 mt-2">
            <Button label="Создать" :loading="isCreating" @click="onCreateUser" />
            <Button label="Обновить список" severity="secondary" @click="loadUsers" />
          </div>

          <div v-if="error" class="mt-2 p-2 border-1 border-round" style="border-color: var(--p-red-300);">
            {{ error }}
          </div>
        </div>
      </template>
    </Card>

    <Card>
      <template #title>Пользователи</template>
      <template #content>
        <div class="flex flex-column row-gap-2">
          <div class="flex justify-content-between align-items-center gap-3 flex-wrap">
            <div class="text-sm text-500">
              Всего: <span class="font-semibold">{{ users.length }}</span>
            </div>
            <InputText v-model="query" placeholder="Поиск по имени или email" />
          </div>

          <div class="table-wrap">
            <table class="admin-users__table">
              <thead>
                <tr>
                  <th>Имя</th>
                  <th>Email</th>
                  <th>Роли</th>
                  <th style="width: 140px;">Действия</th>
                </tr>
              </thead>
              <tbody>
                <tr v-for="u in filteredUsers" :key="u.id">
                  <td>{{ u.name }}</td>
                  <td>{{ u.email }}</td>
                  <td>
                    <MultiSelect
                      v-model="u._role_ids"
                      :options="rolesOptions"
                      optionLabel="label"
                      optionValue="id"
                      display="chip"
                      placeholder="Роли"
                      class="w-full"
                    />
                  </td>
                  <td>
                    <Button
                      size="small"
                      label="Сохранить"
                      :loading="u._saving"
                      @click="onSaveUserRoles(u)"
                    />
                  </td>
                </tr>
                <tr v-if="!filteredUsers.length">
                  <td colspan="4" class="text-center text-500 py-3">
                    Пользователи не найдены.
                  </td>
                </tr>
              </tbody>
            </table>
          </div>
        </div>
      </template>
    </Card>
  </div>
</template>

<script setup lang="ts">
import { Card, Button, InputText, MultiSelect, Password } from "primevue";
import { computed, onMounted, reactive, ref } from "vue";
import { useGlobalStore } from "@/store/globalStore";
import DictionariesRepository from "@/api/Dictionaries/DictionariesRepository";
import UsersRepository from "@/api/Users/UsersRepository";

type TRoleDict = {
  id: number;
  slug: string;
  title: { ru?: string; en?: string };
};

type TUserRow = {
  id: number;
  name: string;
  email: string;
  roles?: Array<{ id: number; slug: string; title: any }>;
  _role_ids: number[];
  _saving?: boolean;
};

const globalStore = useGlobalStore();

const roles = ref<TRoleDict[]>([]);
const users = ref<TUserRow[]>([]);
const query = ref("");
const error = ref<string | null>(null);
const isCreating = ref(false);

const createForm = reactive({
  name: "",
  email: "",
  password: "",
  role_ids: [] as number[],
});

const rolesOptions = computed(() => {
  return roles.value.map((r) => ({
    id: r.id,
    label: r.title?.ru ?? r.slug,
  }));
});

const filteredUsers = computed(() => {
  const q = query.value.trim().toLowerCase();
  if (!q) return users.value;
  return users.value.filter((u) => {
    return (
      (u.name || "").toLowerCase().includes(q) ||
      (u.email || "").toLowerCase().includes(q)
    );
  });
});

onMounted(async () => {
  await loadRoles();
  await loadUsers();
});

const loadRoles = async () => {
  const { data } = await DictionariesRepository.roles();
  roles.value = data;
};

const loadUsers = async () => {
  error.value = null;
  globalStore.changeLoading(true);
  try {
    const { data } = await UsersRepository.list();
    users.value = (data as any[]).map((u) => ({
      ...u,
      _role_ids: (u.roles ?? []).map((r: any) => r.id),
      _saving: false,
    }));
  } catch (e) {
    error.value = "Не удалось загрузить пользователей.";
    console.log(e);
  } finally {
    globalStore.changeLoading(false);
  }
};

const onCreateUser = async () => {
  error.value = null;
  if (!createForm.name || !createForm.email || !createForm.password) {
    error.value = "Заполните имя, email и пароль.";
    return;
  }
  isCreating.value = true;
  try {
    await UsersRepository.create({
      name: createForm.name,
      email: createForm.email,
      password: createForm.password,
      role_ids: createForm.role_ids,
    });
    createForm.name = "";
    createForm.email = "";
    createForm.password = "";
    createForm.role_ids = [];
    await loadUsers();
  } catch (e) {
    error.value = "Не удалось создать пользователя.";
    console.log(e);
  } finally {
    isCreating.value = false;
  }
};

const onSaveUserRoles = async (u: TUserRow) => {
  u._saving = true;
  try {
    const { data } = await UsersRepository.syncRoles(u.id, u._role_ids);
    u.roles = (data as any).roles ?? [];
  } catch (e) {
    error.value = "Не удалось сохранить роли пользователя.";
    console.log(e);
  } finally {
    u._saving = false;
  }
};
</script>

<style scoped lang="scss">
.admin-users {
  &__title {
    font-weight: 600;
    font-size: 70px;
  }

  &__table {
    width: 100%;
    border-collapse: collapse;

    th,
    td {
      text-align: left;
      padding: 12px 10px;
      border-bottom: 1px solid var(--p-surface-200);
      vertical-align: top;
    }

    th {
      font-weight: 600;
      color: var(--p-surface-0);
      background: #1e1e1e;
      position: sticky;
      top: 0;
      z-index: 1;
    }
  }
}

.table-wrap {
  width: 100%;
  overflow: auto;
  max-height: 70vh;
  border: 1px solid var(--p-surface-200);
  border-radius: 10px;
}

/* PrimeVue Password по умолчанию может не растягивать внутренний input */
:deep(.p-password),
:deep(.p-password-input) {
  width: 100%;
}
</style>


