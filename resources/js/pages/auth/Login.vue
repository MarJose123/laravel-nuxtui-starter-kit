<script setup lang="ts">
import Layout from '@/layouts/auth.vue'
import { router } from '@inertiajs/vue3'
import type { FormSubmitEvent } from '@nuxt/ui'
import { reactive } from 'vue'

defineOptions({ layout: Layout })

const props = defineProps<{
    canResetPassword: boolean
    canSignUp: boolean
}>()

const fields = reactive([
    {
        name: 'email',
        type: 'text' as const,
        label: 'Email',
        placeholder: 'Enter your email',
        required: true,
        error: false as boolean | string,
    },
    {
        name: 'password',
        label: 'Password',
        type: 'password' as const,
        required: true,
        placeholder: 'Enter your password',
        error: false as boolean | string,
    },
    {
        name: 'remember',
        label: 'Remember me',
        type: 'checkbox' as const,
    },
])

function onSubmit(payload: FormSubmitEvent<any>) {
    console.log('Submitted', payload)

    fields.forEach((field) => {
        if (field.hasOwnProperty('error')) {
            field.error = false
        }
    })

    router.post(route('login.store'), payload?.data, {
        onError: (errors) => {
            for (const errorsKey in errors) {
                fields[fields.findIndex((e) => e.name === errorsKey)].error =
                    errors[errorsKey]
            }
        },
    })
}
</script>

<template>
    <div
        class="flex flex-col items-center justify-center gap-4 p-4 pt-2 md:pt-16"
    >
        <UAuthForm
            class="max-w-md"
            title="Login"
            description="Enter your credentials to access your account."
            icon="i-heroicons-rocket-launch"
            :fields="fields"
            :submit="{ label: 'Login' }"
            @submit="onSubmit"
        >
            <template #footer v-if="props.canSignUp">
                Don't have an account?
                <ULink to="#" class="font-medium text-primary">Sign up</ULink>.
            </template>
            <template #password-hint v-if="props.canResetPassword">
                <ULink to="#" class="font-medium text-primary" tabindex="-1"
                    >Forgot password?</ULink
                >
            </template>
        </UAuthForm>
    </div>
</template>
