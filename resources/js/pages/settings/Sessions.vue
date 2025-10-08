<script setup lang="ts">
import ApiSessions from '@/components/ApiSessions.vue'
import WebSessions from '@/components/WebSessions.vue'
import Layout from '@/layouts/default.vue'
import { ApiSession } from '@/types/apiSession'
import { Notification } from '@/types/notification'
import { WebSession } from '@/types/webSession'
import { Head } from '@inertiajs/vue3'
import type { BreadcrumbItem } from '@nuxt/ui'
import { onMounted, ref, watch } from 'vue'

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

const props = defineProps<{
    webSessions: WebSession[]
    apiSessions: ApiSession[]
    notification: Notification | null
}>()
const toast = useToast()

onMounted(() => {
    if (props.notification) {
        toast.add({
            title: props.notification?.title
                ? props.notification?.title
                : props.notification?.type === 'success'
                  ? 'Success'
                  : 'Opps! Something went wrong',
            description: props.notification.message,
            color: props.notification?.type === 'success' ? 'success' : 'error',
            icon: props.notification?.type === 'success' ? 'i-heroicons-check-circle' : 'i-heroicons-x-circle',
            duration: 5000,
        })
    }
})

watch(
    () => props.notification,
    (notification) => {
        if (notification) {
            toast.add({
                title: notification.title
                    ? notification.title
                    : notification.type === 'success'
                      ? 'Success'
                      : 'Opps! Something went wrong',
                description: notification.message,
                color: notification.type === 'success' ? 'success' : 'error',
                icon: notification.type === 'success' ? 'i-heroicons-check-circle' : 'i-heroicons-x-circle',
            })
        }
    },
)
</script>

<template>
    <AppLayout :breadcrumbItems="breadcrumbItems">
        <Head title="Sessions" />

        <WebSessions :webSessions="webSessions" />

        <ApiSessions :apiSessions="apiSessions" />
    </AppLayout>
</template>

<style scoped></style>
