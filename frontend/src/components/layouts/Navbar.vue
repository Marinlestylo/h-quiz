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
                <div v-if="user.id">
                    <DropdownMenu name="Activités" :links="activityLinks" class="ml-4" />

                </div>
                <div v-if="user.role.includes('staff')">
                    <DropdownMenu name="Quiz" :links="quizLinks" class="ml-4" />
                    <DropdownMenu name="Questions" :links="questionLinks" class="ml-4" />
                    <DropdownMenu name="Rosters" :links="rosterLinks" class="ml-4" />
                </div>
                <div v-else-if="user.role.includes('student')">
                    <DropdownMenu name="Drill" :links="drillLinks" class="ml-4" />
                </div>


            </div>
            <div class="flex items-center">
                <Connection />
            </div>
        </nav>
    </header>
</template>

<script setup>
import Connection from '@/components/layouts/Connection.vue';
import DropdownMenu from './DropdownMenu.vue';
import { useUserStore } from '@/stores/user';
import { computed } from 'vue';

const activityLinks = [
    { name: 'Activités', link: '/activities' },
];

const quizLinks = [
    { name: 'Quiz', link: '/quizzes' },
    { name: 'Création d\'un quiz', link: '/questions' },
    { name: 'Modification d\'un quiz', link: '/rosters' },
];

const questionLinks = [
    { name: 'Questions', link: '/questions' },
    { name: 'Création d\'une question', link: '/create-question' },
];

const rosterLinks = [
    { name: 'Rosters', link: '/rosters' },
    { name: 'Création d\'un roster', link: '/create-roster' }
];

const drillLinks = [
    { name: 'Mode Drill', link: '/drill' },
];

const store = useUserStore();
const user = computed(() => store.user);
</script>