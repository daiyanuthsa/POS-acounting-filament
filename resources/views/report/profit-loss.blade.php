<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Neraca Bengkel Bang Jo</title>

    @vite('resources/css/app.css')
</head>

<body class="p-8">
    <section class='md:px-24 px-10 pt-20'>
        <div class='flex flex-col items-center'>
            <div class='title-report font-bold text-lg lg:text-2xl text-center'>
                <h1>{{ $merchant->name }}</h1>
                <h1>LAPORAN PERUBAHAN MODAL</h1>
                <h5>{{ $startDate }} - {{ $endDate }}</h5>
            </div>
            <div id='table-report' class='lg:w-1/2 w-full pt-10 lg:pt-20 text-sm space-y-10 lg:text-lg'>
                <div id='revenue'>
                    <table class="w-full font-bold">
                        <tbody>
                            <tr>
                                <td>PENDAPATAN/<i>REVENUE</i></td>
                                <td class="text-center border-b-2 px-1 lg:px-2 pb-2 border-black">
                                    {{ $endDate }}</td>
                                <td></td>
                            </tr>
                        </tbody>
                    </table>
                    <table class="w-full ">
                        <tbody>
                            @php
                                $totalRevenue = 0; // Initialize a variable to hold the total
                            @endphp

                            @foreach ($revenue as $account)
                                {{-- @if ($account->asset_type === 'current') --}}
                                <tr>
                                    <td class
                                    ='pl-5'>{{ $account->account_code }}
                                        {{ $account->account_name }}</td>
                                    <td class
                                    ="text-right">
                                        {{ number_format($account->balance, 2) }}</td>
                                </tr>
                                @php
                                    // Add the balance of the current account to the total
                                    $totalRevenue += $account->balance;
                                @endphp
                            @endforeach
                        </tbody>
                    </table>
                    <div class
                    ='flex justify-between font-bold'>
                        <h4>JUMLAH PENDAPATAN</h4>
                        <p>{{ number_format($totalRevenue, 2) }}</p>
                    </div>
                </div>

                <div id='bpp'>
                    <div class
                    ='flex justify-between font-bold'>
                        <h4>BEBAN POKOK PENJUALAN/<i>COST OF GOODS SOLD</i></h4>
                    </div>
                    <table class
                    ="w-full">
                        <tbody>
                            @php
                                $totalCOG = 0; // Initialize a variable to hold the total
                            @endphp

                            @foreach ($costOfGoods as $account)
                                <tr>
                                    <td class
                                    ='pl-5'>{{ $account->account_code }}
                                        {{ $account->account_name }}
                                    </td>
                                    <td class
                                    ="text-right">
                                        {{ number_format($account->balance, 2) }}</td>
                                </tr>
                                @php
                                    // Add the balance of the current account to the total
                                    $totalCOG += $account->balance;
                                @endphp
                            @endforeach
                        </tbody>
                    </table>
                    <div class
                    ='flex justify-between font-bold'>
                        <h4>JUMLAH BEBAN/<i>EXPENSES</i></h4>
                        <p>{{ number_format($totalCOG, 2) }}</p>
                    </div>
                </div>

                <div id='beban'>
                    <div class
                    ='flex justify-between font-bold'>
                        <h4>BEBAN/<i>EXPENSES</i></h4>
                    </div>
                    <table class
                    ="w-full">
                        <tbody>
                            @php
                                $totalExpense = 0; // Initialize a variable to hold the total
                            @endphp

                            @foreach ($expense as $account)
                                <tr>
                                    <td class
                                    ='pl-5'>{{ $account->account_code }}
                                        {{ $account->account_name }}
                                    </td>
                                    <td class
                                    ="text-right">
                                        {{ number_format($account->balance, 2) }}</td>
                                </tr>
                                @php
                                    // Add the balance of the current account to the total
                                    $totalExpense += $account->balance;
                                @endphp
                            @endforeach
                        </tbody>
                    </table>
                    <div class
                    ='flex justify-between font-bold'>
                        <h4>JUMLAH BEBAN/<i>EXPENSES</i></h4>
                        <p>{{ number_format($totalExpense, 2) }}</p>
                    </div>
                    <div id='profit' class
                    ='flex justify-between font-bold'>
                        <h4>LABA USAHA</h4>
                        <p>{{ number_format($totalRevenue - $totalCOG - $totalExpense, 2) }}</p>
                    </div>
                </div>
            </div>
        </div>
    </section>

</body>

</html>
