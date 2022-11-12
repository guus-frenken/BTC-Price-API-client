<script setup>
import {ref} from 'vue';
import BtcPriceChart from '../components/BtcPriceChart';
import CurrencyPicker from '../components/CurrencyPicker';

const currency = ref('eur');

const setCurrency = (newCurrency) => currency.value = newCurrency;
</script>

<template>
  <div class="container my-8">
    <div class="bg-white shadow-lg rounded-lg p-6">
      <CurrencyPicker @onChangeCurrency="setCurrency" class-name="mb-3"/>
      <BtcPriceChart :currency="currency"/>
    </div>
  </div>
</template>
