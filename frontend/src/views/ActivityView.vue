<template>

    <ActivityComponent/>

</template>

<script setup>
import { computed } from 'vue';
import { useUserStore } from '@/stores/user';
import StudentActivity from '@/components/StudentActivity.vue';
import TeacherActivity from '@/components/TeacherActivity.vue';

const store = useUserStore();
const user = computed(() => store.user);

const ActivityComponent = computed(() => {
    if (user.value.role.includes('staff')) {
        return TeacherActivity;
    } else {
        return StudentActivity;
    }
});
</script>