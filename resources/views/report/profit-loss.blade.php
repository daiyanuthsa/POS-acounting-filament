<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Neraca Bengkel Bang Jo</title>

    @vite('resources/css/app.css')
</head>

<body class="p-8">
    <div class="w-full">
        <div class="text-center mb-8">
            <h1 class="text-2xl font-bold">{{ $merchant->name }}</h1>
            <h2 class="text-xl">Laporan Laba - Rugi</h2>
            <p>Periode</p>
            <p>{{ $startDate }} - {{ $endDate }} </p>
            <p>(Dalam rupiah)</p>
        </div>

        <div class="flex justify-between">
            <!-- Revenue Section -->
            <div class="w-1/2 border-r pr-4">
                <div class="mb-4">
                    <table class="w-full text-left mb-4">
                        <tbody>
                            <tr>
                                <th colspan="2">Pendapatan/Revenue</th>
                                <th>{{ $endDate }}</th>
                            </tr>
                            @php
                                $totalRevenue = 0; // Initialize a variable to hold the total
                            @endphp

                            @foreach ($revenue as $account)
                                {{-- @if ($account->asset_type === 'current') --}}
                                <tr>
                                    <td>{{ $account->account_code }}</td>
                                    <td>{{ $account->account_name }}</td>
                                    <td class="text-right">{{ number_format($account->balance, 2) }}</td>
                                </tr>
                                @php
                                    // Add the balance of the current account to the total
                                    $totalRevenue += $account->balance;
                                @endphp
                            @endforeach
                            <tr>
                                <td colspan="2">Total Pendapatan</td>
                                <td>{{ number_format($totalRevenue, 2) }}</td>
                            </tr>
                        </tbody>
                    </table>
                </div>
                <div>
                    <table class="w-full text-left mb-4">
                        <tbody>
                            <tr>
                                <th colspan="3">BEBAN POKOK PENJUALAN/COST OF GOODS SOLD</th>
                            </tr>
                            @php
                                $totalCOG = 0; // Initialize a variable to hold the total
                            @endphp

                            @foreach ($costOfGoods as $account)
                                <tr>
                                    <td>{{ $account->account_code }}</td>
                                    <td>{{ $account->account_name }}</td>
                                    <td class="text-right">{{ number_format($account->balance, 2) }}</td>
                                </tr>
                                @php
                                    // Add the balance of the current account to the total
                                    $totalCOG += $account->balance;
                                @endphp
                            @endforeach
                            <tr>
                                <td colspan="2">Total Beban Pokok Penjualan</td>
                                <td>{{ number_format($totalCOG, 2) }}</td>
                            </tr>
                        </tbody>
                    </table>
                </div>
                {{-- Beban / Expenses --}}
                <div>
                    <table class="w-full text-left mb-4">
                        <tbody>
                            <tr>
                                <th colspan="3">BEBAN/Expenses</th>
                            </tr>
                            @php
                                $totalExpense = 0; // Initialize a variable to hold the total
                            @endphp

                            @foreach ($expense as $account)
                                <tr>
                                    <td>{{ $account->account_code }}</td>
                                    <td>{{ $account->account_name }}</td>
                                    <td class="text-right">{{ number_format($account->balance, 2) }}</td>
                                </tr>
                                @php
                                    // Add the balance of the current account to the total
                                    $totalExpense += $account->balance;
                                @endphp
                            @endforeach
                            <tr>
                                <td colspan="2">Total Beban / Expenses</td>
                                <td>{{ number_format($totalExpense, 2) }}</td>
                            </tr>
                        </tbody>
                    </table>
                </div>

                <div class="flex justify-between font-semibold mb-4">
                    <span>Total Laba</span>
                    <span>{{ number_format($totalRevenue - $totalCOG - $totalExpense, 2) }}</span>
                </div>
            </div>
        </div>
    </div>
</body>

</html>
