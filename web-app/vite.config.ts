import { defineConfig } from 'vite'
import vue from '@vitejs/plugin-vue'
import vueJsx from '@vitejs/plugin-vue-jsx'
import Components from 'unplugin-vue-components/vite'
import AutoImport from 'unplugin-auto-import/vite'
import { AntDesignVueResolver } from 'unplugin-vue-components/resolvers'
import path from 'node:path'

export default defineConfig({
  plugins: [
    vue(),
    vueJsx(),
    AutoImport({
      resolvers: [AntDesignVueResolver({ resolveIcons: true })],
      dts: 'src/auto-imports.d.ts',
    }),
    Components({
      dirs: ['src/components'], // Quét các component cục bộ
      resolvers: [AntDesignVueResolver({ importStyle: false, resolveIcons: true })],
      dts: 'src/components.d.ts',
    }),
  ],
  resolve: {
    alias: {
      '@': path.resolve(__dirname, 'src'),
    },
  },
})
