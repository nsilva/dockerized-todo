<script setup>
    import { ref, defineEmits } from 'vue';

    const props = defineProps({
        initialFormData: {
        type: Object,
        default: () => ({}),
        },
    });

    const emit = defineEmits(['formSubmitted']);

    const formData = ref(props.initialFormData);

    const submitForm = () => {
        // Emit the form data object
        emit('formSubmitted', formData.value);
    };
</script>

<template>
  <form @submit.prevent="submitForm">
    <slot></slot>
  </form>
</template>
