import { defineConfig } from 'vite'
import vue from '@vitejs/plugin-vue'
import tsconfigPaths from 'vite-tsconfig-paths';
import svgLoader from 'vite-svg-loader';

export default defineConfig({
  plugins: [vue(), tsconfigPaths(), svgLoader()],
  resolve: {
    alias: {
      "@/": "/src/"
    }
  }
})
