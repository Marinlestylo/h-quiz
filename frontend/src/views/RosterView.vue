<template>
    <div class="flex items-center mb-6 justify-between">
        <div class="text-3xl">
            Sélectionnez un roster
        </div>
        <div>
            <RouterLink to="/create-roster"
                class="bg-gray-700 hover:bg-gray-900 text-white font-bold py-2 px-4 rounded ml-48">
                Créer un nouveau roster
            </RouterLink>
        </div>
    </div>
    <div>
        <select v-model="selectedRoster" @change="displayRosterInfo" id="id_roster"
            class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5">
            <option disabled selected value="">Choisissez un roster</option>
            <option v-for="roster in rosters" :key="roster.id" v-bind:value="roster.id">{{ roster.name }} / {{
                roster.semester }} / {{ roster.year }}</option>
        </select>
    </div>
    <div v-show="showDetails">
        <div class="text-xl mt-10 mb-4">
            Étudiants
        </div>
        <div class="flex">
            <label for="default-search"
                class="mb-2 text-sm font-medium text-gray-900 sr-only">Search</label>
            <div class="relative w-full">
                <div class="absolute inset-y-0 left-0 flex items-center pl-3 pointer-events-none">
                    <svg aria-hidden="true" class="w-5 h-5 text-gray-500" fill="none"
                        stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                    </svg>
                </div>
                <input type="search" id="default-search"
                    class="block w-full p-4 pl-10 text-sm text-gray-900 border border-gray-300 rounded-lg bg-gray-50 focus:ring-blue-500 focus:border-blue-500" 
                    placeholder="Entrez le nom d'un étudiant" required>
                <button type="submit"
                    class="text-white absolute right-2.5 bottom-2.5 bg-gray-700 hover:bg-gray-900 focus:ring-4 focus:outline-none focus:ring-gray-300 font-medium rounded-lg text-sm px-4 py-2">Ajouter l'étudiant</button>
            </div>
        </div>
        <div class="max-w-5xl rounded overflow-hidden shadow-lg border border-gray-400 bg-white rounded-b px-4 my-6">
            <div class="flex flex-col">
                <div class="overflow-x-auto sm:-mx-6 lg:-mx-8">
                    <div class="inline-block min-w-full py-2 sm:px-6 lg:px-8">
                        <div class="overflow-hidden">
                            <table class="min-w-full text-left text-sm font-light">
                                <thead class="border-b font-medium dark:border-neutral-500">
                                    <tr>
                                        <th scope="col" class="px-6 py-2">Nom</th>
                                        <th scope="col" class="px-6 py-2">Orientation</th>
                                        <th scope="col" class="px-6 py-2">Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr v-for="student in detailedRoster.data"
                                        class="border-b transition duration-300 ease-in-out hover:bg-neutral-200">
                                        <td class="whitespace-nowrap px-6 py-4 font-medium">{{ student.name }}</td>
                                        <td class="whitespace-nowrap px-6 py-4 font-medium">{{ student.orientation }}</td>
                                        <td class="whitespace-nowrap px-6 py-4 font-medium"><button @click="deleteStudent(student.id)" class="bg-gray-700 hover:bg-gray-900 text-white font-bold py-2 px-4 rounded">Supprimer</button></td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script setup>
import { computed, onMounted, ref } from 'vue';
import { useRosterStore, backUrl, appUrl } from '../stores/roster';
import { useUserStore} from '../stores/user';

const store = useRosterStore();
const userStore = useUserStore();
const rosters = computed(() => store.rosters);
const allUsers = computed(() => userStore.allUsers);
const detailedRoster = ref([]);
const showDetails = ref(false);

let selectedRoster = ref('');
let studentToDelete = ref('');

onMounted(async () => {
    await store.fetchRosters();
});

async function displayRosterInfo() {
    detailedRoster.value = await store.fetchStudentsFromRoster(selectedRoster.value);
    await userStore.fetchAllUsers();
    showDetails.value = true;
}

async function deleteStudent(studentId) {
    const res = await store.deleteStudentFromRoster(selectedRoster.value, studentId);
    detailedRoster.value = res.students;
}


</script>