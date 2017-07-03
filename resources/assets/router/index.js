import Vue from 'vue'
import VueRouter from 'vue-router'
import routes from './routes'
// import util from '../util'
// import iView from 'iview'

/*iView.LoadingBar.config({
    color: 'rgb(143, 255, 199)',
    failedColor: 'red',
    height: 3
});*/

Vue.use(VueRouter)

const router = new VueRouter({
    routes,
    mode: 'history',
    linkActiveClass: 'active',
    scrollBehavior (to, from, savedPosition) {
        return savedPosition || { x: 0, y: 0 }
    }
});


//路由开始前
router.beforeEach((to, from, next) => {
    // console.log('to', to);
    // console.log(from);
    // console.log(window.Admin);
    var urlPrefix = 'admin';
    if (typeof window.Admin != 'undefined') {
        if (window.Admin.LoggedUser) {
            router.app.$store.commit('setUser', window.Admin.LoggedUser);
        }
        urlPrefix = window.Admin.UrlPrefix;
    }
    router.app.$store.commit('setUrlPrefix', urlPrefix)

    if (to.meta.title) {
        router.app.$store.commit('setMetaTitle', to.meta.title);
        document.title = router.app.$store.state.metaTitle + ' - ' + window.Admin.Title;
    }

    if (to.fullPath != '/#') {
        // router.app.$Loading.start();

        if (to.meta.auth) {
            // if (typeof window.Admin != 'undefined' && window.Admin.PermList) {
                // store.commit('setPermissions', window.Admin.PermList);
            // }

            if (Object.keys(router.app.$store.state.user).length == 0) {
                return next({name: 'admin.login'});
            }

            if ($.inArray(to.name, ['admin.model.index', 'admin.model.create', 'admin.model.edit']) != -1) {
                if (Object.keys(router.app.$store.state.models).indexOf(to.params.model) == -1) {
                    router.app.axios.get(`/${router.app.$store.state.urlPrefix}/${to.params.model}/config`)
                        .then(response => {
                            var data = {};
                            data[to.params.model] = response.data;
                            router.app.$store.commit('setModel', data);
                            router.app.$store.commit('setMetaTitle', response.data.title);
                            document.title = router.app.$store.state.metaTitle + ' - ' + window.Admin.Title;
                            next();
                        }).catch(error => {
                            next({name: 'admin.403'});
                        })
                } else {
                    to.meta.title = router.app.$store.state.models[to.params.model].title;
                    router.app.$store.commit('setMetaTitle', to.meta.title);
                    document.title = router.app.$store.state.metaTitle + ' - ' + window.Admin.Title;
                    next();
                }
            } else {
                if (typeof to.name == 'undefined' || to.name == '') {
                    return next({name: 'admin.403'});
                }
                router.app.axios.get(`/${router.app.$store.state.urlPrefix}/check/perm/${to.name}`)
                    .then(response => {
                        router.app.$store.commit('setMetaTitle', to.meta.title);
                        document.title = router.app.$store.state.metaTitle + ' - ' + window.Admin.Title;
                        next();
                    }).catch(error => {
                        return next({name: 'admin.403'});
                    })
            }

        } else {
            next();
        }
    }
});


//路由完成后
router.afterEach(route => {
    // router.app.$Loading.finish();
});

export default router;