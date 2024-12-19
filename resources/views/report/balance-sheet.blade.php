<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Laporan Neraca Saldo {{ strtoupper($merchant->name) }}</title>
    <link rel="stylesheet" href="./css/report/balancesheet.css">
</head>

<body>
    <div class="container">
        <div id="title">
            <h3>{{ strtoupper($merchant->name) }}</h3>
            <h1>NERACA (STANDAR)</h1>
            <p>Per Tgl. 31 Desember {{ $year }}</p>
            <p>(Dalam rupiah)</p>
        </div>

        <div class="content">
            <div class="single-content">
                <span class="deskripsi">Deskripsi</span>
                <span class="nilai">Nilai (IDR)</span>
            </div>

            <!-- Aktiva Section -->
            <div id="aktiva" class="mb-4">
                <h3>AKTIVA</h3>
                <h4>AKTIVA LANCAR</h4>
                <table class="w-full">
                    <tbody>
                        @php
                            $totalcurrentAssets = 0;
                        @endphp
                        @foreach ($balanceSheetData as $account)
                            @if ($account->asset_type === 'current')
                                <tr class="account-row">
                                    <td class="pl-5">{{ $account->account_code }} {{ $account->account_name }}</td>
                                    <td class="text-right">{{ number_format($account->balance, 2) }}</td>
                                </tr>
                                @php
                                    $totalcurrentAssets += $account->balance;
                                @endphp
                            @endif
                        @endforeach
                    </tbody>
                </table>
                <table class="w-full" id="table-end">
                    <tbody>
                        <tr class="account-end">
                            <td class="pl-5">TOTAL AKTIVA LANCAR</td>
                            <td class="text-right">{{ number_format($totalcurrentAssets, 2) }}</td>
                        </tr>
                    </tbody>
                </table>

                <h4>AKTIVA TETAP</h4>
                <table class="w-full">
                    <tbody>
                        @php
                            $totalFixedAssets = 0;
                        @endphp
                        @foreach ($balanceSheetData as $account)
                            @if ($account->asset_type === 'fixed')
                                <tr class="account-row">
                                    <td class="pl-5">{{ $account->account_code }} {{ $account->account_name }}</td>
                                    <td class="text-right">{{ number_format($account->balance, 2) }}</td>
                                </tr>
                                @php
                                    $totalFixedAssets += $account->balance;
                                @endphp
                            @endif
                        @endforeach
                    </tbody>
                </table>
                <table class="w-full" id="table-end">
                    <tbody>
                        <tr class="account-end">
                            <td class="pl-5">TOTAL AKTIVA TETAP</td>
                            <td class="text-right">{{ number_format($totalFixedAssets, 2) }}</td>
                        </tr>
                    </tbody>
                </table>
                <table class="w-full" id="table-end">
                    <tbody>
                        <tr class="account-end">
                            <td class="pl-5">TOTAL AKTIVA</td>
                            <td class="text-right">{{ number_format($totalFixedAssets + $totalcurrentAssets, 2) }}</td>
                        </tr>
                    </tbody>
                </table>
            </div>

            <!-- Pasiva Section -->
            <div id="pasiva">
                <h3 class="mt-4">PASIVA</h3>
                <h4>Hutang Lancar</h4>
                <table class="w-full">
                    <tbody>
                        @php
                            $liability = 0;
                        @endphp
                        @foreach ($pasiva as $account)
                            @if ($account->accountType === 'Liability')
                                <tr class="account-row">
                                    <td class="pl-5">{{ $account->account_code }} {{ $account->account_name }}</td>
                                    <td class="text-right">{{ number_format($account->balance, 2) }}</td>
                                </tr>
                                @php
                                    $liability += $account->balance;
                                @endphp
                            @endif
                        @endforeach
                    </tbody>
                </table>
                <table class="w-full" id="table-end">
                    <tbody>
                        <tr class="account-end">
                            <td class="pl-5">TOTAL HUTANG LANCAR</td>
                            <td class="text-right">{{ number_format($liability, 2) }}</td>
                        </tr>
                    </tbody>
                </table>

                <!-- EQUITY -->
                <h4>Modal</h4>
                <table class="w-full">
                    <tbody>
                        @php
                            $equity = 0;
                        @endphp
                        @foreach ($pasiva as $account)
                            @if ($account->accountType === 'Equity')
                                <tr class="account-row">
                                    <td class="pl-5">{{ $account->account_code }} {{ $account->account_name }}</td>
                                    <td class="text-right">{{ number_format($account->balance, 2) }}</td>
                                </tr>
                                @php
                                    $equity += $account->balance;
                                @endphp
                            @endif
                        @endforeach
                    </tbody>
                </table>
                <table class="w-full" id="table-end">
                    <tbody>
                        <tr class="account-end">
                            <td class="pl-5">TOTAL MODAL</td>
                            <td class="text-right">{{ number_format($equity, 2) }}</td>
                        </tr>
                    </tbody>
                </table>
                <table class="w-full" id="table-end">
                    <tbody>
                        <tr class="account-end">
                            <td class="pl-5">TOTAL PASIVA</td>
                            <td class="text-right">{{ number_format($equity + $liability, 2) }}</td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</body>

</html>
