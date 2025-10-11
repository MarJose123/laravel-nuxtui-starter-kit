<script setup lang="ts"></script>

<template>
    <UApp>
        <UMain>
            <UDashboardGroup as="div" storage="local" storage-key="dashboard" :persistent="true">
                <SidebarNavigation />
                <slot />
            </UDashboardGroup>
        </UMain>
    </UApp>
</template>

<style scoped></style>
