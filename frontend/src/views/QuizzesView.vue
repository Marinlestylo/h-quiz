<template>
    <div v-if="!isLoading">
        <ErrorMessage :status="status" />
    </div>
    <div class="text-3xl mb-6">
        Tous les quiz
    </div>
    <div
        class="max-w-5xl rounded overflow-hidden shadow-lg border border-gray-400 bg-white rounded-b p-4 my-6">
        <div class="flex flex-col">
            <div class="overflow-x-auto sm:-mx-6 lg:-mx-8">
                <div class="inline-block min-w-full py-2 sm:px-6 lg:px-8">
                    <div class="overflow-hidden">
                        <table class="min-w-full text-left text-sm font-light">
                            <thead class="border-b font-medium dark:border-neutral-500">
                                <tr>
                                    <th scope="col" class="px-6 py-2">#</th>
                                    <th scope="col" class="px-6 py-2">Quiz</th>
                                    <th scope="col" class="px-6 py-2">Questions</th>
                                    <th scope="col" class="px-6 py-2">Créateur</th>
                                    <th scope="col" class="px-6 py-2">Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr v-for="item in listItems.quizzes"
                                    class="border-b transition duration-300 ease-in-out hover:bg-neutral-200">
                                    <td class="whitespace-nowrap px-6 py-4 font-medium">{{ item.id }}</td>
                                    <td class="whitespace-nowrap px-6 py-4">{{ item.name }}</td>
                                    <td class="whitespace-nowrap px-6 py-4">{{ item.question_count }}</td>
                                    <td class="whitespace-nowrap px-6 py-4">{{ item.owner.firstname + " " +
                                        item.owner.lastname }}
                                    </td>
                                    <td class="whitespace-nowrap px-6 py-4"><svg xmlns="http://www.w3.org/2000/svg"
                                            fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"
                                            class="w-10 h-10">
                                            <path stroke-linecap="round" stroke-linejoin="round"
                                                d="M12 9v6m3-3H9m12 0a9 9 0 11-18 0 9 9 0 0118 0z" />
                                        </svg>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script setup>
import { ref } from 'vue';
import ErrorMessage from '@/components/StatusError.vue';
import { backUrl } from '@/stores/user';

const listItems = ref([]);
let status = ref(0);
const isLoading = ref(true);

async function getData() {
    const res = await fetch(`${backUrl}/api/quizzes`, {
        credentials: 'include',
    });
    status.value = res.status;
    if (res.status === 200) {
        const finalRes = await res.json();
        listItems.value = finalRes;
    }
    isLoading.value = false;
}

getData()
</script>