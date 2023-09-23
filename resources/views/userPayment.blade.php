<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>User Payment Data</title>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/jquery.dataTables.min.css">
    <script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>

    <style>
        .chartSection {
            background-color: #fff;
            border-radius: 15px;
            height: 280px;
            margin-top: 3px;
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
        <canvas id="userPaymentLineChart"></canvas>
    </div>

    {{-- dataTableJs --}}
    <div>
        <table id="userPaymentDataTable" class="display" data-order="" style="width:100%">
            <thead>
                {{-- 一組<tr></tr>代表橫向資料欄位一排 --}}
                <tr>
                    <td><strong>Hour</strong></td>
                    <td style="color:#D95B04"><strong>Android Users</strong></td>
                    <td style="color:#8F8100"><strong>Android Rev</strong></td>
                    <td style="color:#04CBD9"><strong>iOS Users</strong></td>
                    <td style="color:#1F008F"><strong>iOS Rev</strong></td>
                </tr>
            </thead>
            <tbody>
                @for ($i = 0; $i < 24; $i++)
                    <tr>
                        <td>{{ $i }}:00</td>
                        <td style="color:#D95B04">{{ $androidUsers[$i] }}</td>
                        <td style="color:#8F8100">{{ $androidRev[$i] }}</td>
                        <td style="color:#04CBD9">{{ $iOSUsers[$i] }}</td>
                        <td style="color:#1F008F">{{ $iOSRev[$i] }}</td>
                    </tr>
                @endfor

                <tr>
                    <td><strong>Total</strong></td>
                    <td style="color:#D95B04"><strong>{{ $sumAU }}</strong></td>
                    <td style="color:#8F8100"><strong>{{ $sumAR }}</strong></td>
                    <td style="color:#04CBD9"><strong>{{ $sumIU }}</strong></td>
                    <td style="color:#1F008F"><strong>{{ $sumIR }}</strong></td>
                </tr>
            </tbody>

        </table>
    </div>

    <script>
        // chartJs---------------------------------
        // 定義userPaymentLineCtx
        const userPaymentLineCtx = document.getElementById('userPaymentLineChart');
        console.log('userPaymentLineChart:', userPaymentLineChart);

        // 定義線圖x軸
        const hourlabels = [];
        for (i = 0; i < 24; i++) {
            hourlabels.push(i);
        }
        console.log('hourlabels:', hourlabels);

        // 將controller傳值之$result轉為json給頁面使用
        const userPaymentData = <?php echo json_encode($result); ?>;
        console.log('userPaymentData:', userPaymentData);

        // 定義androidUsers
        // 值從userPaymentData被取出會變成是一般array
        // 將array轉為關聯式數組要JSON.parse
        const androidUsers = JSON.parse(userPaymentData[0]['user_count']);
        console.log('androidUsers:', androidUsers);

        // 定義androidRev
        const androidRev = JSON.parse(userPaymentData[0]['revenue']);
        console.log('androidRev:', androidRev);

        // 定義iOSUsers
        iOSUsers = JSON.parse(userPaymentData[1]['user_count']);
        console.log('iOSUsers:', iOSUsers);

        // 定義iOSRev
        iOSRev = JSON.parse(userPaymentData[1]['revenue']);
        console.log('iOSRev:', iOSRev);

        // 定義androidUsers加總
        let sumAU = 0;
        androidUsers.forEach(element => {
            sumAU += element;
        });
        console.log('sumAU:', sumAU);

        // 定義androidRev加總
        let sumAR = 0;
        androidRev.forEach(element => {
            sumAR += element;
        });
        console.log('sumAR:', sumAR);

        // 定義iOSUsers加總
        let sumIU = 0;
        iOSUsers.forEach(element => {
            sumIU += element;
        });
        console.log('sumIU:', sumIU);

        // 定義iOSRev加總
        let sumIR = 0;
        iOSRev.forEach(element => {
            sumIR += element;
        });
        console.log('sumIR:', sumIR);

        // 繪製userPaymemtLineChart
        const userPaymemtLineChart =
            new Chart(userPaymentLineCtx, {
                type: 'line',
                data: {
                    labels: hourlabels,
                    datasets: [{
                            label: 'Android Users:' + sumAU,
                            data: androidUsers,
                            backgroundColor: '#D95B04',
                            borderColor: '#D95B04',
                            pointBackgroundColor: '#D95B04',
                            pointBorderColor: '#D95B04',
                        },
                        {
                            label: 'iOS Users:' + sumIU,
                            data: iOSUsers,
                            backgroundColor: '#04CBD9',
                            borderColor: '#04CBD9',
                            pointBackgroundColor: '#04CBD9',
                            pointBorderColor: '#04CBD9',
                        },
                        {
                            label: 'Android Rev:' + sumAR,
                            data: androidRev,
                            backgroundColor: '#8F8100',
                            borderColor: '#8F8100',
                            pointBackgroundColor: '#8F8100',
                            pointBorderColor: '#8F8100',
                        },
                        {
                            label: 'iOS Rev:' + sumIR,
                            data: iOSRev,
                            backgroundColor: '#1F008F',
                            borderColor: '#1F008F',
                            pointBackgroundColor: '#1F008F',
                            pointBorderColor: '#1F008F',
                        },
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
                                text: 'Hour',
                                align: 'end',
                            }
                        },
                        y: {
                            beginAtZero: true,
                            title: {
                                display: true,
                                text: 'Users',
                                align: 'end'
                            }
                        }
                    },
                }
            })

        // dataTablejs---------------------------------
        const userPaymentDataTable =
            new DataTable('#userPaymentDataTable', {
                columnDefs: [{
                        width: '20%',
                        targets: 0
                    },
                    {
                        width: '20%',
                        targets: 1
                    },
                    {
                        width: '20%',
                        targets: 2
                    },
                    {
                        width: '20%',
                        targets: 3
                    },
                    {
                        width: '20%',
                        targets: 4
                    },
                ],
                searching: false,
                paging: false,
                info: false,
            });
    </script>
</body>

</html>
