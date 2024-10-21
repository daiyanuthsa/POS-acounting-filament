<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Neraca Bengkel Bang Jo</title>

    <?php echo app('Illuminate\Foundation\Vite')('resources/css/app.css'); ?>
</head>
<body class="p-8">
    <div class="w-full">
        <div class="text-center mb-8">
            <h1 class="text-2xl font-bold">Bengkel Bang Jo</h1>
            <h2 class="text-xl">Neraca</h2>
            <p>Per 31 Desember 2020</p>
            <p>(Dalam rupiah)</p>
        </div>
        <div class="flex justify-between">
            <!-- Aktiva Section -->
            <div class="w-1/2 border-r pr-4">
                <h3 class="font-bold mb-4">Aktiva</h3>
                <div class="mb-4">
                    <h4 class="font-semibold">Aktiva Lancar</h4>
                    <table class="w-full text-left mb-4">
                        <tbody>
                            <tr>
                                <td>111</td>
                                <td>Kas</td>
                                <td class="text-right">25,765,000</td>
                            </tr>
                            <tr>
                                <td>112</td>
                                <td>Piutang</td>
                                <td class="text-right">2,025,000</td>
                            </tr>
                            <tr>
                                <td>113</td>
                                <td>Asuransi Dibayar Di muka</td>
                                <td class="text-right">1,000,000</td>
                            </tr>
                            <tr>
                                <td>114</td>
                                <td>Perlengkapan Bengkel</td>
                                <td class="text-right">26,925,000</td>
                            </tr>
                            <tr>
                                <td>115</td>
                                <td>Perlengkapan Kantor</td>
                                <td class="text-right">2,200,000</td>
                            </tr>
                        </tbody>
                    </table>
                    <div class="flex justify-between font-semibold mb-4">
                        <span>Total Aktiva Lancar</span>
                        <span>57,915,000</span>
                    </div>
                </div>
                <div>
                    <h4 class="font-semibold">Aktiva Tetap</h4>
                    <table class="w-full text-left mb-4">
                        <tbody>
                            <tr>
                                <td>121</td>
                                <td>Peralatan Bengkel</td>
                                <td class="text-right">27,800,000</td>
                            </tr>
                            <tr>
                                <td>1211</td>
                                <td>Akum. Peny. Peralatan Bengkel</td>
                                <td class="text-right">1,490,000</td>
                            </tr>
                            <tr>
                                <td>122</td>
                                <td>Peralatan Kantor</td>
                                <td class="text-right">3,560,000</td>
                            </tr>
                            <tr>
                                <td>1221</td>
                                <td>Akum. Peny. Peralatan Kantor</td>
                                <td class="text-right">520,000</td>
                            </tr>
                            <tr>
                                <td>123</td>
                                <td>Gedung</td>
                                <td class="text-right">20,000,000</td>
                            </tr>
                            <tr>
                                <td>1231</td>
                                <td>Akum. Peny. Gedung</td>
                                <td class="text-right">1,300,000</td>
                            </tr>
                            <tr>
                                <td>124</td>
                                <td>Tanah</td>
                                <td class="text-right">30,000,000</td>
                            </tr>
                        </tbody>
                    </table>
                    <div class="flex justify-between font-semibold">
                        <span>Total Aktiva Tetap</span>
                        <span>78,050,000</span>
                    </div>
                </div>
                <div class="flex justify-between font-bold mt-4">
                    <span>Total Aktiva</span>
                    <span>135,965,000</span>
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
                            <tr>
                                <td>211</td>
                                <td>Utang Usaha</td>
                                <td class="text-right">30,525,000</td>
                            </tr>
                            <tr>
                                <td>212</td>
                                <td>Utang Gaji</td>
                                <td class="text-right">250,000</td>
                            </tr>
                        </tbody>
                    </table>
                    <div class="flex justify-between font-semibold mb-4">
                        <span>Total Hutang Lancar</span>
                        <span>30,775,000</span>
                    </div>
                </div>
                <div>
                    <h5 class="font-semibold mt-2">Modal</h5>
                    <table class="w-full text-left mb-4">
                        <tbody>
                            <tr>
                                <td>311</td>
                                <td>Modal Tn. Jo</td>
                                <td class="text-right">105,190,000</td>
                            </tr>
                        </tbody>
                    </table>
                </div>
                <div class="flex justify-between font-bold mt-4">
                    <span>Total Pasiva</span>
                    <span>135,965,000</span>
                </div>
            </div>
        </div>
    </div>
</body>
</html>
<?php /**PATH C:\laragon\www\filament-POS-Accounting\resources\views/report/balance-sheet.blade.php ENDPATH**/ ?>