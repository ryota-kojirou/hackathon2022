import Vue from "vue";
import App from "./App.vue";
import router from "./router";
import store from "./store";
import vuetify from "./plugins/vuetify";
import axios from "axios"; //追記
import VueAxios from "vue-axios"; //追記
import VueClipboard from "vue-clipboard2";
import VueQriously from "vue-qriously";
import setInterval from '@/plugins/setIntervals';

Vue.use(VueQriously);
Vue.use(setInterval)


//firebase入れるよ
// Import the functions you need from the SDKs you need
import { initializeApp } from "firebase/app";
// import { getAnalytics } from "firebase/analytics";
// TODO: Add SDKs for Firebase products that you want to use
// https://firebase.google.com/docs/web/setup#available-libraries

// Your web app's Firebase configuration
// For Firebase JS SDK v7.20.0 and later, measurementId is optional
const firebaseConfig = {
    apiKey: "AIzaSyCXbPYmGKAt1N9GgAu2Bh-IpDMPdOn7CgM",
    authDomain: "hackathon6th.firebaseapp.com",
    projectId: "hackathon6th",
    storageBucket: "hackathon6th.appspot.com",
    messagingSenderId: "1032336544476",
    appId: "1:1032336544476:web:6fdc521644917ce9ef5a19",
    measurementId: "G-C8EPN5P4WC",
};
// Initialize Firebase
const app = initializeApp(firebaseConfig);
// const analytics = getAnalytics(app);

export { app };

Vue.config.productionTip = false;
Vue.use(VueClipboard);
Vue.use(VueAxios, axios); //追記

new Vue({
    router,
    store,
    vuetify,
    render: (h) => h(App),
}).$mount("#app");