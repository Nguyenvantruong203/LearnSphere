import { createApp, h } from 'vue'
import { createPinia } from 'pinia'
import App from './App.vue'
import router from './router'
import Antd from 'ant-design-vue'
import 'ant-design-vue/dist/reset.css'
import './style.css'

import { ConfigProvider, theme } from 'ant-design-vue'

const pinia = createPinia()
const app = createApp({
  render: () =>
    h(ConfigProvider, {
      theme: {
        algorithm: theme.defaultAlgorithm,
        token: { 
          colorPrimary: '#14b8a6',
          borderRadius: 12,
          // Thêm một số token khác nếu cần
          fontFamily: '-apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, sans-serif',
          // colorBgContainer: '#ffffff',
          // colorBorder: '#e5e7eb'
        }
      }
    }, { default: () => h(App) })
})

app.use(pinia)
app.use(router)
app.use(Antd)
app.mount('#app')

// Hoặc nếu muốn tách theme config ra file riêng:
// import themeConfig from './theme.config'
// theme: themeConfig
