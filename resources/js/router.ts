import type { RouteRecordRaw } from "vue-router";
import { createRouter, createWebHistory } from "vue-router";

import Dashboard from "./Pages/Dashboard.vue";
import Edit from "./Pages/Profile/Edit.vue";
import UploadInventory from "./Pages/CulturalProperty/UploadInventory.vue";
import Create from "./Pages/Announcement/Create.vue";
import ViewInventory from "./Pages/CulturalProperty/ViewInventory.vue";
import Announcement from "./Pages/Announcement/Index.vue";
import Register from "./Pages/Auth/Register.vue";
import Index from "./Pages/Users/Index.vue";
import EditProfile from "./Pages/Users/EditProfile.vue";

//users

const routes: RouteRecordRaw[] = [
    {
        path: "/dashboard",
        name: "Dashboard",
        component: Dashboard,
    },
    {
        path: "/profile",
        name: "Profile",
        component: Edit,
    },
    {
        path: "/cultural-property/upload-inventory",
        name: "UploadInventory",
        component: UploadInventory,
    },
    {
        path: "/cultural-property/view-inventory",
        name: "ViewInventory",
        component: ViewInventory,
    },
];

const router = createRouter({
    history: createWebHistory(),
    routes,
});

export default router;
