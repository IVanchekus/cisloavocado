<template>
  <div class="users">
    <Card>
      <template #title>Пользователи</template>
      <template #content>
        <div class="flex flex-column row-gap-3">
          <div class="flex justify-content-between align-items-center gap-3 flex-wrap">
            <div class="text-sm text-500">
              Всего: <span class="font-semibold">{{ filteredUsers.length }}</span>
            </div>
            <InputText v-model="query" placeholder="Поиск по имени или email" />
          </div>

          <div v-if="error" class="p-3 border-1 border-round" style="border-color: var(--p-red-300);">
            {{ error }}
          </div>

          <div class="table-wrap">
            <table class="users__table">
              <thead>
                <tr>
                  <th>Имя</th>
                  <th>Email</th>
                  <th>Дата регистрации</th>
                </tr>
              </thead>
              <tbody>
                <tr v-for="u in filteredUsers" :key="u.id">
                  <td>{{ u.name }}</td>
                  <td>{{ u.email }}</td>
                  <td>{{ formatDate(u.created_at) }}</td>
                </tr>
                <tr v-if="!filteredUsers.length">
                  <td colspan="3" class="text-center text-500 py-3">
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
import UsersRepository from "@/api/Users/UsersRepository";
import { useGlobalStore } from "@/store/globalStore";
import { Card, InputText } from "primevue";
import { computed, onMounted, ref } from "vue";

type TUserRow = {
  id: number;
  name: string;
  email: string;
  created_at: string;
};

const globalStore = useGlobalStore();

const users = ref<Array<TUserRow>>([]);
const query = ref("");
const error = ref<string | null>(null);

onMounted(async () => {
  await loadUsers();
});

const loadUsers = async () => {
  error.value = null;
  globalStore.changeLoading(true);
  try {
    const { data } = await UsersRepository.list();
    users.value = data;
  } catch (e) {
    error.value = "Не удалось загрузить пользователей.";
    console.log(e);
  } finally {
    globalStore.changeLoading(false);
  }
};

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

const formatDate = (iso: string) => {
  const d = new Date(iso);
  if (Number.isNaN(d.getTime())) return iso;
  return d.toLocaleString("ru-RU", {
    year: "numeric",
    month: "2-digit",
    day: "2-digit",
    hour: "2-digit",
    minute: "2-digit",
  });
};
</script>

<style scoped lang="scss">
.users {
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
</style>


