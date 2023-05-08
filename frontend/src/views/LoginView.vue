<template>
    <main>
        <div class="w-full">
            <button class="bg-gray-800 hover:bg-gray-600 text-white font-bold py-2 px-4 rounded"
                @click="redirect">
                Se connecter
            </button>
        </div>
    </main>
</template>

<script setup>
    function redirect() {
        window.location.href = 'http://localhost:8000/api/login';
    }
</script>