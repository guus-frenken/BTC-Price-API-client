<script setup>
import {onMounted, reactive, watch} from 'vue';
import {useBtcApiStore} from '../stores/btcapistore';
import useBtcPrices from '../composables/btcprices';

const store = useBtcApiStore();
const {getCurrentBtcPrice} = useBtcPrices();

const state = reactive({
  btcPrice: null,
});

const setCurrentBtcPrice = () => {
  getCurrentBtcPrice(store.currency)
      .then(({price: {priceFormatted}}) => {
        state.btcPrice = priceFormatted;
      });
};

onMounted(() => setCurrentBtcPrice());
watch(() => store.currency, () => setCurrentBtcPrice());
</script>

<template>
  <p class="text-xl">Current BTC Price: {{ state.btcPrice }}</p>
</template>
