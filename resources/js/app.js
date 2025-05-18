import './bootstrap';
import { createApp } from 'vue'
import App from './components/App.vue'
import vuetify from './vuetify'
import axios from 'axios'

axios.defaults.headers.common['X-Requested-With'] = 'XMLHttpRequest'

createApp(App).use(vuetify).mount('#app')
