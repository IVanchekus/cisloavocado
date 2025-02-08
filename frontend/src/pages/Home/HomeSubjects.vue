<template>
  <div class="home__subjects">
    <div class="home__subj-name">{{ tSubject }}</div>
    <div class="divider-line flex">
      <div class="divider-line__line"></div>
      <div class="divider-line__vector"><i class="pi pi-times"></i></div>
    </div>
    <div class="home__subj-name">{{ bSubject }}</div>
    <div class="home__exam-name">{{ examName }}</div>
  </div>
</template>

<script setup lang="ts">
import { IProps } from '@/pages/Home/HomeSubjects.types';

const props = defineProps<IProps>();
</script>

<style lang="scss" scoped>
.home {
  &__subjects {
    background: #FFF598;
    width: 461px;
    height: 182px;
    box-shadow: 0px 4px 4px 0px rgba(0, 0, 0, 0.25);
    border-radius: 16px;
  }

  &__subj-name {
    font-size: 48px;
  }

  &__exam-name {
    font-size: 64px;
  }
}

.divider-line {
  &__line {
    width: 273px;
    height: 0;
    border: 1px solid rgb(30, 30, 30);;
  }

  &__vector {
    font-size: 10.5;
  }
}
</style>