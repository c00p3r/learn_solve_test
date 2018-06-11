window.onload = function () {

    function ajax_get(url, callback) {
        var request = new XMLHttpRequest();
        request.onreadystatechange = function () {
            if (request.readyState == 4 && request.status == 200) {
                try {
                    var data = JSON.parse(request.responseText);
                } catch (err) {
                    console.log(err.message + ' in ' + request.responseText);
                    return;
                }
                callback(data);
            }
        };

        request.open('GET', url, true);
        request.send();
    }

    var dataPoints = [];

    var chart = new CanvasJS.Chart('chartContainer', {
        animationEnabled: true,
        axisX: {
            interval: 1
        },
        axisY2: {
            interlacedColor: 'rgba(1,77,101,.2)',
            gridColor: 'rgba(1,77,101,.1)',
            title: 'Market change, %'
        },
        data: [{
            type: 'bar',
            name: 'markets',
            axisYType: 'secondary',
            color: '#014D65',
            dataPoints: dataPoints
        }]
    });

    function renderChart(data) {
        data.forEach(function (item) {
            dataPoints.push({
                y: 100 + parseFloat(item.change),
                label: item.exchange + ':' + item.ticker
            });
        });
        chart.render();
    }

    ajax_get('/api/chart_data', renderChart);
};