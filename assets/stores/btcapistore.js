import {ref} from 'vue';
import {defineStore} from 'pinia';

export const useBtcApiStore = defineStore('btcapi', () => {
    const currency = ref('EUR');

    const setCurrency = (newCurrency) => currency.value = newCurrency;

    return {currency, setCurrency};
});
