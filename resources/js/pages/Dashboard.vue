<script setup lang="ts">
import Layout from '@/layouts/default.vue'
import { router } from '@inertiajs/vue3'
import { ref } from 'vue'

defineOptions({ layout: Layout })

const breadcrumbItems = ref([
    {
        label: 'Home',
    },
    {
        label: 'Dashboard',
        to: '/dashboard',
    },
])
</script>

<template>
    <UDashboardPanel>
        <template #header>
            <UDashboardNavbar>
                <template #title>
                    <UBreadcrumb :items="breadcrumbItems" />
                </template>
                <template #leading>
                    <UDashboardSidebarCollapse />
                </template>
            </UDashboardNavbar>
        </template>
        <template #body>
            <div>
                <span>Dashboard</span>
                <UButton @click.prevent="router.post(route('logout'))">Logout</UButton>
            </div>
        </template>
    </UDashboardPanel>
</template>

<style scoped></style>
