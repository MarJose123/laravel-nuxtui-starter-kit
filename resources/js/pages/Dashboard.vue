<script setup lang="ts">
import PlaceholderPattern from '@/components/PlaceholderPattern.vue'
import Layout from '@/layouts/default.vue'
import { Head } from '@inertiajs/vue3'
import { ref } from 'vue'

defineOptions({ layout: Layout })

const breadcrumbItems = ref([
    {
        label: 'Home',
    },
    {
        label: 'Dashboard',
        to: route('dashboard', {}, false),
        target: '_self',
    },
])
</script>

<template>
    <AppLayout :breadcrumbItems="breadcrumbItems">
        <Head title="Dashboard" />

        <div class="flex h-full flex-1 flex-col gap-4 overflow-x-auto rounded-xl p-4">
            <div class="grid auto-rows-min gap-4 md:grid-cols-3">
                <div
                    class="border-sidebar-border/70 dark:border-sidebar-border relative aspect-video overflow-hidden rounded-xl border"
                >
                    <PlaceholderPattern class="h-full w-full" />
                </div>
                <div
                    class="border-sidebar-border/70 dark:border-sidebar-border relative aspect-video overflow-hidden rounded-xl border"
                >
                    <PlaceholderPattern class="h-full w-full" />
                </div>
                <div
                    class="border-sidebar-border/70 dark:border-sidebar-border relative aspect-video overflow-hidden rounded-xl border"
                >
                    <PlaceholderPattern class="h-full w-full" />
                </div>
            </div>
            <div
                class="border-sidebar-border/70 dark:border-sidebar-border relative min-h-[100vh] flex-1 rounded-xl border md:min-h-min"
            >
                <PlaceholderPattern class="h-full w-full" />
            </div>
        </div>
    </AppLayout>
</template>

<style scoped></style>
