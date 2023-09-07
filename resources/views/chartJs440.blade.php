<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Document</title>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
</head>
<style>
    #myChart {
        background-color: #fff;
        border-radius: 15px;
        padding: 5px;
        height: 245px;
    }

    #userNewLineChart {
        background-color: #fff;
        border-radius: 15px;
        padding: 5px;
        height: 245px;
    }
</style>

<body>
    <div style="margin-bottom: 10px">
        <canvas id="myChart"></canvas>
    </div>

    <div>
        <canvas id="userNewLineChart"></canvas>
    </div>

    <script>
        const ctx = document.getElementById('myChart');

        new Chart(ctx, {
            type: 'bar',
            data: {
                labels: ['Red', 'Blue', 'Yellow', 'Green', 'Purple', 'Orange'],
                datasets: [{
                    label: '# of Votes',
                    data: [12, 19, 3, 5, 2, 3],
                    borderWidth: 1
                }]
            },
            options: {
                maintainAspectRatio: false,
                plugins: {
                    title: {
                        display: true,
                        text: 'Example',
                        font: {
                            size: 18
                        },
                        padding: 0,
                    },
                    legend: true,
                },
                scales: {
                    y: {
                        beginAtZero: true,
                    }
                }
            }
        });

        const userNewCtx = document.getElementById('userNewLineChart');



        new Chart(userNewCtx, {
            type: 'line',
            data: {
                labels: ['Red', 'Blue', 'Yellow', 'Green', 'Purple', 'Orange'],
                datasets: [{
                        label: 'AndroidUserCount',
                        data: [12, 19, 3, 5, 2, 3],
                        backgroundColor: '#943F00',
                        borderColor: '#943F00',
                        pointBorderColor: '#943F00',
                        pointBackgroundColor: '#943F00',
                    },
                    {
                        label: 'iOSUserCount',
                        data: [88, 77, 33, 44, 55, 66],
                        backgroundColor: '#008391',
                        borderColor: '#008391',
                        pointBorderColor: '#008391',
                        pointBackgroundColor: '#008391',
                    },
                ]
            },
            options: {
                maintainAspectRatio: false,
                plugins: {
                    title: {
                        display: true,
                        text: 'New User',
                        font: {
                            size: 18,
                        },
                        padding: 0,
                    },
                    legend: true,
                },
                scales: {
                    x: {
                        beginAtZero: true,
                        title: {
                            display: true,
                            text: 'hour',
                            align: 'end',
                        },
                    },
                    y: {
                        beginAtZero: true,
                        title: {
                            display: true,
                            text: 'users',
                            align: 'end',
                        },
                    }
                }
            }
        });
    </script>
</body>

</html>
