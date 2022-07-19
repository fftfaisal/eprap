import './bootstrap';
const CKEditor = require('@ckeditor/ckeditor5-vue2');
window.Vue = require('vue').default;
Vue.use( CKEditor );

Vue.filter('capitalize', function(value) {
    if (!value) return ''
    value = value.toString()
    return value.charAt(0).toUpperCase() + value.slice(1)
})

// ES6 Modules or TypeScript
import Swal from 'sweetalert2'
window.swal = Swal;

const toast = swal.mixin({
    toast: true,
    position: 'center',
    showConfirmButton: false,
    timer: 3000,
    timerProgressBar: true,
    onOpen: (toast) => {
        toast.addEventListener('mouseenter', Swal.stopTimer)
        toast.addEventListener('mouseleave', Swal.resumeTimer)
    }
});

window.toast = toast;

import jQuery from 'jquery';
var $ = global.jQuery;
window.$ = $;
Vue.component('study-materials', require('./components/StudyMaterials.vue').default);

const app = new Vue({
    el: '#app'
});
