<script setup lang="ts">
import { computed, ref } from 'vue'

const props = defineProps<{
    label?: string
    error?: string
    modelValue: string
    name?: string
    placeholder?: string
}>()

const emit = defineEmits<{
    'update:modelValue': [value: string]
}>()

const showPassword = ref(false)

const checkStrength = (str: string) => {
    const requirements = [
        { regex: /.{8,}/, text: 'At least 8 characters' },
        { regex: /[0-9]/, text: 'At least 1 number' },
        { regex: /[a-z]/, text: 'At least 1 lowercase letter' },
        { regex: /[A-Z]/, text: 'At least 1 uppercase letter' },
        {
            regex: /[!@#$%^&*()_+\-=\[\]{};':"\\|,.<>\/?]/,
            text: 'At least 1 symbol',
        },
    ]

    return requirements.map((req) => ({
        met: req.regex.test(str),
        text: req.text,
    }))
}

const strength = computed(() => checkStrength(props.modelValue))
const score = computed(() => strength.value.filter((req) => req.met).length)

const color = computed(() => {
    if (score.value === 0) return 'neutral'
    if (score.value <= 1) return 'error'
    if (score.value <= 2) return 'warning'
    if (score.value === 3) return 'warning'
    return 'success'
})

const text = computed(() => {
    if (score.value === 0) return 'Enter a password'
    if (score.value <= 2) return 'Weak password'
    if (score.value <= 3) return 'Medium password'
    return 'Strong password'
})
</script>

<template>
    <div class="space-y-2">
        <UFormField
            :label="label ?? 'Password'"
            :name="name ?? label?.toLowerCase() ?? 'password'"
            :error="error"
            required
        >
            <UInput
                :model-value="modelValue"
                @input="emit('update:modelValue', $event.target.value)"
                :placeholder="placeholder ?? 'Input your password'"
                :color="color"
                :type="showPassword ? 'text' : 'password'"
                :aria-invalid="score < 4"
                aria-describedby="password-strength"
                :ui="{ trailing: 'pe-1' }"
                class="w-full"
            >
                <template #trailing>
                    <UButton
                        color="neutral"
                        variant="link"
                        size="sm"
                        :icon="showPassword ? 'i-lucide-eye-off' : 'i-lucide-eye'"
                        :aria-label="showPassword ? 'Hide password' : 'Show password'"
                        :aria-pressed="showPassword"
                        aria-controls="password"
                        @click="showPassword = !showPassword"
                    />
                </template>
            </UInput>
        </UFormField>

        <UProgress :color="color" :indicator="text" :model-value="score" :max="4" size="sm" />

        <p id="password-strength" class="text-sm font-medium">{{ text }}. Must contain:</p>

        <ul class="space-y-1" aria-label="Password requirements">
            <li
                v-for="(req, index) in strength"
                :key="index"
                class="flex items-center gap-0.5"
                :class="req.met ? 'text-success' : 'text-muted'"
            >
                <UIcon :name="req.met ? 'i-lucide-circle-check' : 'i-lucide-circle-x'" class="size-4 shrink-0" />

                <span class="text-xs font-light">
                    {{ req.text }}
                    <span class="sr-only">
                        {{ req.met ? ' - Requirement met' : ' - Requirement not met' }}
                    </span>
                </span>
            </li>
        </ul>
    </div>
</template>

<style scoped></style>
