<script setup lang="ts">

import {router} from "@inertiajs/vue3";
</script>

<template>
<span>Dashboard</span>
    <UButton @click.prevent="router.post(route('logout'))">Logout</UButton>
</template>

<style scoped>

</style>
