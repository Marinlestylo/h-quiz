import { createRouter, createWebHistory } from 'vue-router'
import HomeView from '../views/HomeView.vue'
import QuizzesView from '@/views/QuizzesView.vue'
import SandboxView from '@/views/SandboxView.vue'
import CreateQuestionView from '@/views/CreateQuestionView.vue'
import UpdateQuestionView from '@/views/UpdateQuestionView.vue'
import CreateQuizView from '@/views/CreateQuizView.vue'
import UpdateQuizView from '@/views/UpdateQuizView.vue'
import CreateRosterView from '@/views/CreateRosterView.vue'
import RosterView from '@/views/RosterView.vue'

const router = createRouter({
  history: createWebHistory(import.meta.env.BASE_URL),
  routes: [
    {
      path: '/',
      name: 'root',
      component: HomeView
    },
    {
      path: '/about',
      name: 'about',
      // route level code-splitting
      // this generates a separate chunk (About.[hash].js) for this route
      // which is lazy-loaded when the route is visited.
      component: () => import('../views/AboutView.vue')
    },
    {
      path: '/quizzes',
      name: 'quizzes',
      component: QuizzesView
    },
    {
      path: '/sandbox',
      name: 'sandbox',
      component: SandboxView
    },
    {
      path: '/create-question',
      name: 'create question',
      component: CreateQuestionView
    },
    {
      path: '/update-question',
      name: 'update question',
      component: UpdateQuestionView
    },
    {
      path: '/create-quiz',
      name: 'create quiz',
      component: CreateQuizView
    },
    {
      path: '/update-quiz',
      name: 'update quiz',
      component: UpdateQuizView
    },
    {
      path: '/create-roster',
      name: 'create roster',
      component: CreateRosterView
    },
    {
      path: '/roster',
      name: 'roster',
      component: RosterView
    }
  ]
})

export default router
