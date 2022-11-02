import Chart from 'chart.js/auto';
import 'chartjs-adapter-date-fns';

const getApiData = async (endpoint) => {
    const response = await fetch(`/api/${endpoint}`);

    if (response.status !== 200) {
        throw new Error(response.status);
    }

    return await response.json();
};

const getCurrentBtcPrice = async () => {
    const {price} = await getApiData(`btcprice/${window.currency}`);

    return price;
};

const getBtcPrices = async () => {
    const {prices} = await getApiData(`btcprice/${window.currency}/history`);

    return prices;
};

const prepareChartData = async () => {
    const prices = await getBtcPrices();

    if (prices.length === 0) {
        return [];
    }

    return prices.map(function (price) {
        return {
            x: new Date(price.timestamp),
            y: price.price,
            priceFormatted: price.priceFormatted,
        }
    });
};

const loadChart = (ctx, data) => {
    new Chart(ctx, {
        type: 'line',
        data: {
            datasets: [
                {
                    backgroundColor: 'rgba(31, 27, 89, 0.6)',
                    fill: true,
                    label: 'Price',
                    data: data,
                    tension: 0.1,
                },
            ],
        },
        options: {
            plugins: {
                legend: {
                    display: false,
                },
                tooltip: {
                    callbacks: {
                        label: function (context) {
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
        },
    });
};

(function () {
    window.currency = 'eur';

    const ctx = document.getElementById('btcPricesChart').getContext('2d');
    const priceElem = document.getElementById('currentBtcPrice');

    prepareChartData().then(data => loadChart(ctx, data));
    getCurrentBtcPrice().then(price => priceElem.innerText = `Current price: ${price.priceFormatted}`);
})();
