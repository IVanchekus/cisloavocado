import { defineConfig } from 'vite'
import vue from '@vitejs/plugin-vue'
import tsconfigPaths from 'vite-tsconfig-paths';
import svgLoader from 'vite-svg-loader';
import { visualizer } from 'rollup-plugin-visualizer';

export default defineConfig({
  plugins: [vue(), tsconfigPaths(), svgLoader(), visualizer()],
  resolve: {
    alias: {
      "@/": "/src/"
    }
  }
})
