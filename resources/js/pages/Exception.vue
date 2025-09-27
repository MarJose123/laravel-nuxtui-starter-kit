<script setup lang="ts">
import { router } from '@inertiajs/vue3'

defineProps<{
    redirect: string
    error: object
}>()
</script>

<template>
    <UError :error="error">
        <template #links>
            <UButton size="lg" color="primary" variant="solid" label="Back to home" @click="router.visit(redirect)" />
        </template>
    </UError>
</template>

<style scoped></style>
