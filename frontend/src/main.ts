import './assets/index.css'
import 'primeicons/primeicons.css'
import { createApp } from 'vue'
import { createPinia } from 'pinia'
import piniaPersistedState from 'pinia-plugin-persistedstate'
import PrimeVue from 'primevue/config'
import Aura from '@primeuix/themes/aura'
import { ConfirmationService } from 'primevue'
import App from './App.vue'
import router from './router'
import { vCan } from './directives/can'

const app = createApp(App)

const pinia = createPinia()
pinia.use(piniaPersistedState)
app.use(pinia)
app.use(router)
app.use(ConfirmationService)
app.use(PrimeVue, {
  theme: {
    preset: Aura,
    options: {
      darkModeSelector: 'none',
    }
  }
})
app.directive('can', vCan)

app.mount('#app')
