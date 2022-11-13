<script setup>
import CurrencyPicker from '../components/CurrencyPicker';
import CurrentBtcPrice from '../components/CurrentBtcPrice';
import BtcPriceChart from '../components/BtcPriceChart';
</script>

<template>
  <div class="container my-8">
    <div class="bg-white shadow-lg rounded-lg p-6">
      <div class="flex items-center justify-between mb-8">
        <CurrencyPicker/>
        <CurrentBtcPrice/>
      </div>
      <BtcPriceChart/>
    </div>
  </div>
</template>
