import React from 'react';

const ProfitLoss = () => {
    return (
        <section className='md:px-24 px-10 pt-20'>
            <div className='flex flex-col items-center'>
                <div className='title-report font-bold text-lg lg:text-2xl text-center'>
                    <h1>NAMA PERUSAHAAN</h1>
                    <h1>LAPORAN PERUBAHAN MODAL</h1>
                </div>
                <div id='table-report' className='lg:w-1/2 w-full pt-10 lg:pt-20 text-sm space-y-10 lg:text-lg'>
                    <div id='revenue'>
                        <table className="w-full font-bold">
                            <tbody>
                                <tr>
                                    <td>PENDAPATAN/<i>REVENUE</i></td>
                                    <td className="text-center border-b-2 px-1 lg:px-2 pb-2 border-black">(PERIODE)</td>
                                    <td></td>
                                </tr>
                            </tbody>
                        </table>
                        <table className="w-full ">
                            <tbody>
                                <tr>
                                    <td className='pl-5'>Pendapatan/<i>Revenue</i></td>
                                    <td className="text-right">50.000.000</td>
                                </tr>
                                <tr>
                                    <td className='pl-5'>Pendapatan Produk 1</td>
                                    <td className="text-right">120.000.000</td>
                                </tr>
                                <tr>
                                    <td className='pl-5'>Pendapatan Produk 2</td>
                                    <td className="text-right border-b-2 pb-2 border-black">120.000.000</td>
                                </tr>
                            </tbody>
                        </table>
                        <div className='flex justify-between font-bold'>
                            <h4>JUMLAH PENDAPATAN</h4>
                            <p>200.000.000</p>
                        </div>
                    </div>

                    <div id='bpp'>
                        <div className='flex justify-between font-bold'>
                            <h4>BEBAN POKOK PENJUALAN/<i>COST OF GOODS SOLD</i></h4>
                        </div>
                        <table className="w-full">
                            <tbody>
                                <tr>
                                    <td className='pl-5'>Pendapatan/<i>Revenue</i></td>
                                    <td className="text-right">50.000.000</td>
                                </tr>
                                <tr>
                                    <td className='pl-5'>Pendapatan Produk 1</td>
                                    <td className="text-right">120.000.000</td>
                                </tr>
                                <tr>
                                    <td className='pl-5'>Pendapatan Produk 2</td>
                                    <td className="text-right border-b-2 pb-2 border-black">120.000.000</td>
                                </tr>
                            </tbody>
                        </table>
                        <div className='flex justify-between font-bold'>
                            <h4>JUMLAH BEBAN/<i>EXPENSES</i></h4>
                            <p>200.000.000</p>
                        </div>
                    </div>

                    <div id='beban'>
                        <div className='flex justify-between font-bold'>
                            <h4>BEBAN/<i>EXPENSES</i></h4>
                        </div>
                        <table className="w-full">
                            <tbody>
                                <tr>
                                    <td className='pl-5'>Pendapatan/<i>Revenue</i></td>
                                    <td className="text-right">50.000.000</td>
                                </tr>
                                <tr>
                                    <td className='pl-5'>Pendapatan Produk 1</td>
                                    <td className="text-right">120.000.000</td>
                                </tr>
                                <tr>
                                    <td className='pl-5'>Pendapatan Produk 2</td>
                                    <td className="text-right border-b-2 pb-2 border-black">120.000.000</td>
                                </tr>
                            </tbody>
                        </table>
                        <div className='flex justify-between font-bold'>
                            <h4>JUMLAH BEBAN/<i>EXPENSES</i></h4>
                            <p>200.000.000</p>
                        </div>
                        <div id='profit' className='flex justify-between font-bold'>
                            <h4>LABA USAHA</h4>
                            <p>1.200.000.000</p>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    );
};

export default ProfitLoss;
