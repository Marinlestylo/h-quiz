<template>
    <TransitionRoot appear :show="isOpen" as="template">
        <Dialog as="div" @close="closeModal" class="relative z-10">
            <TransitionChild as="template" enter="duration-300 ease-out" enter-from="opacity-0" enter-to="opacity-100"
                leave="duration-200 ease-in" leave-from="opacity-100" leave-to="opacity-0">
                <div class="fixed inset-0 bg-black bg-opacity-25"></div>
            </TransitionChild>

            <div class="fixed inset-0 overflow-y-auto">
                <div class="flex min-h-full items-center justify-center p-4 text-center">
                    <TransitionChild as="template" enter="duration-300 ease-out" enter-from="opacity-0 scale-95"
                        enter-to="opacity-100 scale-100" leave="duration-200 ease-in" leave-from="opacity-100 scale-100"
                        leave-to="opacity-0 scale-95">
                        <DialogPanel
                            class="w-full max-w-md transform overflow-hidden rounded-2xl bg-white p-6 text-left align-middle shadow-xl transition-all">
                            <DialogTitle as="h3" class="text-lg font-medium leading-6 text-gray-900">
                                Création d'une nouvelle activité
                            </DialogTitle>
                            <div class="mt-2">
                                <div class="text-sm text-gray-500">
                                    Vous êtes sur le point de créer une nouvelle activité à partir du quiz
                                    <span class="font-medium"> "{{ quizName }}"</span>. Il faudra encore la démarrer
                                    pour qu'elle soit visible par les étudiants.
                                </div>
                                <AlertPopup v-model:message="errorMessage" alertType="error" class="mt-4"/>
                                <AlertPopup v-model:message="successMessage" alertType="success" class="mt-4"/>
                                <div>
                                    <div class="mt-4">
                                        <label for="name"
                                            class="block mb-2 text-lg font-medium text-gray-900 w-64">Classe</label>
                                        <select v-model="activity.rosterId" id="id_roster"
                                            class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5">
                                            <option disabled selected value="">Choisissez un roster</option>
                                            <option v-for="roster in myRosters" :key="roster.id" v-bind:value="roster.id">{{
                                                roster.name }} / {{ roster.semester }} / {{ roster.year }}</option>
                                        </select>
                                    </div>
                                    <div class="mt-4">
                                        <label for="duration"
                                            class="block mb-2 text-lg font-medium text-gray-900 w-64">Durée de l'activité
                                            [minutes]</label>
                                        <input type="number" id="duration" v-model="activity.duration" min="1"
                                            class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 "
                                            placeholder="10" autocomplete="off">
                                    </div>
                                    <div class="mt-4">
                                        <label class="block">
                                            <input class="mr-2 " type="checkbox" v-model="activity.shuffleQuestion">
                                            <span class="text-sm">
                                                Mélanger les questions
                                            </span>
                                        </label>
                                    </div>
                                    <div class="mt-2">
                                        <label class="block">
                                            <input class="mr-2 " type="checkbox" v-model="activity.shuffleProposition">
                                            <span class="text-sm">
                                                Mélanger les propositions multiples
                                            </span>
                                        </label>
                                    </div>
                                </div>
                            </div>

                            <div class="mt-4 flex justify-between">
                                <button type="button"
                                    class="inline-flex justify-center rounded-md border border-transparent bg-gray-200 px-4 py-2 text-sm font-medium text-gray-900 hover:bg-gray-300 focus:outline-none focus-visible:ring-2 focus-visible:ring-gray-500 focus-visible:ring-offset-2"
                                    @click="closeModal">
                                    Annuler
                                </button>
                                <button type="button"
                                    class="inline-flex justify-center rounded-md border border-transparent bg-gray-700 px-4 py-2 text-sm font-medium text-white hover:bg-gray-900 focus:outline-none focus-visible:ring-2 focus-visible:ring-gray-500 focus-visible:ring-offset-2"
                                    @click="createActivity">
                                    Créer l'activité
                                </button>
                            </div>
                        </DialogPanel>
                    </TransitionChild>
                </div>
            </div>
        </Dialog>
    </TransitionRoot>
    
</template>
  
<script setup>
import { computed, onMounted, ref } from 'vue'
import {
    TransitionRoot,
    TransitionChild,
    Dialog,
    DialogPanel,
    DialogTitle,
} from '@headlessui/vue'
import AlertPopup from '@/components/AlertPopup.vue'
import { useRosterStore } from '@/stores/roster'
import { useUserStore } from '@/stores/user'
import { useActivityStore } from '@/stores/activity'

const rosterStore = useRosterStore()
const rosters = computed(() => rosterStore.rosters);

const userStore = useUserStore()
const user = computed(() => userStore.user);

const activityStore = useActivityStore()
const myRosters = ref([]);

const errorMessage = ref('');
const successMessage = ref('');

const props = defineProps({
    isOpen: Boolean,
    quizId: Number,
    quizName: String
});

const emits = defineEmits(['update:isOpen']);

const activity = ref({
    rosterId: '',
    duration: '',
    shuffleQuestion: false,
    shuffleProposition: false
});

onMounted(async () => {
    await rosterStore.fetchRosters(); //TODO : Utile ?
    myRosters.value = rosters.value.filter(roster => roster.teacher.id === user.value.id);
})

async function createActivity(){
    const [status, data] = await activityStore.createActivity(activity.value.duration, props.quizId, activity.value.rosterId, activity.value.shuffleQuestion, activity.value.shuffleProposition);
    if (status === 200) {
        successMessage.value = "L'activité a bien été créée.";
        clearFields();
        errorMessage.value = '';
        await new Promise(r => setTimeout(r, 2000));
        closeModal();
    } else {
        errorMessage.value = data.message;
    }
}

function clearFields() {
    activity.value = {
        rosterId: '',
        duration: '',
        shuffleQuestion: false,
        shuffleProposition: false
    }
}

function closeModal() {
    emits('update:isOpen', false)
    clearFields()
    errorMessage.value = ''
    successMessage.value = ''
}
</script>
  