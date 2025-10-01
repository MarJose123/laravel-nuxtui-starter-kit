<script setup lang="ts">
import TwoFactorSetupModal from '@/components/TwoFactorSetupModal.vue'
import { useTwoFactorAuth } from '@/composables/useTwoFactorAuth'
import Layout from '@/layouts/default.vue'
import { Head, router } from '@inertiajs/vue3'
import type { BreadcrumbItem } from '@nuxt/ui'
import { onUnmounted, ref } from 'vue'

defineOptions({ layout: Layout })

interface Props {
    requiresConfirmation: boolean
    twoFactorEnabled: boolean
}

withDefaults(defineProps<Props>(), {
    requiresConfirmation: false,
    twoFactorEnabled: false,
})

const breadcrumbItems = ref<BreadcrumbItem[]>([
    {
        label: 'Settings',
    },
    {
        label: 'Two-Factor Authentication',
        to: route('settings.two-factor.show', {}, false),
        target: '_self',
    },
])

const { hasSetupData, clearTwoFactorAuthData } = useTwoFactorAuth()

const showSetupModal = ref(false)

onUnmounted(() => {
    clearTwoFactorAuthData()
})
</script>

<template>
    <AppLayout :breadcrumbItems="breadcrumbItems">
        <Head title="Two-Factor Authentication" />

        <div class="flex w-full flex-col space-y-6">
            <!--  START:  Header Section        -->
            <HeadingSmall
                title="Two-Factor Authentication"
                description="Manage your two-factor authentication settings"
            />
            <span class="flex w-7/12 border-b border-b-slate-200" />
            <!--   END: Header Section        -->

            <!--  START:  Form Section       -->

            <div v-if="!twoFactorEnabled" class="flex flex-col items-start justify-start space-y-4">
                <p class="text-muted-foreground w-5/12">
                    When you enable two-factor authentication, you will be prompted for a secure pin during login. This
                    pin can be retrieved from a TOTP-supported application on your phone.
                </p>

                <div>
                    <UButton
                        v-if="hasSetupData"
                        icon="i-lucide-shield-check"
                        label="Continue Setup"
                        @click.prevent="showSetupModal = true"
                    />
                    <UButton
                        v-else
                        icon="i-lucide-shield-check"
                        label="Enable 2FA"
                        @click.prevent="
                            router.post(
                                route('two-factor.enable'),
                                {},
                                {
                                    onSuccess: (resp) => {
                                        showSetupModal = true
                                    },
                                    only: ['twoFactorEnabled', 'requiresConfirmation'],
                                },
                            )
                        "
                    />
                </div>
            </div>

            <div v-else class="flex flex-col items-start justify-start space-y-4">
                <p class="text-muted-foreground w-6/12">
                    With two-factor authentication enabled, you will be prompted for a secure, random pin during login,
                    which you can retrieve from the TOTP-supported application on your phone.
                </p>

                <!--   START: RECOVERY CODE SECTION    -->
                <TwoFactorRecoveryCodes />
                <!--   END: RECOVERY CODE SECTION    -->

                <div class="relative inline">
                    <UButton
                        icon="i-lucide-shield-ban"
                        label="Disable 2FA"
                        color="error"
                        @click.prevent="
                            router.delete(route('two-factor.disable'), {
                                preserveScroll: true,
                                preserveState: true,
                            })
                        "
                    />
                </div>
            </div>
        </div>

        <TwoFactorSetupModal
            v-model:open="showSetupModal"
            :requiresConfirmation="requiresConfirmation"
            :twoFactorEnabled="twoFactorEnabled"
        />
    </AppLayout>
</template>

<style scoped></style>
