
const routes = [
    {
        path: '/admin',
        component (resolve) {
            require(['./views/index.vue'], resolve);
        },
        name: 'admin.index'
    },
    {
        path: '/admin/system/menu',
        component (resolve) {
            require(['./views/system/menu/index.vue'], resolve);
        },
        name: 'admin.system.menu'
    },
    {
        path: '/admin/manager/user',
        component (resolve) {
            require(['./views/manager/user/index.vue'], resolve);
        },
        name: 'admin.manager.user'
    },
    {
        path: '/admin/manager/role',
        component (resolve) {
            require(['./views/manager/role/index.vue'], resolve);
        },
        name: 'admin.manager.role'
    }
];
export default routes;
