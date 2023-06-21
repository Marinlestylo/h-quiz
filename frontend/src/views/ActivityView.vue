<template>
    <div v-if="!isLoading">
        <ErrorMessage :status="status" />
    </div>
    <div v-show="!isLoading" class="mb-6">
        <div class="text-3xl">
            Activités de toutes les classes
        </div>
        <div class="text-l">
            Les activités en cours et passées sont accessibles des étudiants concernés. En cachant une activité, les
            étudiants ne pourront plus y accéder.
        </div>
    </div>
    <div v-show="!isLoading"
        class="max-w-5xl rounded overflow-hidden shadow-lg border border-gray-400 bg-white rounded-b px-4 my-6">
        <div class="flex flex-col">
            <div class="overflow-x-auto sm:-mx-6 lg:-mx-8">
                <div class="inline-block min-w-full py-2 sm:px-6 lg:px-8">
                    <div class="overflow-hidden">
                        <table class="min-w-full text-left text-sm font-light">
                            <thead class="border-b font-medium dark:border-neutral-500">
                                <tr>
                                    <th scope="col" class="px-6 py-2">Quiz</th>
                                    <th scope="col" class="px-6 py-2">Questions</th>
                                    <th scope="col" class="px-6 py-2">Classe</th>
                                    <th scope="col" class="px-6 py-2">Durée</th>
                                    <th scope="col" class="px-6 py-2">Modifié</th>
                                    <th scope="col" class="px-6 py-2">Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr v-for="item in hardCodedDate.data"
                                    class="border-b transition duration-300 ease-in-out hover:bg-neutral-200">
                                    <td class="whitespace-nowrap px-6 py-4 font-medium">{{ item.quiz.name }}</td>
                                    <td class="whitespace-nowrap px-6 py-4 font-medium">{{ item.quiz.questions }}</td>
                                    <td class="whitespace-nowrap px-6 py-4 font-medium">{{ item.roster.name }}</td>
                                    <td class="whitespace-nowrap px-6 py-4 font-medium">{{ item.duration/60 }} minutes</td>
                                    <td class="whitespace-nowrap px-6 py-4 font-medium">{{ item.updated_at }} </td>
                                    <td class="whitespace-nowrap px-6 py-4 font-medium">actions</td>
                                    
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

const hardCodedDate = {
    "count": 2,
    "data": [
        {
            "id": 2,
            "user_id": 1,
            "duration": 300,
            "completed": false,
            "hidden": 0,
            "status": "running",
            "quiz": {
                "id": 1,
                "name": "Quiz-00 Mes Débuts",
                "questions": 8,
                "keywords": [
                    "binary",
                    "conversion",
                    "struct"
                ]
            },
            "roster": {
                "id": 1,
                "year": "2020",
                "name": "PlayGround-TIN-A",
                "semester": "fall",
                "students": 2,
                "orientations": {
                    "EAI": 2
                },
                "has_running_activities": true,
                "teacher": {
                    "id": 1,
                    "name": "Tony Maulaz"
                },
                "created_at": 1687288751
            },
            "updated_at": "2023-06-20T20:17:35.000000Z",
            "created_at": "2023-06-20T20:17:12.000000Z",
            "started_at": "2023-06-20T20:17:35.000000Z",
            "ended_at": null,
            "shuffle_questions": 0,
            "shuffle_propositions": 0,
            "quiz_url": "http://127.0.0.1:8000/api/quizzes/1",
            "roster_url": "http://127.0.0.1:8000/api/rosters/1",
            "questions_url": "http://127.0.0.1:8000/api/activities/2/questions"
        },
        {
            "id": 1,
            "user_id": 1,
            "duration": 120,
            "completed": true,
            "hidden": 0,
            "status": "finished",
            "quiz": {
                "id": 1,
                "name": "Quiz-00 Mes Débuts",
                "questions": 8,
                "keywords": [
                    "binary",
                    "conversion",
                    "struct"
                ]
            },
            "roster": {
                "id": 1,
                "year": "2020",
                "name": "PlayGround-TIN-A",
                "semester": "fall",
                "students": 2,
                "orientations": {
                    "EAI": 2
                },
                "has_running_activities": true,
                "teacher": {
                    "id": 1,
                    "name": "Tony Maulaz"
                },
                "created_at": 1687288751
            },
            "updated_at": "2023-06-20T19:19:14.000000Z",
            "created_at": "2023-06-20T19:19:14.000000Z",
            "started_at": "2020-09-01T12:58:05.000000Z",
            "ended_at": "2020-09-01T13:40:00.000000Z",
            "shuffle_questions": 0,
            "shuffle_propositions": 0,
            "@hide_url": "http://127.0.0.1:8000/api/activities/1/hide",
            "quiz_url": "http://127.0.0.1:8000/api/quizzes/1",
            "roster_url": "http://127.0.0.1:8000/api/rosters/1",
            "questions_url": "http://127.0.0.1:8000/api/activities/1/questions"
        }
    ]
}
listItems.value = hardCodedDate
console.log(listItems.value)
</script>