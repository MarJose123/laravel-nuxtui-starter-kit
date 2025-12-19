<script setup lang="ts">
import { router } from '@inertiajs/vue3'
import type { BreadcrumbItem } from '@nuxt/ui'
import { onMounted, onUnmounted, ref } from 'vue'

const props = defineProps<{
    breadcrumbItems: BreadcrumbItem[]
}>()

const toast = useToast()
const flashEventListener = ref()

onMounted(() => {
    flashEventListener.value = router.on('flash', (event) => {
        if (event.detail.flash.notification) {
            toast.add({
                title: event.detail.flash.notification?.title,
                description: event.detail.flash.notification.message,
                color: event.detail.flash.notification?.color,
                icon: event.detail.flash.notification?.icon,
            })
        }
    })
})

onUnmounted(() => {
    if (flashEventListener.value) {
        flashEventListener.value()
    }
})
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
