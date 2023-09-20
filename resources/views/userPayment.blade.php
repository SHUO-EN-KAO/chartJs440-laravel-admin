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
</head>

<body>
    <div>
        <strong style="color:darkslateblue">Data Date: {{ $date }}</strong>
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
                    <td><strong>Android User</strong></td>
                    <td><strong>Android Rev</strong></td>
                    <td><strong>iOS User</strong></td>
                    <td><strong>iOS Rev</strong></td>
                </tr>
            </thead>
            <tbody>
                @for ($i = 0; $i < 24; $i++)
                    <tr>
                        <td>{{ $i }}:00</td>
                        <td>{{ $androidUserCount[$i] }}</td>
                        <td>{{ $androidUserRev[$i] }}</td>
                        <td>{{ $iOSUserCount[$i] }}</td>
                        <td>{{ $iOSUserRev[$i] }}</td>
                    </tr>
                @endfor

                <tr>
                    <td><strong>Total</strong></td>
                    <td><strong>{{ $sumAU }}</strong></td>
                    <td><strong>{{ $sumAR }}</strong></td>
                    <td><strong>{{ $sumIU }}</strong></td>
                    <td><strong>{{ $sumIR }}</strong></td>
                </tr>
            </tbody>

        </table>
    </div>

    {{-- dataTablejs --}}
    <script>
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
