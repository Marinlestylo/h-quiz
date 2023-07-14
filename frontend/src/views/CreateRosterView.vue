<template>
    <div class="flex items-center mb-16 justify-between">
        <div class="text-3xl">
            Création d'un roster
        </div>
        <div>
            <RouterLink to="/rosters" class="bg-gray-700 hover:bg-gray-900 text-white font-bold py-2 px-4 rounded ml-64">
                Retour vers tous les rosters
            </RouterLink>
        </div>
    </div>

    <div class="w-full">
        <div class="mb-6 flex items-center">
            <label for="name" class="block mb-2 text-lg font-medium text-gray-900 w-64">Nom du roster</label>
            <input type="text" id="name" v-model="roster.name"
                class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 "
                placeholder="A" autocomplete="off">
        </div>
        <div class="mb-6 flex items-center">
            <label for="course" class="block mb-2 text-lg font-medium text-gray-900 w-64">Cours</label>
            <input type="text" id="course" list="courses" v-model="roster.course"
                class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 "
                placeholder="MAT1" autocomplete="off">
            <datalist id="courses" v-if="courses !== null">
                <option v-for="course in courses.courses" :key="course.id">{{ course.name }}</option>
            </datalist>
        </div>
        <div class="mb-6 flex items-center">
            <label for="semestre" class="block mb-2 text-lg font-medium text-gray-900 w-64">Semestre
                <div class="text-sm">
                    <div class="text-xs font-thin">0 = Automne</div>
                    <div class="text-xs font-thin">1 = Printemps</div>
                </div>
            </label>
            <input type="number" id="semestre" min="0" max="1" v-model="roster.semester"
                class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 "
                autocomplete="off" placeholder="0">
        </div>
        <div class="mb-6 flex items-center">
            <label for="year" class="block mb-2 text-lg font-medium text-gray-900 w-64">Année</label>
            <input type="number" id="year" min="1900" max="2100" v-model="roster.year"
                class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 "
                placeholder="2023" autocomplete="off">
        </div>
        
        <button @click="createRoster"
            class="bg-gray-700 hover:bg-gray-900 text-white font-bold py-2 px-4 rounded flex ml-auto">Création du
            roster
        </button>

        <AlertPopup v-model:message="errorMessage" alertType="error" class="mt-4"/>
        <AlertPopup v-model:message="successMessage" alertType="success" class="mt-4"/>

    </div>
</template>

<script setup>
import { computed, onMounted, ref } from 'vue';
import { useRosterStore } from '../stores/roster';
import { useCourseStore } from '../stores/course';
import AlertPopup from '@/components/AlertPopup.vue';

const store = useRosterStore();
const courseStore = useCourseStore();
const courses = computed(() => courseStore.allCourses);

const roster = ref({
    name: null,
    course: null,
    semester: null,
    year: null
});
const errorMessage = ref('');
const successMessage = ref('');

onMounted(async () => {
    await courseStore.fetchAllCourses();
});

async function createRoster() {
    if (!validateInpute()) {
        errorMessage.value = "Certains champs ne sont pas remplis correctement";
        return;
    }

    const [status, data] = await store.createRoster({
        'name': roster.value.name,
        'course_id': getCourseId(roster.value.course),
        'semester': roster.value.semester,
        'year': roster.value.year
    });

    if (status === 200) {
        successMessage.value = `Le roster "${data.roster.name}" a bien été créé`;
        resetFields();
    } else {
        errorMessage.value = data.message;
    }
}

function getCourseId(courseName) {
    const course = courses.value.courses.find(course => course.name === courseName);
    if (course != null) {
        return course.id;
    }
}

function validateInpute() {
    if (roster.value.name == null || roster.value.name === '') {
        return false;
    }
    if (!getCourseId(roster.value.course) || roster.value.course == null || roster.value.course === '') {
        return false;
    }
    if (roster.value.semester == null || roster.value.semester === '' || roster.value.semester < 0 || roster.value.semester > 1
        || !Number.isInteger(roster.value.semester)) {
        return false;
    }
    if (roster.value.year == null || roster.value.year === '' || !Number.isInteger(roster.value.year)) {
        return false;
    }
    return true;
}

const resetFields = () => {
    roster.value.name = null;
    roster.value.course = null;
    roster.value.semester = null;
    roster.value.year = null;
    errorMessage.value = '';
}


</script>