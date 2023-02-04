import Vue from "vue";
import VueRouter from "vue-router";
import User from "./services/UserServices";
import StorageService from "./services/StorageService";
import Bus from "./Config/Plugins/Bus";

Vue.use(VueRouter);

const routes = [
    {
        path: "/",
        name: "Home",
        component: () =>
            import(/* webpackChunkName: "login" */ "./views/Login.vue")
    },
    {
        path: "/login",
        name: "Login",
        component: () =>
            import(/* webpackChunkName: "login" */ "./views/Login.vue")
    },
    {
        path: "/forgotten-password",
        name: "ForgottenPassword",
        component: () =>
            import(
                /* webpackChunkName: "forgottten_password" */ "./views/ForgottenPassword.vue"
                )
    },
    {
        path: "/forgotten-password/:code",
        name: "ForgottenPasswordCode",
        component: () =>
            import(
                /* webpackChunkName: "forgotte_password_code" */ "./views/ForgottenPasswordCode.vue"
                )
    },
    /** dashboard */
    {
        path: "/dashboard",
        name: "Dashboard",
        component: () =>
            import(/* webpackChunkName: "dashboard" */ "./views/Dashboard.vue"),
        meta: {
            requiresAuth: true
        }
    },
    /** users */
    {
        path: "/users",
        name: "Users",
        component: () =>
            import(/* webpackChunkName: "users" */ "./views/Users.vue"),
        meta: {
            requiresAuth: true
        }
    },
    {
        path: "/users/:userId",
        name: "Users-detail",
        component: () =>
            import(
                /* webpackChunkName: "users_detail" */ "./views/UserDetail.vue"
                ),
        meta: {
            requiresAuth: true
        }
    },
    /**blog */
    {
        path: "/blog",
        name: "Blog",
        meta: {
            requiresAuth: true
        },
        component: () =>
            import(/* webpackChunkName: "blog" */ "./views/Blog.vue")
    },
    {
        path: "/blog/new",
        name: "Blog-new",
        meta: {
            requiresAuth: true
        },
        component: () =>
            import(/* webpackChunkName: "blog-new" */ "./views/NewPost.vue")
    },
    {
        path: "/blog/:postId",
        name: "Blog-detail",
        meta: {
            requiresAuth: true
        },
        component: () =>
            import(/* webpackChunkName: "blog_detail" */ "./views/BlogPost.vue")
    },
    /**cms */
    {
        path: "/cms",
        name: "Cms",
        meta: {
            requiresAuth: true
        },
        component: () => import(/* webpackChunkName: "cms" */ "./views/Cms.vue")
    },
    {
        path: "/cms/new",
        name: "Cms-new",
        meta: {
            requiresAuth: true
        },
        component: () =>
            import(/* webpackChunkName: "cms_new" */ "./views/NewCms.vue")
    },
    {
        path: "/cms/:cmsId",
        name: "Cms-detail",
        meta: {
            requiresAuth: true
        },
        component: () =>
            import(/* webpackChunkName: "cms_detail" */ "./views/CmsDetail.vue")
    },
    {
        path: "/blog-categories",
        name: "BlogCategories",
        meta: {
            requiresAuth: true
        },
        component: () =>
            import(
                /* webpackChunkName: "blog_categories" */ "./views/BlogCategories.vue"
                )
    }
];

const router = new VueRouter({
    mode: "history",
    // base: process.env.BASE_URL,
    base: "/admin",
    routes
});

router.beforeEach(async (to, from, next) => {
    const hasToken = StorageService.get("token");
    const omit = ["Login", "ForgottenPassword", "ForgottenPasswordCode"];
    try {
        Bus.$emit("processing", true);
        const { data } = await User.currentUser();
        Bus.$emit("processing", false);
        if (omit.includes(to.name)) {
            next({ path: "/dashboard" });
        } else {
            next();
        }
    } catch (e) {
        Bus.$emit("processing", false);
        if (omit.includes(to.name)) {
            next();
        } else {
            next({ name: "Login" });
        }
    }
});

export default router;
