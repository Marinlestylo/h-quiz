import { createRouter, createWebHistory } from 'vue-router'
import HomeView from '../views/HomeView.vue'
import QuizzesView from '@/views/QuizzesView.vue'
import QuestionView from '@/views/QuestionView.vue'
import CreateQuestionView from '@/views/CreateQuestionView.vue'
import UpdateQuestionView from '@/views/UpdateQuestionView.vue'
import CreateQuizView from '@/views/CreateQuizView.vue'
import UpdateQuizView from '@/views/UpdateQuizView.vue'
import CreateRosterView from '@/views/CreateRosterView.vue'
import RosterView from '@/views/RosterView.vue'
import ActivityView from '@/views/ActivityView.vue'
import PageNotFound from '@/views/PageNotFound.vue'

import auth from '../middlewares/auth.js'
import teacher from '../middlewares/teacher.js'
import middlewarePipeline from '../middlewares/middleware-pipeline'

import { useUserStore } from '../stores/user.js'

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
      meta: {
        middleware: [
          auth
        ]
      },
      // route level code-splitting
      // this generates a separate chunk (About.[hash].js) for this route
      // which is lazy-loaded when the route is visited.
      component: () => import('../views/AboutView.vue')
    },
    {
      path: '/quizzes',
      name: 'quizzes',
      meta: {
        middleware: [
          teacher
        ]
      },
      component: QuizzesView
    },
    {
      path: '/activities',
      name: 'activities',
      meta: {
        middleware: [
          auth
        ]
      },
      component: ActivityView
    },
    {
      path: '/questions',
      name: 'questions',
      meta: {
        middleware: [
          teacher
        ]
      },
      component: QuestionView
    },
    {
      path: '/create-question',
      name: 'create question',
      meta: {
        middleware: [
          teacher
        ]
      },
      component: CreateQuestionView
    },
    {
      path: '/update-question/:id',
      name: 'update question',
      meta: {
        middleware: [
          teacher
        ]
      },
      component: UpdateQuestionView
    },
    {
      path: '/create-quiz',
      name: 'create quiz',
      meta: {
        middleware: [
          teacher
        ]
      },
      component: CreateQuizView
    },
    {
      path: '/update-quiz',
      name: 'update quiz',
      meta: {
        middleware: [
          teacher
        ]
      },
      component: UpdateQuizView
    },
    {
      path: '/create-roster',
      name: 'create roster',
      meta: {
        middleware: [
          teacher
        ]
      },
      component: CreateRosterView
    },
    {
      path: '/rosters',
      name: 'roster',
      meta: {
        middleware: [
          teacher
        ]
      },
      component: RosterView
    },
    {
      path:  "/:catchAll(.*)",
      name: '404',
      component: PageNotFound
    }
  ]
})

router.beforeEach((to, from, next) => {

  /** Navigate to next if middleware is not applied */
  if (!to.meta.middleware) {
      return next()
  }
  const userStore = useUserStore();

  const middleware = to.meta.middleware;
  const context = {
    to,
    from,
    next,
    userStore
  //   store  | You can also pass store as an argument
  }

  return middleware[0]({
      ...context,
      next:middlewarePipeline(context, middleware,1)
  })
})

export default router
