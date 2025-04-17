<template>
  <div class="block-ui">
    <div v-if="isBlocked" class="block-ui__elements">
      <Logo/>
    </div>
    <BlockUI :blocked="isBlocked" full-screen></BlockUI>
  </div>
</template>

<script lang="ts" setup>
import { BlockUI } from 'primevue';
import { IProps } from './FullScreenLoader.types';
import Logo from '@/assets/logo.svg';

defineProps<IProps>();
</script>

<style scoped lang="scss">
.block-ui {
  position: relative;

  &__elements{
    position: absolute;
    z-index: 1102;
    width: 100%;
    height: 100vh;
    display: flex;
    justify-content: center;
    align-items: center;
  }
}
</style>