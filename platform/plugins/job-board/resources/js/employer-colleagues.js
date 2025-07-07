import EmployerColleaguesComponent from './components/EmployerColleaguesComponent'

if (typeof vueApp !== 'undefined') {
    vueApp.booting((vue) => {
        vue.component('employer-colleagues-component', EmployerColleaguesComponent)
    })
}
