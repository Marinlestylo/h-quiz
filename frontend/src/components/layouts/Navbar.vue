<template>
    <header class="w-full bg-gray-900 p-4">
        <nav class="text-white flex items-center">
            <a href="https://heig-vd.ch" target="_blank">
                <img src="@/assets/images/logo-heig-vd.svg" alt="Logo" class="h-12 inline-block" />
            </a>
            <div>
                <img src="@/assets/images/quiz.svg" alt="Logo" class="h-12 inline-block" />
            </div>
            <RouterLink to="/" class="ml-4 hover:text-gray-300">Home</RouterLink>
            <RouterLink to="/about" class="ml-4 hover:text-gray-300">About</RouterLink>
        </nav>
    </header>
</template>