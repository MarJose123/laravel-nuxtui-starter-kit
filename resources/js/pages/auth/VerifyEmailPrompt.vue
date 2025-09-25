<script setup lang="ts">
import Logo from '@/components/Logo.vue'
import Layout from '@/layouts/auth.vue'
import { Head, router, useForm } from '@inertiajs/vue3'
import { ref } from 'vue'

defineOptions({ layout: Layout })

const newVerificationLinkSent = ref(false)

const form = useForm({})

const onSubmit = () => {
    form.post(route('verification.send'), {
        onSuccess: () => {
            newVerificationLinkSent.value = true
        },
        preserveState: true,
    })
}
</script>

<template>
    <div class="flex">
        <Head title="Email verification" />

        <!-- START: Register Form Root Layout -->
        <div class="w-full max-w-sm space-y-6">
            <!--  START:  Header Section        -->
            <div class="flex flex-col text-center">
                <div class="mb-2">
                    <Logo class="inline-block size-8 shrink-0" />
                </div>

                <div class="text-xl font-semibold text-pretty text-highlighted">Verify email</div>
                <div class="mt-1 text-base text-pretty text-muted">
                    Please verify your email address by clicking on the link we just emailed to you.
                </div>
            </div>
            <!--   END: Header Section        -->

            <UAlert
                v-if="newVerificationLinkSent"
                color="success"
                title="Email Verification Sent!"
                description=" A new verification link has been sent to the email address you provided during registration."
                icon="i-heroicons:information-circle"
            />

            <!--  START:  Form Section       -->
            <div class="flex flex-col gap-y-6">
                <UForm class="space-y-5" @submit.prevent="onSubmit">
                    <UButton
                        label="Resend verification email"
                        type="submit"
                        block
                        :disabled="form.processing"
                        :loading="form.processing"
                        class="w-full"
                    />
                </UForm>
            </div>

            <div class="mt-2 text-center text-sm text-muted">
                <UButton color="primary" @click.prevent="router.post(route('logout'))" variant="link">Log out</UButton>.
            </div>
        </div>
    </div>
</template>

<style scoped></style>
