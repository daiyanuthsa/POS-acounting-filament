<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Neraca Bengkel Bang Jo</title>

    @vite('resources/css/app.css')
</head>

<body class="p-8">
    <section className='md:px-24 px-10 pt-20'>
        <div className='flex flex-col items-center'>
            <div className='title-report font-bold text-lg lg:text-2xl text-center'>
                <h1>{{ $merchant }}</h1>
                <h1>LAPORAN PERUBAHAN MODAL</h1>
                <h1>TAHUN {{ $year }}</h1>
            </div>
            <div id='table-report' className='lg:w-1/2 w-full pt-10 lg:pt-20 text-sm space-y-2 lg:text-lg'>
                <div className='flex justify-between font-bold'>
                    <h4>MODAL AWAL</h4>
                    <p>{{ number_format($openningBalance, 2) }}</p>
                </div>
                <h4 className='font-bold'>PERUBAHAN</h4>
                <table class="w-full">
                    <tbody>
                        @php
                            $totalMovement = 0; // Initialize a variable to hold the total
                        @endphp
                        @foreach ($equityMovement as $account)
                            <tr>
                                <td>{{ $account->account_code }}</td>
                                <td>{{ $account->account_name }}</td>
                                <td class="text-right">{{ number_format($account->balance, 2) }}</td>
                            </tr>
                            @php
                                $totalMovement += $account->balance;
                            @endphp
                        @endforeach
                    </tbody>
                </table>
                <div className='flex justify-between font-bold'>
                    <h4>TOTAL PERUBAHAN MODAL</h4>
                    <p>{{ number_format($totalMovement, 2) }}</p>
                </div>
                <div className='flex justify-between font-bold'>
                    <h4>MODAL AKHIR</h4>
                    <p>{{ number_format($openningBalance + $totalMovement, 2) }}</p>
                </div>
            </div>
        </div>
    </section>
</body>

</html>
