<template>
    <!-- Header of the page -->
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

    <!-- Roster selection -->
    <div>
        <select v-model="selectedRoster" @change="displayRosterInfo" id="id_roster"
            class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5">
            <option disabled selected value="">Choisissez un roster</option>
            <option v-for="roster in rosters" :key="roster.id" v-bind:value="roster.id">{{ roster.name }} / {{
                roster.semester }} / {{ roster.year }}</option>
        </select>
    </div>

    <!-- Roster details -->
    <div v-show="showDetails">
        <div class="text-xl mt-10 mb-4">
            Étudiants
        </div>

        <!-- Input to add Students -->
        <div class="flex">
            <label for="student" class="mb-2 text-sm font-medium text-gray-900 sr-only"></label>
            <div class="relative w-full">
                <div class="absolute inset-y-0 left-0 flex items-center pl-3 pointer-events-none">
                    <svg aria-hidden="true" class="w-5 h-5 text-gray-500" fill="none" stroke="currentColor"
                        viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                    </svg>
                </div>
                <input v-model="searchStudent"
                    class="block w-full p-4 pl-10 text-sm text-gray-900 border border-gray-300 rounded-lg bg-gray-50 focus:ring-blue-500 focus:border-blue-500"
                    list="students" name="student" id="student" placeholder="Entrez le nom d'un étudiant"
                    autocomplete="off">
                <datalist id="students" v-if="allStudents !== null">
                    <option v-for="stud in allStudents.students" :key="stud.id">{{ stud.user.firstname }} {{
                        stud.user.lastname }}</option>
                </datalist>
                <button type="submit" @click="addStudent"
                    class="text-white absolute right-2.5 bottom-2.5 bg-gray-700 hover:bg-gray-900 focus:ring-4 focus:outline-none focus:ring-gray-300 font-medium rounded-lg text-sm px-4 py-2">Ajouter
                    l'étudiant</button>
            </div>
        </div>

        <AlertPopup v-model:message="errorMessage" alertType="error"  class="my-4"/>
        <AlertPopup v-model:message="successMessage" alertType="success"  class="my-4"/>

        <!-- Table to display students -->
        <div v-if="showDetails && !detailedRoster.data.length" class="text-xl">Il n'y actuellement aucun étudiant dans
            ce roster.</div>
        <div v-else class="max-w-5xl rounded overflow-hidden shadow-lg border border-gray-400 bg-white rounded-b px-4 my-6">
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
                                        <td class="whitespace-nowrap px-6 py-4 font-medium"><button
                                                @click="deleteStudent(student.id)"
                                                class="bg-gray-700 hover:bg-gray-900 text-white font-bold py-2 px-4 rounded">Supprimer</button>
                                        </td>
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
import { useRosterStore } from '../stores/roster';
import { useStudentStore } from '../stores/student';
import AlertPopup from '../components/AlertPopup.vue';

const store = useRosterStore();
const studentStore = useStudentStore();
const rosters = computed(() => store.rosters);
const allStudents = computed(() => studentStore.allStudents);
const detailedRoster = ref([]);
const showDetails = ref(false);

let selectedRoster = ref('');
let searchStudent = ref('');
let errorMessage = ref('');
const successMessage = ref('');

onMounted(async () => {
    await store.fetchRosters();
});

async function displayRosterInfo() {
    resetFields();
    detailedRoster.value = await store.fetchStudentsFromRoster(selectedRoster.value);
    await studentStore.fetchAllStudents();
    showDetails.value = true;
}

async function deleteStudent(studentId) {
    const [status, data] = await store.deleteStudentFromRoster(selectedRoster.value, studentId);
    if (status === 200) {
        detailedRoster.value = data.students;
        errorMessage.value = '';
        const student = allStudents.value.students.find(u => u.id === studentId).user;
        const name = student.firstname + " " + student.lastname;
        successMessage.value = `L\'étudiant(e) ${name} a bien été supprimé(e) du roster`;
    } else {
        errorMessage.value = data.message;
        successMessage.value = '';
    }
}

async function addStudent() {
    if (!searchStudent.value) {
        errorMessage.value = 'Veuillez entrer un nom d\'étudiant';
        return;
    }
    const student = allStudents.value.students.find(u => u.user.firstname + " " + u.user.lastname === searchStudent.value);
    if (!student) {
        errorMessage.value = 'Cet étudiant n\'existe pas';
        return;
    }
    const [status, data] = await store.addStudentToRoster(selectedRoster.value, student.id);
    if (status === 200) {
        detailedRoster.value = data.students;
        searchStudent.value = '';
        errorMessage.value = '';
        const name = student.user.firstname + " " + student.user.lastname;
        successMessage.value = `L\'étudiant(e) ${name} a bien été ajouté(e) au roster`;
    } else {
        errorMessage.value = data.message;
        successMessage.value = '';
    }
}

const resetFields = () => {
    searchStudent.value = '';
    errorMessage.value = '';
}
</script>