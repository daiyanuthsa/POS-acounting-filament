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
            <h2 class="text-xl">Neraca</h2>
            <p>Per 31 Desember {{ $year }}</p>
            <p>(Dalam rupiah)</p>
        </div>

        <div class="flex justify-between">
            <!-- Aktiva Section -->
            <div class="w-1/2 border-r pr-4">
                <h3 class="font-bold mb-4">Aktiva</h3>
                <div class="mb-4">
                    <h4 class="font-semibold">Aktiva Tetap</h4>
                    <table class="w-full text-left mb-4">
                        <tbody>
                            @php
                                $totalcurrentAssets = 0; // Initialize a variable to hold the total
                            @endphp

                            @foreach ($balanceSheetData as $account)
                                @if ($account->asset_type === 'current')
                                    <tr>
                                        <td>{{ $account->account_code }}</td>
                                        <td>{{ $account->account_name }}</td>
                                        <td class="text-right">{{ number_format($account->balance, 2) }}</td>
                                    </tr>
                                    @php
                                        // Add the balance of the current account to the total
                                        $totalcurrentAssets += $account->balance;
                                    @endphp
                                @endif
                            @endforeach
                        </tbody>
                    </table>

                    <div class="flex justify-between font-semibold">
                        <span>Total Aktiva Tetap</span>
                        <span>{{ number_format($totalcurrentAssets, 2) }}</span>
                    </div>

                </div>
                <div>
                    <h4 class="font-semibold">Aktiva Tetap</h4>
                    <table class="w-full text-left mb-4">
                        <tbody>
                            @php
                                $totalFixedAssets = 0; // Initialize a variable to hold the total
                            @endphp

                            @foreach ($balanceSheetData as $account)
                                @if ($account->asset_type === 'fixed')
                                    <tr>
                                        <td>{{ $account->account_code }}</td>
                                        <td>{{ $account->account_name }}</td>
                                        <td class="text-right">{{ number_format($account->balance, 2) }}</td>
                                    </tr>
                                    @php
                                        // Add the balance of the current account to the total
                                        $totalFixedAssets += $account->balance;
                                    @endphp
                                @endif
                            @endforeach
                        </tbody>
                    </table>

                    <div class="flex justify-between font-semibold">
                        <span>Total Aktiva Tetap</span>
                        <span>{{ number_format($totalFixedAssets, 2) }}</span>
                    </div>

                </div>
                <div class="flex justify-between font-semibold mt-4">
                    <span>Total Aktiva</span>
                    <span>{{ number_format($totalFixedAssets + $totalcurrentAssets, 2) }}</span>
                </div>
            </div>

            <!-- Pasiva Section -->
            <div class="w-1/2 pl-4">
                <h3 class="font-bold mb-4">Pasiva</h3>
                <div class="mb-4">
                    <h4 class="font-semibold">Hutang dan Modal</h4>
                    <h5 class="font-semibold mt-2">Hutang Lancar</h5>
                    <table class="w-full text-left mb-4">
                        <tbody>
                            @php
                                $liability = 0; // Initialize a variable to hold the total
                            @endphp

                            @foreach ($pasiva as $account)
                                @if ($account->accountType === 'Liability')
                                    <tr>
                                        <td>{{ $account->account_code }}</td>
                                        <td>{{ $account->account_name }}</td>
                                        <td class="text-right">{{ number_format($account->balance, 2) }}</td>
                                    </tr>
                                    @php
                                        // Add the balance of the current account to the total
                                        $liability += $account->balance;
                                    @endphp
                                @endif
                            @endforeach
                        </tbody>
                    </table>
                    <div class="flex justify-between font-semibold mb-4">
                        <span>Total Hutang Lancar</span>
                        <span>{{ number_format($liability, 2) }}</span>
                    </div>
                </div>
                <div>
                    <h5 class="font-semibold mt-2">Modal</h5>
                    <table class="w-full text-left mb-4">
                        <tbody>
                            @php
                                $equity = 0; // Initialize a variable to hold the total
                            @endphp

                            @foreach ($pasiva as $account)
                                @if ($account->accountType === 'Equity')
                                    <tr>
                                        <td>{{ $account->account_code }}</td>
                                        <td>{{ $account->account_name }}</td>
                                        <td class="text-right">{{ number_format($account->balance, 2) }}</td>
                                    </tr>
                                    @php
                                        // Add the balance of the current account to the total
                                        $equity += $account->balance;
                                    @endphp
                                @endif
                            @endforeach
                        </tbody>
                    </table>
                </div>
                <div class="flex justify-between font-bold mt-4">
                    <span>Total Pasiva</span>
                    <span>{{ number_format($equity, 2) }}</span>
                </div>
            </div>
        </div>
    </div>
</body>

</html>
