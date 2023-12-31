<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Index</title>
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

    #userPaymentLineChart {
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

    #paymentTotalPieChart {
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

    .dataSelection {
        text-align: center;
    }
</style>

<body>
    {{-- <div style="margin-bottom: 10px">
        <canvas id="myChart"></canvas>
    </div> --}}

    <div style="margin-bottom: 5px">
        <canvas id="newUserLineChart"></canvas>
    </div>

    <div style="margin-bottom: 5px">
        <canvas id="userPaymentLineChart"></canvas>
    </div>

    <div class="pieSection">
        <div class="pie">
            <canvas id="newUserTotalPieChart"></canvas>
        </div>

        <div class="pie">
            <canvas id="paymentTotalPieChart"></canvas>
            <div class="dataSelection">
                <label for="dataSelect">Select Payment Data:</label>
                <select id="dataSelect" onchange="updateUserPaymentTotalPieChart()">
                    <option value="revenueTotal">Revenue Total</option>
                    <option value="userTotal">User Total</option>
                </select>
            </div>
        </div>
    </div>

    <script>
        // newUserLineChart
        const newUserCtx = document.getElementById('newUserLineChart');
        console.log('newUserCtx:', newUserCtx);

        // 24小時x軸座標陣列
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

        // 取controller回傳值來定義
        const newUser = <?php echo json_encode($newUserApiDataDB); ?>;
        console.log('newUser:', newUser);

        // 將newAndroidUserCount和newiOSUserCount預設為[]
        // 以供newUser無資料時能被判斷取值
        let newAndroidUserCount = [];
        let newiOSUserCount = [];

        // 若newUser有資料
        // 則判斷取newAndroidUserCount和newiOSUserCount值
        if (newUser.length > 0) {
            // JSON.parse()將字串轉為JSON數組才能給chart使用
            // 不然純字串無法對應chart之X時間軸正常顯示
            newAndroidUserCount = JSON.parse(newUser[0]['user_count']);
            console.log('newAndroidUserCount:', newAndroidUserCount);

            newiOSUserCount = JSON.parse(newUser[1]['user_count']);
            console.log('newiOSUserCount:', newiOSUserCount);
        }

        new Chart(newUserCtx, {
            type: 'line',
            data: {
                labels: hourLabels,
                datasets: [{
                        label: 'Android Users',
                        data: newAndroidUserCount,
                        backgroundColor: '#943F00',
                        borderColor: '#943F00',
                        pointBorderColor: '#943F00',
                        pointBackgroundColor: '#943F00',
                    },
                    {
                        label: 'iOS Users',
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

        // userPaymentLineChart
        const userPaymentCtx = document.getElementById('userPaymentLineChart');
        console.log('userPaymentCtx:', userPaymentCtx);

        // 取controller回傳值來定義
        const userPayment = <?php echo json_encode($userPaymentApiDataDB); ?>;
        console.log('userPayment:', userPayment);

        // 預設變數為[]
        // 以供userPayment無資料時能被判斷取值
        let paymentAndroidUserCount = [];
        let paymentiOSUserCount = [];
        let paymentAndroidRevenue = [];
        let paymentiOSRevenue = [];

        // 若userPayment有資料
        // 則判斷取值
        if (userPayment.length > 0) {
            // JSON.parse()將字串轉為JSON數組才能給chart使用
            // 不然純字串無法對應chart之X時間軸正常顯示
            paymentAndroidUserCount = JSON.parse(userPayment[0]['user_count']);
            console.log('paymentAndroidUserCount', paymentAndroidUserCount);

            paymentiOSUserCount = JSON.parse(userPayment[1]['user_count']);
            console.log('paymentiOSUserCount', paymentiOSUserCount);

            paymentAndroidRevenue = JSON.parse(userPayment[0]['revenue']);
            console.log('paymentAndroidRevenue:', paymentAndroidRevenue);

            paymentiOSRevenue = JSON.parse(userPayment[1]['revenue']);
            console.log('paymentiOSRevenue', paymentiOSRevenue);
        }

        new Chart(userPaymentCtx, {
            type: 'line',
            data: {
                labels: hourLabels,
                datasets: [{
                        label: 'Android Users',
                        data: paymentAndroidUserCount,
                        backgroundColor: '#D95B04',
                        borderColor: '#D95B04',
                        pointBorderColor: '#D95B04',
                        pointBackgroundColor: '#D95B04',
                    },
                    {
                        label: 'iOS Users',
                        data: paymentiOSUserCount,
                        backgroundColor: '#04CBD9',
                        borderColor: '#04CBD9',
                        pointBorderColor: '#04CBD9',
                        pointBackgroundColor: '#04CBD9',
                    },
                    {
                        label: 'Android Revenue',
                        data: paymentAndroidRevenue,
                        backgroundColor: '#8F8100',
                        borderColor: '#8F8100',
                        pointBorderColor: '#8F8100',
                        pointBackgroundColor: '#8F8100',
                    },
                    {
                        label: 'iOS Revenue',
                        data: paymentiOSRevenue,
                        backgroundColor: '#1F008F',
                        borderColor: '#1F008F',
                        pointBorderColor: '#1F008F',
                        pointBackgroundColor: '#1F008F'
                    }
                ]
            },
            options: {
                maintainAspectRatio: false,
                plugins: {
                    title: {
                        display: true,
                        text: 'User Payment',
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

        // 定義newUserTotalPieChart
        const newUserTotalCtx = document.getElementById('newUserTotalPieChart');
        console.log('newUserTotalCtx:', newUserTotalCtx);

        let newAndroidUserTotal =
            newAndroidUserCount.reduce(
                (accumulator, currentValue) => accumulator + currentValue, 0
            );
        console.log('newAndroidUserTotal:', newAndroidUserTotal);

        let newiOSUserTotal =
            newiOSUserCount.reduce(
                (accumulator, currentValue) => accumulator + currentValue, 0
            );
        console.log('newiOSUserTotal:', newiOSUserTotal);

        // 創圖newUserTotalPieChart
        const newUserTotalPieChart =
            new Chart(newUserTotalCtx, {
                type: 'pie',
                data: {
                    labels: [
                        'Android Users: ' + newAndroidUserTotal.toLocaleString(),
                        'iOS Users: ' + newiOSUserTotal.toLocaleString()
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

        // 定義userPaymentTotalPieChart
        const paymentTotalCtx = document.getElementById('paymentTotalPieChart');
        console.log('paymentTotalCtx:', paymentTotalCtx);

        let paymentAndroidUserTotal =
            paymentAndroidUserCount.reduce(
                (accumulator, currentValue) => accumulator + currentValue, 0
            );
        console.log('paymentAndroidUserTotal:', paymentAndroidUserTotal);

        let paymentiOSUserTotal = paymentiOSUserCount.reduce(
            (accumulator, currentValue) => accumulator + currentValue, 0
        );
        console.log('paymentiOSUserTotal:', paymentiOSUserTotal);

        let paymentAndroidRevenueTotal =
            paymentAndroidRevenue.reduce(
                (accumulator, currentValue) => accumulator + currentValue, 0
            );
        console.log('paymentAndroidRevenueTotal:', paymentAndroidRevenueTotal);

        let paymentiOSRevenueTotal =
            paymentiOSRevenue.reduce(
                (accumulator, currentValue) => accumulator + currentValue, 0
            );
        console.log('paymentiOSRevenueTotal:', paymentiOSRevenueTotal);

        // 建圖userPaymentTotalPieChart
        const userPaymentTotalPieChart =
            new Chart(paymentTotalCtx, {
                type: 'pie',
                data: {
                    labels: [
                        'Android Rev: ' + paymentAndroidRevenueTotal.toLocaleString(),
                        'iOS Rev: ' +
                        paymentiOSRevenueTotal.toLocaleString(),
                    ],
                    datasets: [{
                        data: [
                            paymentAndroidRevenueTotal,
                            paymentiOSRevenueTotal,
                        ],
                        backgroundColor: [
                            '#8F8100',
                            '#1F008F'
                        ],
                        hoverOffset: 10,
                    }]
                },
                options: {
                    maintainAspectRatio: false,
                    plugins: {
                        title: {
                            display: true,
                            text: 'User Payment Total',
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
                    }
                }
            })

        function updateUserPaymentTotalPieChart() {
            const selectedOption = document.getElementById('dataSelect').value;

            if (selectedOption === 'revenueTotal') {
                userPaymentTotalPieChart.data.labels = [
                    'Android Rev: ' + paymentAndroidRevenueTotal.toLocaleString(),
                    'iOS Rev: ' + paymentiOSRevenueTotal.toLocaleString(),
                ];

                // 因為資料為數組所以需要加索引[0]
                userPaymentTotalPieChart.data.datasets[0].data = [
                    paymentAndroidRevenueTotal,
                    paymentiOSRevenueTotal,
                ];

                userPaymentTotalPieChart.data.datasets[0].backgroundColor = [
                    '#8F8100',
                    '#1F008F'
                ]
            } else if (selectedOption === 'userTotal') {
                userPaymentTotalPieChart.data.labels = [
                    'Android Users: ' + paymentAndroidUserTotal.toLocaleString(),
                    'iOS Users: ' + paymentiOSUserTotal.toLocaleString(),
                ];

                userPaymentTotalPieChart.data.datasets[0].data = [
                    paymentAndroidUserTotal,
                    paymentiOSUserTotal,
                ];

                userPaymentTotalPieChart.data.datasets[0].backgroundColor = [
                    '#D95B04',
                    '#04CBD9',
                ];
            }

            userPaymentTotalPieChart.update();
        }
    </script>
</body>

</html>
