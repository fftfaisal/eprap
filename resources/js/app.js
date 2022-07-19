require('./bootstrap');
import CKEditor from '@ckeditor/ckeditor5-vue2';
window.Vue = require('vue').default;
Vue.use( CKEditor );

Vue.filter('capitalize', function(value) {
    if (!value) return ''
    value = value.toString()
    return value.charAt(0).toUpperCase() + value.slice(1)
})
window.editorConfig = {
    htmlSupport: {
        allow: [
            {
                name: /.*/,
                attributes: true,
                classes: true,
                styles: true
            }
        ]
    }
};
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

global.jQuery = require('jquery');
var $ = global.jQuery;
window.$ = $;

Vue.component('exam', require('./components/Exam.vue').default);
Vue.component('setup-reading-exam', require('./components/admin/SetupReadingExam.vue').default);
Vue.component('setup-writing-exam', require('./components/admin/SetupWritingExam.vue').default);
Vue.component('setup-listening-exam', require('./components/admin/SetupListeningExam.vue').default);
Vue.component('radio-questions', require('./components/RadioQuestions.vue').default);
Vue.component('checkbox-questions', require('./components/CheckboxQuestions.vue').default);
// Vue.component('study-materials', require('./components/StudyMaterials.vue').default);
// Vue.component('setup-task', require('./components/admin/study-materials/StudyTask.vue').default);
// Vue.component('setup-task-edit', require('./components/admin/study-materials/StudyTaskEdit.vue').default);

//Assessment
Vue.component('assessment-writing', require('./components/assessment/Writing.vue').default);
Vue.component('assessment-speaking', require('./components/assessment/Speaking.vue').default);

const app = new Vue({
    el: '#app'
});
