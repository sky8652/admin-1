import Vue from 'vue';
import VueI18n from 'vue-i18n';
// import deepmerge from 'deepmerge';

import en from './en';
import zh from './zh-CN';

import EleEn from 'element-ui/lib/locale/lang/en';
import EleZh from 'element-ui/lib/locale/lang/zh-CN';

Vue.config.lang = window.Lang;

Vue.use(VueI18n);

const messages = {
    en: Object.assign(en, EleEn),
    'zh-CN': Object.assign(zh, EleZh)
}

const i18n = new VueI18n({
    locale: Vue.config.lang,
    fallbackLocale: 'en',
    messages
})

export default i18n;