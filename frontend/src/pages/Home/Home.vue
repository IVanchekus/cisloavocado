<template>
  <div class="home">
    <div class="home__enter text-center">
      <div class="home__welcome">Добро пожаловать!</div>
      <div class="home__school-name">Онлайн школа «Число Авокадо»</div>
    </div>
    <div class="home__info-squares">
      <div class="home__block-column flex align-items-center justify-content-center gap-8">
        <div class="home__info-text">
          Познай суть физики и математики с Числом Авокадо: Семена знаний, плод успеха!
        </div>
        <HomeSubjects t-subject="Физика" b-subject="Математика" exam-name="ЕГЭ" />
      </div>
      <div class="home__block-column flex align-items-center justify-content-center gap-8">
        <HomeSubjects t-subject="Математика" b-subject="Физика" exam-name="ОГЭ" />
        <div class="home__info-text">
          Физика и математика станут легкими: Насладись учебой с практикой и ответами!
        </div>
      </div>
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
    margin-bottom: 64px;
  }

  &__info-text {
    font-size: 30px;
    font-weight: 500;
    text-align: left;
    width: 461px;
  }

  &__info-squares {
    display: flex;
    flex-direction: column;
    gap: 100px;
    justify-content: center;
  }

  &__container {
    margin: auto 0;
  }
}

@media (max-width: 961px) {
  .home__info-squares {
    justify-content: center;
  }

  .home__block-column {
    display: flex;
    flex-wrap: wrap;
  }

  .home__info-text {
    font-size: 24px;
  }

  .home__enter {
    font-size: 24px;
  }
}
</style>
