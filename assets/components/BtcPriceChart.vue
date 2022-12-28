<script setup>
import {computed, onMounted, reactive, ref, watch} from 'vue';
import 'chartjs-adapter-date-fns';
import {Line} from 'vue-chartjs';
import {Chart, LineElement, PointElement, LineController, LinearScale, TimeScale, Filler, Tooltip} from 'chart.js';
import {useBtcApiStore} from '../stores/btcapistore';
import useBtcPrices from '../composables/btcprices';

Chart.register(LineElement, PointElement, LineController, LinearScale, TimeScale, Filler, Tooltip);

const store = useBtcApiStore();
const {getBtcPriceHistory} = useBtcPrices();

const state = reactive({
  btcPrices: [],
});

const data = computed(() => ({
  datasets: [
    {
      backgroundColor: 'rgba(31, 27, 89, 0.6)',
      fill: true,
      label: 'Price',
      data: state.btcPrices,
      tension: 0.1,
    },
  ],
}));

const options = ref({
  aspectRatio: 3,
  plugins: {
    legend: {
      display: false,
    },
    tooltip: {
      callbacks: {
        label: (context) => {
          let label = `${context.dataset.label}: `;

          if (context.parsed.y !== null) {
            label += context.dataset.data[context.dataIndex].priceFormatted;
          }

          return label;
        },
      },
    },
  },
  interaction: {
    intersect: false,
  },
  scales: {
    x: {
      type: 'time',
      time: {
        displayFormats: {
          'day': 'MMM dd',
        },
        round: 'day',
        tooltipFormat: 'MMM dd',
        unit: 'day',
      },
    },
    y: {
      type: 'linear',
      beginAtZero: true,
      grace: '10%',
    },
  },
});

const setBtcPriceData = () => {
  getBtcPriceHistory(store.currency)
      .then(({prices}) => {
        if (prices.length === 0) {
          state.btcPrices = prices;
        }

        state.btcPrices = prices.map(({timestamp, price, priceFormatted}) => ({
          x: new Date(timestamp),
          y: price,
          priceFormatted,
        }));
      });
};

onMounted(() => setBtcPriceData());
watch(() => store.currency, () => setBtcPriceData());
</script>

<template>
  <Line :options="options" :data="data"/>
</template>
