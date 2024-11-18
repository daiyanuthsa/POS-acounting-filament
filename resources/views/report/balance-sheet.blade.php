<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Neraca Bengkel Bang Jo</title>
    <link rel="stylesheet" href="./css/report/balancesheet.css">
</head>

<body>
    <div class="container">
        <div id="title">
            <h3>{{ $merchant->name }}</h3>
            <h1>Neraca (standar)</h1>
            <p>Per Tgl. 31 Desember {{ $year }}</p>
            <p>(Dalam rupiah)</p>
        </div>

        <div class="content">
            <div class="single-content">
                <span class="deskripsi">Deskripsi</span>
                <span class="nilai">Nilai (IDR)</span>
            </div>

            <!-- Aktiva Section -->
            <div id="aktiva">
                <h3>AKTIVA</h3>
                <h4>Aktiva Lancar</h4>
                <table>
                    <tbody>
                        @php
                            $totalcurrentAssets = 0;
                        @endphp

                        @foreach ($balanceSheetData as $account)
                            @if ($account->asset_type === 'current')
                                <tr class="account-row">
                                    <td class="account-code">{{ $account->account_code }}</td>
                                    <td class="account-name">{{ $account->account_name }}</td>
                                    <td class="nilai">{{ number_format($account->balance, 2) }}</td>
                                </tr>
                                @php
                                    $totalcurrentAssets += $account->balance;
                                @endphp
                            @endif
                        @endforeach
                    </tbody>
                </table>
                <div class="single-content">
                    <span class="deskripsi">Total Aktiva Lancar</span>
                    <span class="nilai">{{ number_format($totalcurrentAssets, 2) }}</span>
                </div>

                <h4>Aktiva Tetap</h4>
                <table>
                    <tbody>
                        @php
                            $totalFixedAssets = 0;
                        @endphp

                        @foreach ($balanceSheetData as $account)
                            @if ($account->asset_type === 'fixed')
                                <tr class="account-row">
                                    <td class="account-code">{{ $account->account_code }}</td>
                                    <td class="account-name">{{ $account->account_name }}</td>
                                    <td class="nilai">{{ number_format($account->balance, 2) }}</td>
                                </tr>
                                @php
                                    $totalFixedAssets += $account->balance;
                                @endphp
                            @endif
                        @endforeach
                    </tbody>
                </table>
                <div class="single-content">
                    <span class="deskripsi">Total Aktiva Tetap</span>
                    <span class="nilai">{{ number_format($totalFixedAssets, 2) }}</span>
                </div>

                <div class="single-content">
                    <span class="deskripsi">Total Aktiva</span>
                    <span class="nilai">{{ number_format($totalFixedAssets + $totalcurrentAssets, 2) }}</span>
                </div>
            </div>

            <!-- Pasiva Section -->
            <div id="pasiva">
                <h3>PASIVA</h3>
                <h4>Hutang Lancar</h4>
                <table>
                    <tbody>
                        @php
                            $liability = 0;
                        @endphp

                        @foreach ($pasiva as $account)
                            @if ($account->accountType === 'Liability')
                                <tr class="account-row">
                                    <td class="account-code">{{ $account->account_code }}</td>
                                    <td class="account-name">{{ $account->account_name }}</td>
                                    <td class="nilai">{{ number_format($account->balance, 2) }}</td>
                                </tr>
                                @php
                                    $liability += $account->balance;
                                @endphp
                            @endif
                        @endforeach
                    </tbody>
                </table>
                <div class="single-content">
                    <span class="deskripsi">Total Hutang Lancar</span>
                    <span class="nilai">{{ number_format($liability, 2) }}</span>
                </div>

                <h4>Modal</h4>
                <table>
                    <tbody>
                        @php
                            $equity = 0;
                        @endphp

                        @foreach ($pasiva as $account)
                            @if ($account->accountType === 'Equity')
                                <tr class="account-row">
                                    <td class="account-code">{{ $account->account_code }}</td>
                                    <td class="account-name">{{ $account->account_name }}</td>
                                    <td class="nilai">{{ number_format($account->balance, 2) }}</td>
                                </tr>
                                @php
                                    $equity += $account->balance;
                                @endphp
                            @endif
                        @endforeach
                    </tbody>
                </table>
                <div class="single-content">
                    <span class="deskripsi">Total Pasiva</span>
                    <span class="nilai">{{ number_format($equity, 2) }}</span>
                </div>
            </div>
        </div>
    </div>
</body>

</html>
