(function ($, Chart, Phoenix) {

    var indicators = Phoenix.historicalIndicators;
    var currencySymbol = Phoenix.currencySymbol || '$';

    drawCurrencyChart('monthlyRecurringRevenueChart', 30, 'monthly_recurring_revenue');
    drawCurrencyChart('yearlyRecurringRevenueChart', 30, 'yearly_recurring_revenue');
    drawCurrencyChart('dailyTotalRevenueChart', 14, 'total_revenue_to_date');
    drawChart('newCustomersChart', 14, 'new_customers');

    function drawCurrencyChart(id, days, dataKey) {
        return drawChart(id, days, dataKey, {
            scales: {
                yAxes: [{
                    ticks: {
                        beginAtZero: true,
                        userCallback: formatCurrency
                    }
                }]
            }
        });
    }

    function drawChart(id, days, dataKey, customOptions) {
        var dataset = getChartBaseDataSet();
        var options = {
            responsive: true,
            legend: {display: false}
        };

        var labels = indicators
            .slice(Math.max(0, indicators.length - days))
            .map(function (v) { return moment(v.created_at).format('M/D') });

        dataset.data = indicators
            .slice(Math.max(0, indicators.length - days))
            .map(function (v) { return v[dataKey] });

        if (arguments.length === 4) {
            options = $.extend(options, customOptions);
        }

        return new Chart(document.getElementById(id), {
            type: 'line',
            data: {
                labels: labels,
                datasets: [dataset]
            },
            options: options
        });
    }

    function getChartBaseDataSet() {
        return {
            label: 'Dataset',
            backgroundColor: 'rgba(91, 192, 222, 0.2)',
            borderColor: '#5bc0de',
            pointBackgroundColor: '#5bc0de',
            pointBorderColor: '#fff',
            pointHoverBackgroundColor: '#fff',
            pointHoverBorderColor: '#5bc0de',
        };
    }

    function formatCurrency(value) {
        value = parseFloat(value);

        if (!isFinite(value) || !value && value !== 0) {
            return '';
        }

        var sign = value < 0 ? '-' : '';
        var strValue = Math.abs(value).toFixed(2);

        var intValue = strValue.slice(0, -3);
        var decimals = strValue.slice(-3);

        var leadingDigitsLen = intValue.length % 3;
        var leadingDigitsStr = leadingDigitsLen > 0
            ? intValue.slice(0, leadingDigitsLen) + (intValue.length > 3 ? ',' : '')
            : '';

        var remainingDigits = intValue.slice(leadingDigitsLen).replace(/(\d{3})(?=\d)/g, '$1,');

        return sign + currencySymbol + leadingDigitsStr + remainingDigits + decimals;
    }

})(jQuery, Chart, window.Phoenix || {});
