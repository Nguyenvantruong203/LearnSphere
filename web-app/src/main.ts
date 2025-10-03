import { createApp, h } from 'vue'
import { createPinia } from 'pinia'
import App from './App.vue'
import router from './router'
import Antd from 'ant-design-vue'
import 'ant-design-vue/dist/reset.css'
import './style.css'
import piniaPluginPersistedstate from 'pinia-plugin-persistedstate'
import { ConfigProvider, theme } from 'ant-design-vue'
import '@fortawesome/fontawesome-free/css/all.min.css'

const pinia = createPinia()
pinia.use(piniaPluginPersistedstate)
const app = createApp({
  render: () =>
    h(ConfigProvider, {
      theme: {
        algorithm: theme.defaultAlgorithm,
        token: { 
          colorPrimary: '#14b8a6',
          borderRadius: 12,
          fontFamily: '-apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, sans-serif',
        }
      }
    }, { default: () => h(App) })
})

app.use(pinia)
app.use(router)
app.use(Antd)
app.mount('#app')