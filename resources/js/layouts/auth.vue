<script setup lang="ts"></script>

<template>
    <UApp>
        <UMain>
            <UContainer>
                <div class="flex flex-col items-center justify-center gap-4 p-4 pt-2 md:pt-16">
                    <slot />
                </div>
            </UContainer>
        </UMain>
    </UApp>
</template>

<style scoped></style>
