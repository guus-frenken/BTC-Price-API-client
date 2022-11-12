<script setup>
defineProps({
  className: String,
});

defineEmits(['onChangeCurrency']);
</script>

<template>
  <select
      @input="$emit('onChangeCurrency', $event.target.value)"
      :class="className"
      class="form-select appearance-none
      px-3
      py-1.5
      text-base
      font-normal
      text-gray-700
      bg-white bg-clip-padding bg-no-repeat
      border border-solid border-gray-300
      rounded
      transition
      ease-in-out
      m-0
      focus:text-gray-700 focus:bg-white focus:border-blue-600 focus:outline-none"
  >
    <option value="eur">EUR</option>
    <option value="usd">USD</option>
  </select>
</template>
