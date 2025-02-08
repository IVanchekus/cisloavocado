<template>
  <div class="home">
    <div class="home__enter text-center">
      <div class="home__welcome">Добро пожаловать!</div>
      <div class="home__school-name">Онлайн школа «Число Авокадо»</div>
    </div>
    <div class="home__info-squares">
      <div>Познай суть физики и математики с Числом Авокадо: Семена знаний, плод успеха!</div>
      <HomeSubjects t-subject="Физика" b-subject="Математика" exam-name="ЕГЭ" />
      <HomeSubjects t-subject="Математика" b-subject="Физика" exam-name="ОГЭ" />
      <div>Физика и математика станут легкими: Насладись учебой с практикой и ответами!</div>
    </div>
  </div>
</template>

<script setup lang="ts">
import HomeSubjects from '@/pages/Home/HomeSubjects.vue';


</script>

<style lang="scss" scoped>
.home {
  height: 100%;

  &__enter {
    font-size: 70px;
  }

  &__welcome {
    font-weight: 500;
  }

  &__school-name {
    font-weight: 800;
  }
}
</style>
