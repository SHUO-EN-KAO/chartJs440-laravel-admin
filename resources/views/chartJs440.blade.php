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
        height: 235px;
    }

    #newUserLineChart {
        background-color: #fff;
        border-radius: 15px;
        padding: 5px;
        height: 235px;
    }

    #newUserTotalPieChart {
        background-color: #fff;
        border-radius: 15px;
        padding: 5px;
    }

    #newUserTotalPieChart2 {
        background-color: #fff;
        border-radius: 15px;
        padding: 5px;
    }

    .pieSection {
        display: flex;
        justify-content: space-evenly;
        width: 100%;
        height: 235px;
    }

    .pie {
        width: 50%;
        margin: 2px;
    }
</style>

<body>
    {{-- <div style="margin-bottom: 10px">
        <canvas id="myChart"></canvas>
    </div> --}}

    <div style="margin-bottom: 10px">
        @if ($dataCreated)
            <div>Notice: {{ $dataDate }} New User Data Created !</div>
        @else
            <div>Notice: {{ $dataDate }} New User Data Updated !</div>
        @endif
    </div>

    <div style="margin-bottom: 10px">
        <canvas id="newUserLineChart"></canvas>
    </div>

    <div class="pieSection">
        <div class="pie">
            <canvas id="newUserTotalPieChart"></canvas>
        </div>

        <div class="pie">
            <canvas id="newUserTotalPieChart2"></canvas>
        </div>
    </div>

    <script>
        const ctx = document.getElementById('myChart');
        console.log('ctx:', ctx);

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

        const newUserCtx = document.getElementById('newUserLineChart');
        console.log('newUserCtx:', newUserCtx);

        // 寫法1
        const hourLabels = [];
        for (let i = 0; i < 24; i++) {
            hourLabels.push(i);
        };

        // 寫法2
        // const hourLabels = Array.from({
        //     length: 24
        // }, (_, index) => index);

        console.log('hourLabels:', hourLabels);

        const newUser = <?php echo json_encode($jsonNewUser); ?>;
        console.log('newUser:', newUser);

        const newAndroidUserCount = newUser['data']['statistics'][0]['userCount'];
        console.log('newAndroidUserCount:', newAndroidUserCount);

        const newiOSUserCount = newUser['data']['statistics'][1]['userCount'];
        console.log('newiOSUserCount:', newiOSUserCount);

        new Chart(newUserCtx, {
            type: 'line',
            data: {
                labels: hourLabels,
                datasets: [{
                        label: 'AndroidUserCount',
                        data: newAndroidUserCount,
                        backgroundColor: '#943F00',
                        borderColor: '#943F00',
                        pointBorderColor: '#943F00',
                        pointBackgroundColor: '#943F00',
                    },
                    {
                        label: 'iOSUserCount',
                        data: newiOSUserCount,
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

        const newUserTotalCtx = document.getElementById('newUserTotalPieChart');
        console.log('newUserTotalCtx:', newUserTotalCtx);

        const newAndroidUserTotal =
            newAndroidUserCount.reduce(
                (accumulator, currentValue) => accumulator + currentValue, 0
            )
        console.log('newAndroidUserTotal:', newAndroidUserTotal);

        const newiOSUserTotal =
            newiOSUserCount.reduce(
                (accumulator, currentValue) => accumulator + currentValue, 0
            )
        console.log('newiOSUserTotal:', newiOSUserTotal);


        new Chart(newUserTotalCtx, {
            type: 'pie',
            data: {
                labels: [
                    'Android: ' + newAndroidUserTotal,
                    'iOS: ' + newiOSUserTotal
                ],
                datasets: [{
                    data: [
                        newAndroidUserTotal,
                        newiOSUserTotal
                    ],
                    backgroundColor: ['#943F00', '#008391'],
                    hoverOffset: 10,
                }],
            },
            options: {
                maintainAspectRatio: false,
                plugins: {
                    title: {
                        display: true,
                        text: 'New User Total',
                        font: {
                            size: 18,
                        },
                        padding: 0,
                    },
                    legend: {
                        display: true,
                        labels: {
                            font: {
                                weight: 'bold'
                            }
                        }
                    }
                },
            }
        });

        const newUserTotal2Ctx = document.getElementById('newUserTotalPieChart2');

        new Chart(newUserTotal2Ctx, {
            type: 'pie',
            data: {
                labels: ['Android', 'iOS'],
                datasets: [{
                    data: [
                        newAndroidUserTotal,
                        newiOSUserTotal
                    ],
                    backgroundColor: ['#943F00', '#008391'],
                    hoverOffset: 10,
                }],
            },
            options: {
                maintainAspectRatio: false,
                plugins: {
                    title: {
                        display: true,
                        text: 'New User Total2',
                        font: {
                            size: 18,
                        },
                        padding: 0,
                    },
                    legend: true,
                },
            }
        });
    </script>
</body>

</html>
