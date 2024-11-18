import React from 'react';

const FinancialPosition = () => {
    return (
        <section className="flex justify-center items-center py-20">
            <div className="w-2/6">
                <div id="title-report" className="font-bold text-lg lg:text-2xl text-center mb-10">
                    <h1 className='font-anonymusPro'>NAMA PERUSAHAAN</h1>
                    <h1 className='text-red-700'>Neraca (Standar)</h1>
                    <h1 className='font-anonymusPro'>Per Tgl. 31 Jan 2021</h1>
                </div>

                <div>
                    <div className='flex justify-between text-blue-800 font-semibold mb-5 border-b'>
                        <p>Deskripsi</p>
                        <p>Nilai (IDR)</p>
                    </div>
                    {/* ASET */}
                    <div className="mb-10">
                        <h3 className="font-bold text-lg mb-5">ASET</h3>
                        <div className="pl-5">
                            <h4 className="font-bold mb-2">Aset Lancar</h4>
                            <table className="w-full mb-5">
                                <tbody>
                                    <tr>
                                        <td>Kas dan Setara Kas</td>
                                        <td className="text-right">490.744.920</td>
                                    </tr>
                                    <tr>
                                        <td>Piutang Usaha</td>
                                        <td className="text-right">73.664.700</td>
                                    </tr>
                                    <tr>
                                        <td>Persediaan</td>
                                        <td className="text-right">115.036.000</td>
                                    </tr>
                                    <tr>
                                        <td>Aset Lancar Lainnya</td>
                                        <td className="text-right">56.021.500</td>
                                    </tr>
                                    <tr className="font-bold border-t">
                                        <td>Jumlah Aset Lancar</td>
                                        <td className="text-right">735.467.120</td>
                                    </tr>
                                </tbody>
                            </table>

                            <h4 className="font-bold mb-2">Aset Tidak Lancar</h4>
                            <table className="w-full mb-5">
                                <tbody>
                                    <tr>
                                        <td>Nilai Histori</td>
                                        <td className="text-right">1.243.680.000</td>
                                    </tr>
                                    <tr>
                                        <td>Akumulasi Penyusutan</td>
                                        <td className="text-right">-77.160.000</td>
                                    </tr>
                                    <tr className="font-bold border-t">
                                        <td>Jumlah Aset Tidak Lancar</td>
                                        <td className="text-right">1.166.520.000</td>
                                    </tr>
                                </tbody>
                            </table>
                            <div className="font-bold border-t pt-2">
                                <div className="flex justify-between">
                                    <span>JUMLAH ASET</span>
                                    <span>1.901.987.120</span>
                                </div>
                            </div>
                        </div>
                    </div>

                    {/* LIABILITAS DAN EKUITAS */}
                    <div>
                        <h3 className="font-bold text-lg mb-5">LIABILITAS DAN EKUITAS</h3>
                        <div className="pl-5">
                            <h4 className="font-bold mb-2">Liabilitas</h4>
                            <table className="w-full mb-5">
                                <tbody>
                                    <tr>
                                        <td>Liabilitas Jangka Pendek</td>
                                        <td className="text-right">72.860.920</td>
                                    </tr>
                                    <tr>
                                        <td>Liabilitas Jangka Panjang</td>
                                        <td className="text-right">356.000.000</td>
                                    </tr>
                                    <tr className="font-bold border-t">
                                        <td>Jumlah Liabilitas</td>
                                        <td className="text-right">428.860.920</td>
                                    </tr>
                                </tbody>
                            </table>

                            <h4 className="font-bold mb-2">Ekuitas</h4>
                            <table className="w-full mb-5">
                                <tbody>
                                    <tr>
                                        <td>Laba Ditahan</td>
                                        <td className="text-right">445.880.000</td>
                                    </tr>
                                    <tr>
                                        <td>Modal Pribadi</td>
                                        <td className="text-right">975.000.000</td>
                                    </tr>
                                    <tr>
                                        <td>Laba Tahun Ini</td>
                                        <td className="text-right">52.246.200</td>
                                    </tr>
                                    <tr className="font-bold border-t">
                                        <td>Jumlah Ekuitas</td>
                                        <td className="text-right">1.473.126.200</td>
                                    </tr>
                                </tbody>
                            </table>
                            <div className="font-bold border-t pt-2">
                                <div className="flex justify-between">
                                    <span>JUMLAH LIABILITAS DAN EKUITAS</span>
                                    <span>1.901.987.120</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    );
};

export default FinancialPosition;