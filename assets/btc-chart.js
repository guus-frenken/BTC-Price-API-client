import Chart from 'chart.js/auto';
import 'chartjs-adapter-date-fns';

const getCurrentBtcPrice = async () => {
    let response = await fetch('/api/btcprice/');
    let {price} = await response.json();

    return price;
};

const getBtcPrices = async () => {
    let response = await fetch('/api/btcprice/history/');
    let {prices} = await response.json();

    return prices;
};

const prepareChartData = async () => {
    let data = [];
    const prices = await getBtcPrices();

    prices.map(function (price) {
        data.push({
            x: new Date(price.timestamp),
            y: price.price,
            priceFormatted: price.priceFormatted,
        });
    });

    return data;
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
                    yAxisID: 'y',
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
                            let price = context.dataset.data[context.dataIndex].priceFormatted;

                            if (context.parsed.y !== null) {
                                label += price;
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

(async function () {
    const ctx = document.getElementById('btcPricesChart').getContext('2d');
    const data = await prepareChartData();

    loadChart(ctx, data);

    const priceElem = document.getElementById('currentBtcPrice');
    let price = await getCurrentBtcPrice();

    priceElem.innerHTML = `Current price: ${price.priceFormatted}`;
})();
