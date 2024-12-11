import { createApp } from 'vue';
import App from './App.vue';
import store from './store';  // Import the store
import router from './router';
import ElementPlus from 'element-plus';
import 'element-plus/dist/index.css';

const app = createApp(App);

app.use(store);  // Use the store
app.use(router);
app.use(ElementPlus);

app.mount('#app');
