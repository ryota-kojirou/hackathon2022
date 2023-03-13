import Vue from "vue";
import VueRouter from "vue-router";
import Home from "../views/Home.vue";
import VueQrcodeReader from "vue-qrcode-reader";

Vue.use(VueQrcodeReader);

Vue.use(VueRouter);

const routes = [{
    path: "/",
    name: "Home",
    component: Home,
},
{
    path: "/wait/:roomID",
    name: "Wait",
    component: () =>
        import( /* webpackChunkName: "about" */ "../views/Wait.vue"),
},
{
    path: "/test/:roomID",
    name: "TestWait",
    component: () =>
        import( /* webpackChunkName: "about" */ "../views/TestWait.vue"),
},
{
    path: "/praise/:roomID",
    name: "Praise",
    component: () =>
        import( /* webpackChunkName: "about" */ "../views/Praise.vue"),
},
{
    path: "/guess/:roomID",
    name: "Guess",
    component: () =>
        import( /* webpackChunkName: "about" */ "../views/Guess.vue"),
},
{
    path: "/check/:roomID",
    name: "Check",
    component: () =>
        import( /* webpackChunkName: "about" */ "../views/Check.vue"),
},
{
    path: "/result/:roomID",
    name: "Result",
    component: () =>
        import( /* webpackChunkName: "about" */ "../views/Result.vue"),
},
{
    path: "/collection",
    name: "Collection",
    component: () =>
        import( /* webpackChunkName: "about" */ "../views/Collection.vue"),
},
];

const router = new VueRouter({
    mode: "hash",
    base: process.env.BASE_URL,
    routes,
});

export default router;