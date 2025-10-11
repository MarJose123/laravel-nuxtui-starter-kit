<script setup lang="ts">
import { useAppearance } from '@/composables/useAppearance'
import { NeutralColor, useColorUi } from '@/composables/useColorUi'
import Layout from '@/layouts/auth.vue'
import { Notification } from '@/types/notification'
import { router } from '@inertiajs/vue3'
import type { FormSubmitEvent } from '@nuxt/ui'
import { onMounted, reactive, watch } from 'vue'

defineOptions({ layout: Layout })

const props = defineProps<{
    canResetPassword: boolean
    canRegister: boolean
    notification: Notification | null
}>()

const toast = useToast()
const { updateAppearance } = useAppearance()
const { primaryColor, neutralColor, updateUi } = useColorUi()

const fields = reactive([
    {
        name: 'email',
        type: 'text' as const,
        label: 'Email',
        placeholder: 'Enter your email',
        required: true,
        autofocus: true,
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
    fields.forEach((field) => {
        if (field.hasOwnProperty('error')) {
            field.error = false
        }
    })

    router.post(route('login.store'), payload?.data, {
        onSuccess: (response) => {
            updateAppearance(response.props.theme.mode)
            // update the app ui color based on the user preference
            updateUi(
                response.props.theme.primary ?? primaryColor,
                (response.props.theme.neutral as NeutralColor) ?? neutralColor,
            )
        },
        onError: (errors) => {
            for (const errorsKey in errors) {
                fields[fields.findIndex((e) => e.name === errorsKey)].error = errors[errorsKey]
            }
        },
    })
}

watch(
    () => props.notification,
    (notification) => {
        if (notification) {
            toast.add({
                title:
                    (notification.title ?? notification.type === 'success') ? 'Success' : 'Opps! Something went wrong',
                description: notification.message,
                color: notification.type === 'success' ? 'success' : 'error',
                icon: notification.type === 'success' ? 'i-heroicons-check-circle' : 'i-heroicons-x-circle',
                duration: 5000,
            })
        }
    },
)

onMounted(() => {
    if (props.notification) {
        toast.add({
            title:
                (props.notification.title ?? props.notification.type === 'success')
                    ? 'Success'
                    : 'Opps! Something went wrong',
            description: props.notification.message,
            color: props.notification.type === 'success' ? 'success' : 'error',
            icon: props.notification.type === 'success' ? 'i-heroicons-check-circle' : 'i-heroicons-x-circle',
            duration: 5000,
        })
    }
})
</script>

<template>
    <UAuthForm
        class="max-w-md"
        title="Login"
        description="Enter your credentials to access your account."
        icon="i-heroicons-rocket-launch"
        :fields="fields"
        :submit="{ label: 'Login' }"
        @submit="onSubmit"
    >
        <template #footer v-if="props.canRegister">
            Don't have an account?
            <ULink :to="route('register', {}, false)" target="_self" class="font-medium text-primary">Sign up</ULink>.
        </template>
        <template #password-hint v-if="props.canResetPassword">
            <ULink
                :to="route('password.request', {}, false)"
                target="_self"
                class="font-medium text-primary"
                tabindex="-1"
                >Forgot password?</ULink
            >
        </template>
    </UAuthForm>
</template>
