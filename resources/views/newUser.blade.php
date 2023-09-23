<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>New User Data</title>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/jquery.dataTables.min.css">
    <script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@10">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@10"></script>

    <style>
        .chart {
            background-color: #fff;
            border-radius: 15px;
            margin-bottom: 5px;
            margin-top: 3px;
            height: 280px;
        }

        .button {
            /* padding: 0; */
        }
    </style>

</head>

<body>
    <div>
        <div style="display: inline-block ; margin-right:10px">
            <strong style="color:darkslateblue">Data Date: {{ $date }}</strong>
        </div>

        <div style="display: inline-block">
            <button>
                <strong>
                    <a href="javascript:history.back()">Back to Search</a>
                </strong>
            </button>
        </div>
    </div>


    {{-- chartJs --}}
    <div class="chartSection">
        <div class="chart">
            <canvas id=newUserLineChart></canvas>
        </div>
    </div>

    {{-- dataTableJs --}}
    <div class="dataTable">
        <table data-order="" id="newUserDataTable" class="display" style="width:100%">
            <thead>
                <tr>
                    <th>Hour</th>
                    <th style="color:#943F00">Android Users</th>
                    <th style="color:#008391">iOS Users</th>
                </tr>
            </thead>
            <tbody>
                @for ($i = 0; $i < 24; $i++)
                    <tr>
                        <td>{{ $i }}:00</td>
                        <td style="color:#943F00">{{ $newAndroidUsers[$i] }}</td>
                        <td style="color:#008391">{{ $newiOSUsers[$i] }}</td>
                    </tr>
                @endfor

                <tr>
                    <td><strong>Total</strong></td>
                    <td style="color:#943F00"><strong>{{ $sumA }}</strong></td>
                    <td style="color:#008391"><strong>{{ $sumI }}</strong></td>
                </tr>
            </tbody>
        </table>
    </div>

    {{-- chartJs --}}
    <script>
        // const不可被重新賦值
        // let可以被重新賦值

        // 取controller回傳$result值來定義newUserData
        // controller傳來$result值為數組
        // 數組必須json_encode轉為字串才能初始化給JavaScript使用
        const newUserData =
            <?php echo json_encode($result); ?>;
        console.log('newUserData:', newUserData);

        // 定義線圖ctx
        const newUserLineCtx = document.getElementById('newUserLineChart');
        console.log('newUserLineCtx:', newUserLineCtx);

        // 定義線圖x軸刻度
        const hourLabels = [];
        for (i = 0; i < 24; i++) {
            hourLabels.push(i);
        };
        console.log('hourLabels:', hourLabels);

        // 定義androidUserCount
        // 要將值轉為數組所以要先JSON.parse
        const androidUserCount = JSON.parse(newUserData[0]['user_count']);
        console.log('androidUserCount:', androidUserCount);

        // 定義iOSUserCount
        // 要將值轉為數組所以要先JSON.parse
        const iOSUserCount = JSON.parse(newUserData[1]['user_count']);
        console.log('iOSUserCount:', iOSUserCount);

        // 定義androidUserCount加總
        let sumA = 0;
        androidUserCount.forEach(element => {
            sumA += element;
        });
        console.log('sumA:', sumA);

        // 定義iOSUserCount加總
        let sumI = 0;
        iOSUserCount.forEach(element => {
            sumI += element;
        });
        console.log('sumI:', sumI);


        // 繪製newUserLineChart線圖
        const newUserLineChart =
            new Chart(newUserLineCtx, {
                type: 'line',
                data: {
                    labels: hourLabels,
                    datasets: [{
                            label: 'Android: ' + sumA,
                            data: androidUserCount,
                            backgroundColor: '#943F00',
                            borderColor: '#943F00',
                            pointBorderColor: '#943F00',
                            pointBackgroundColor: '#943F00',
                        },
                        {
                            label: 'iOS: ' + sumI,
                            data: iOSUserCount,
                            backgroundColor: '#008391',
                            borderColor: '#008391',
                            pointBorderColor: '#008391',
                            pointBackgroundColor: '#008391',
                        }
                    ],
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
                        legend: {
                            display: true,
                            labels: {
                                font: {
                                    weight: 'bold'
                                }
                            }
                        },
                    },
                    scales: {
                        x: {
                            beginAtZero: true,
                            title: {
                                display: true,
                                text: 'Hour',
                                align: 'end',
                            },
                        },
                        y: {
                            beginAtZero: true,
                            title: {
                                display: true,
                                text: 'Users',
                                align: 'end',
                            },
                        },
                    },
                }
            })
    </script>

    {{-- dataTablejs --}}
    <script>
        const newUserDataTable =
            new DataTable('#newUserDataTable', {
                columnDefs: [{
                        width: '30%',
                        targets: 0
                    },
                    {
                        width: '35%',
                        targets: 1
                    },
                    {
                        width: '35%',
                        targets: 2
                    },
                ],
                searching: false,
                paging: false,
                info: false,
            });
    </script>

</body>

</html>
