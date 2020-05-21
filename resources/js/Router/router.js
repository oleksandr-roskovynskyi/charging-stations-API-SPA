import Vue from 'vue'
import VueRouter from 'vue-router'

Vue.use(VueRouter);

import MainPage from "../components/MainPage";
import MyCityPage from "../components/MyCityPage";
import NowOpenPage from "../components/NowOpenPage";
import ClosestPage from "../components/ClosestPage";

const routes = [
    { path: '/', component: MainPage },
    { path: '/city', component: MyCityPage, name: 'MyCityPage' },
    { path: '/now-open', component: NowOpenPage, name: 'NowOpenPage' },
    { path: '/closest-now-open', component: ClosestPage, name: 'ClosestPage' },
];

const router = new VueRouter({
    routes, // short for `routes: routes`
    hashbang : false,
    mode : 'history'
});

export default router;
