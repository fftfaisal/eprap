require('./bootstrap');
window.Vue = require('vue').default;
import vSelect from 'vue-select';
import 'vue-select/dist/vue-select.css';
Vue.filter('capitalize', function (value) {
    if (!value) return ''
    value = value.toString(); 
    return value.replace(/(^\w{1})|(\s+\w{1})/g, letter => letter.toUpperCase());
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

// global.jQuery = require('jquery');
// var $ = global.jQuery;
// window.$ = $;

Vue.component('profile-verification', require('./components/ProfileVerification.vue').default);
Vue.component('v-select', vSelect);
const app = new Vue({
    el: '#app'
});
