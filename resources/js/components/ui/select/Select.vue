<script setup lang="ts">
import { SelectRoot, type SelectRootEmits, type SelectRootProps } from 'reka-ui'

const props = defineProps<SelectRootProps>()
const emits = defineEmits<SelectRootEmits>()
</script>

<template>
    <SelectRoot v-bind="props" @update:model-value="emits('update:modelValue', $event)">
        <slot />
    </SelectRoot>
</template>
