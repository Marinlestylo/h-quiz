<template>
    <header class="w-full bg-gray-900 p-4">
        <nav class="text-white flex items-center">
            <a href="https://heig-vd.ch" target="_blank">
                <img src="@/assets/images/logo-heig-vd.svg" alt="Logo" class="h-12 inline-block" />
            </a>
            <RouterLink to="/" class="ml-4 hover:text-gray-300">
                <div>
                    <img src="@/assets/images/quiz.svg" alt="Logo" class="h-12 inline-block" />
                </div>
            </RouterLink>
            <RouterLink to="/quizzes" class="ml-4 hover:text-gray-300">Quizzes</RouterLink>
            <RouterLink to="/sandbox" class="ml-4 hover:text-gray-300">Bac à sable</RouterLink>
            <RouterLink to="/create-question" class="ml-4 hover:text-gray-300">Créer une Question</RouterLink>
            <RouterLink to="/update-question" class="ml-4 hover:text-gray-300">Modifier une Question</RouterLink>
            <RouterLink to="/create-quiz" class="ml-4 hover:text-gray-300">Créer un Quiz</RouterLink>
            <RouterLink to="/update-quiz" class="ml-4 hover:text-gray-300">Modifier un Quiz</RouterLink>
            <RouterLink to="/create-roster" class="ml-4 hover:text-gray-300">Créer un Roster</RouterLink>
            <RouterLink to="/roster" class="ml-4 hover:text-gray-300">Roster</RouterLink>
        </nav>
    </header>
</template>