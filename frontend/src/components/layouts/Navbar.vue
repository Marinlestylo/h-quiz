<template>
    <header class="w-full bg-gray-900 p-4">
        <nav class="text-white flex justify-between">
            <div class="flex">
                <a href="https://heig-vd.ch" target="_blank">
                    <img src="@/assets/images/logo-heig-vd.svg" alt="Logo" class="h-12 inline-block" />
                </a>
                <RouterLink to="/" class="ml-4 hover:text-gray-300">
                    <div>
                        <img src="@/assets/images/quiz.svg" alt="Logo" class="h-12 inline-block" />
                    </div>
                </RouterLink>
            </div>
            <div class="flex items-center">
                <RouterLink to="/activities" class="ml-4 hover:text-gray-300">Activités</RouterLink>
                <div v-if="user.role.includes('staff')">
                    <RouterLink to="/quizzes" class="ml-4 hover:text-gray-300">Quizzes</RouterLink>
                    <RouterLink to="/questions" class="ml-4 hover:text-gray-300">Questions</RouterLink>
                    <RouterLink to="/rosters" class="ml-4 hover:text-gray-300">Rosters</RouterLink>
                </div>

            </div>
            <div class="flex items-center">
                <Connection />
            </div>
        </nav>
    </header>
</template>

<script setup>
import Connection from '@/components/layouts/Connection.vue'
import { useUserStore } from '@/stores/user';
import { computed } from 'vue';

const store = useUserStore();
const user = computed(() => store.user);
</script>