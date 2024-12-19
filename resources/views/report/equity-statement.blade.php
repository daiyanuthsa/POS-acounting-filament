<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Laporan Perubahan Modal {{ strtoupper($merchant) }}</title>
    <link rel="stylesheet" href="./css/report/equitystatement.css">
</head>

<body class="p-8">
    <section class="md:px-24 px-10 pt-20">
        <div class="flex flex-col items-center">
            <!-- Title Report Section -->
            <div class="title-report font-bold text-lg lg:text-2xl text-center mb-6">
                <h1>{{ strtoupper($merchant) }}</h1>
                <h2>LAPORAN PERUBAHAN MODAL</h2>
                <h3>TAHUN {{ $year }}</h3>
            </div>

            <!-- Table Report Section -->
            <div id="table-report" class="lg:w-1/2 w-full pt-10 lg:pt-20 text-sm space-y-2 lg:text-lg">
                <!-- Modal Awal -->
                <h4 class="font-bold">MODAL AWAL</h4>
                <table class="w-full mb-4">
                    <tbody>
                        @php
                            $equityBalance = 0;
                        @endphp
                        @foreach ($modal as $account)
                            <tr>
                                <td class="pl-5">{{ $account->account_code }}</td>
                                <td>{{ $account->account_name }}</td>
                                <td class="text-right">{{ number_format($account->balance, 2) }}</td>
                            </tr>
                            @php
                                $equityBalance += $account->balance;
                            @endphp
                        @endforeach
                    </tbody>
                </table>

                <!-- Perubahan Section -->
                <h4 class="font-bold mb-2">PERUBAHAN</h4>
                <table class="w-full mb-4">
                    <tbody>
                        @php
                            $totalMovement = 0;
                        @endphp
                        @foreach ($equityMovement as $account)
                            @if ($account->account_code !== '3-110')
                                <tr>
                                    <td class="pl-5">{{ $account->account_code }}</td>
                                    <td>{{ $account->account_name }}</td>
                                    <td class="text-right">{{ number_format($account->balance, 2) }}</td>
                                </tr>
                                @php
                                    $totalMovement += $account->balance;
                                @endphp
                            @endif
                        @endforeach
                    </tbody>
                </table>
                <!-- Total Perubahan Modal -->
                <table class="w-full" id="table-end">
                    <tbody>
                        <tr class="account-end">
                            <td class="pl-5">
                                TOTAL PERUBAHAN MODAL
                            </td>
                            <td class="text-right">{{ number_format($totalMovement, 2) }}</td>
                        </tr>
                    </tbody>
                </table>

                <!-- Modal Akhir -->
                <table class="w-full" id="table-end">
                    <tbody>
                        <tr class="account-end">
                            <td class="pl-5">
                                MODAL AKHIR
                            </td>
                            <td class="text-right">{{ number_format($equityBalance + $totalMovement, 2) }}</td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </section>
</body>

</html>
