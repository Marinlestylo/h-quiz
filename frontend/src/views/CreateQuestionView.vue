<template>
    <div class="flex items-center mb-6 justify-between">
        <div class="text-3xl">
            Création d'une nouvelle question
        </div>
        <div>
            <RouterLink to="/questions" class="bg-gray-700 hover:bg-gray-900 text-white font-bold py-2 px-4 rounded ml-48">
                Retour vers toutes les questions
            </RouterLink>
        </div>
    </div>

    <!-- Création d'une nouvelle question -->
    <div class="w-full">
        <QuestionEditor v-model:question="question" usage="create"/>
    </div>
</template>

<script setup>

import { ref, computed, onMounted } from 'vue';
import QuestionEditor from '../components/QuestionEditor.vue';
import { useUserStore } from '../stores/user';
const userStore = useUserStore();
const user = computed(() => userStore.user);

onMounted(async () => {
    await userStore.fetchUser();
});

const question = ref({
    name: '',
    content: '',
    keywords: [],
    difficulty: '',
    validation: {},
    type: '',
    option: {},
    explanation: '',
    public: false,
    points: 0,
    user_id: user.value.id
});

</script>