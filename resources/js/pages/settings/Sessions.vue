<script setup lang="ts">
import ApiSessions from '@/components/ApiSessions.vue'
import WebSessions from '@/components/WebSessions.vue'
import Layout from '@/layouts/default.vue'
import { ApiSession } from '@/types/apiSession'
import { WebSession } from '@/types/webSession'
import { Head } from '@inertiajs/vue3'
import type { BreadcrumbItem } from '@nuxt/ui'
import { ref } from 'vue'

defineOptions({ layout: Layout })

const breadcrumbItems = ref<BreadcrumbItem[]>([
    {
        label: 'Settings',
    },
    {
        label: 'Account',
        to: route('settings.account.edit', {}, false),
        target: '_self',
    },
])

defineProps<{
    webSessions: WebSession[]
    apiSessions: ApiSession[]
}>()
</script>

<template>
    <AppLayout :breadcrumbItems="breadcrumbItems">
        <Head title="Sessions" />

        <WebSessions :webSessions="webSessions" />

        <ApiSessions :apiSessions="apiSessions" />
    </AppLayout>
</template>

<style scoped></style>
