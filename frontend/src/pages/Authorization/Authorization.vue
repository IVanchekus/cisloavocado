<template>
  <div class="flex flex-column row-gap-3">
    <div class="authorization__title">Авторизация</div>

    <div class="grid">
      <div class="col-12 md:col-4">
        <Card>
          <template #title>Пользователи и роли</template>
          <template #content>
            <div class="text-500 mb-3">
              Создание пользователей и выдача им ролей (из БД).
            </div>
            <Button label="Открыть" @click="router.push({ name: 'admin-users-roles' })" />
          </template>
        </Card>
      </div>

      <div class="col-12 md:col-4">
        <Card>
          <template #title>Редактирование ролей</template>
          <template #content>
            <div class="text-500 mb-3">
              Настройка связей «роль — разрешения».
            </div>
            <Button label="Открыть" @click="router.push({ name: 'admin-roles' })" />
          </template>
        </Card>
      </div>

      <div class="col-12 md:col-4">
        <Card>
          <template #title>Пользователи</template>
          <template #content>
            <div class="text-500 mb-3">
              Список пользователей.
            </div>
            <Button label="Открыть" @click="router.push({ name: 'users' })" />
          </template>
        </Card>
      </div>
    </div>
  </div>
</template>

<script setup lang="ts">
import { Card, Button } from "primevue";
import { onMounted } from "vue";
import { useAuthStore } from "@/store/authStore";
import router from "@/router";

const authStore = useAuthStore();

onMounted(() => {
  if (!authStore.hasRole("admin")) {
    router.push({ name: "home" });
  }
});
</script>

<style scoped lang="scss">
.authorization {
  &__title {
    font-weight: 600;
    font-size: 70px;
  }
}
</style>


