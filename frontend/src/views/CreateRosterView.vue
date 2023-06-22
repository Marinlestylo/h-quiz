<template>
    <div class="text-3xl mb-16 mx-48">
        Création d'un roster
    </div>

    <div class="w-full">
        <div class="mb-6 flex items-center">
            <label for="name" class="block mb-2 text-lg font-medium text-gray-900 w-64">Nom du roster</label>
            <input type="text" id="name"
                class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 "
                placeholder="A" autocomplete="off">
        </div>
        <div class="mb-6 flex items-center">
            <label for="course" class="block mb-2 text-lg font-medium text-gray-900 w-64">Cours</label>
            <input type="text" id="course"
                class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 "
                placeholder="MAT1" autocomplete="off">
        </div>
        <div class="mb-6 flex items-center">
            <label for="semestre" class="block mb-2 text-lg font-medium text-gray-900 w-64">Semestre
                <div class="text-sm">
                    <div class="text-xs font-thin">0 = Automne</div>
                    <div class="text-xs font-thin">1 = Printemps</div>
                </div>
            </label>
            <input type="number" id="semestre" min="0" max="1"
                class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 "
                autocomplete="off" placeholder="0">
        </div>
        <div class="mb-6 flex items-center">
            <label for="year" class="block mb-2 text-lg font-medium text-gray-900 w-64">Année</label>
            <input type="number" id="year" min="1900" max="2100"
                class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 "
                placeholder="2023" autocomplete="off">
        </div>
        <button class="bg-gray-700 hover:bg-gray-900 text-white font-bold py-2 px-4 rounded flex ml-auto">Création du roster</button>

    </div>
</template>

<script setup>
import { computed, onMounted, ref } from 'vue';
import { useRosterStore } from '../stores/roster';



</script>