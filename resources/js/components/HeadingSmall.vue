<script setup lang="ts">
defineProps<{
    title: string
    description: string
}>()
</script>

<template>
    <header>
        <div class="flex flex-col">
            <div class="text-xl font-medium text-pretty text-highlighted">{{ title }}</div>
            <div v-if="description" class="mt-1 max-w-7/12 text-sm text-pretty text-muted">{{ description }}</div>
        </div>
    </header>
</template>

<style scoped></style>
