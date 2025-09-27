<script setup lang="ts">
import type { BreadcrumbItem } from '@nuxt/ui'

const props = defineProps<{
    breadcrumbItems: BreadcrumbItem[]
}>()
</script>

<template>
    <UDashboardPanel>
        <template #header>
            <UDashboardNavbar>
                <template #title>
                    <UDashboardSidebarCollapse as="button" :disabled="false" />
                    <UBreadcrumb :items="props.breadcrumbItems" />
                </template>
            </UDashboardNavbar>
        </template>

        <template #body>
            <slot />
        </template>
    </UDashboardPanel>
</template>

<style scoped></style>
