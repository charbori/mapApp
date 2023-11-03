<div>
    <canvas id="myChart"></canvas>
</div>

@vite(['resources/js/myChart.js'])

<script>
    const ctx = document.getElementById('myChart');
    const MONTHS = [
        'January',
        'February',
        'March',
        'April',
        'May',
        'June',
        'July',
        'August',
        'September',
        'October',
        'November',
        'December'
        ];

    function months(config) {
        var cfg = config || {};
        var count = cfg.count || 12;
        var section = cfg.section;
        var values = [];
        var i, idx, value;
        idx = cfg.s;
        for (i = idx; i < count; ++i) {
            value = MONTHS[Math.ceil(i) % 12];
            values.push(value.substring(0, section));
        }

        return values;
    }
    const now_date2 = new Date();
    const now_month2 = now_date2.getMonth();
    const month_type2 = now_month2 >= 6 ? 'last_half' : 'before_half';
    let start_month = 0;
    if (month_type2 == 'last_half') {
        start_month = 6;
    }
    const labels = months({count: (6 + start_month), s : start_month});
    const data = {
        labels: labels,
        datasets: [{
            label: '',
            data: [0,0,0,0,0,0],
            fill: false,
            borderColor: 'rgb(75, 192, 192)',
            tension: 0.1
        }]
    };

    const config = {
        type: 'line',
        data: data,
    };
</script>
