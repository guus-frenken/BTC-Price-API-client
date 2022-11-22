<script setup>
import {onMounted, reactive} from 'vue';
import {useBtcApiStore} from '../stores/btcapistore';
import useCurrencies from '../composables/currencies';

const store = useBtcApiStore();
const {getCurrencies} = useCurrencies();

const state = reactive({
  currencies: [],
});

onMounted(() => {
  getCurrencies().then(({currencies}) => {
    state.currencies = currencies;
  });
});
</script>

<template>
  <select
      @input="store.setCurrency($event.target.value)"
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
    <option
        v-for="({value, label}, index) in state.currencies"
        :key="index"
        :value="value"
    >{{ label }}
    </option>
  </select>
</template>
