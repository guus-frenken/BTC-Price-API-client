<script setup>
import {computed, onMounted, reactive, ref, watch} from 'vue';
import 'chartjs-adapter-date-fns';
import {LineChart} from 'vue-chart-3';
import {Chart, LineElement, PointElement, LineController, LinearScale, TimeScale, Filler, Tooltip} from 'chart.js';
import useBtcPrices from '../composables/btcprices';

Chart.register(LineElement, PointElement, LineController, LinearScale, TimeScale, Filler, Tooltip);
const {getBtcPriceHistory} = useBtcPrices();

const props = defineProps({
  currency: {
    type: String,
    default: 'eur',
  },
});

const state = reactive({
  btcPrices: {
    loading: false,
    data: [],
  },
});

const chartData = computed(() => ({
  datasets: [
    {
      backgroundColor: 'rgba(31, 27, 89, 0.6)',
      fill: true,
      label: 'Price',
      data: state.btcPrices.data,
      tension: 0.1,
    },
  ],
}));

const options = ref({
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
    mode: 'nearest',
    axis: 'x',
  },
  scales: {
    x: {
      type: 'time',
      time: {
        round: 'day',
        unit: 'day',
        displayFormats: {
          'day': 'MMM dd',
        },
        tooltipFormat: 'MMM dd',
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
  state.btcPrices.loading = true;

  getBtcPriceHistory(props.currency)
      .then(({prices}) => {
        if (prices.length === 0) {
          state.btcPrices.data = prices;
        }

        state.btcPrices.data = prices.map(({timestamp, price, priceFormatted}) => ({
          x: new Date(timestamp),
          y: price,
          priceFormatted,
        }));

        state.btcPrices.loading = false;
      });
};

onMounted(() => setBtcPriceData());
watch(() => props.currency, () => setBtcPriceData());
</script>

<template>
  <LineChart :chartData="chartData" :options="options"/>
</template>
