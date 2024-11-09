import React from 'react';

const FinancialPosition = () => {
    return (
        <section className="md:px-24 px-10 pt-20">
            <div id='title-report' className='font-bold text-lg lg:text-2xl text-center'>
                <h1>NAMA PERUSAHAAN</h1>
                <h1>LAPORAN PERUBAHAN MODAL</h1>
                <h1>TAHUN 2024</h1>
            </div>

            <div className="flex flex-col md:flex-row md:justify-between mt-10 lg:mt-20 px-5 border-2 border-black">
                <div className="md:w-1/2 border-b-2 pb-10 md:border-r-2 md:pr-4 border-black">
                    <h3 className="font-bold mb-4 text-md lg:text-xl">AKTIVA</h3>

                    <div id='aktiva-lancar' className="mb-4 text-sm lg:text-base">
                        <h4 className="font-bold">AKTIVA LANCAR</h4>
                        <table className="w-full text-left mb-4">
                            <tbody>
                                <tr>
                                    <td>1-110</td>
                                    <td>KAS</td>
                                    <td className="text-right">1,200,000</td>
                                </tr>
                                <tr>
                                    <td>1-110</td>
                                    <td>KAS</td>
                                    <td className="text-right">1,200,000</td>
                                </tr>
                                <tr>
                                    <td>1-110</td>
                                    <td>KAS</td>
                                    <td className="text-right">1,200,000</td>
                                </tr>
                                <tr>
                                    <td>1-110</td>
                                    <td>KAS</td>
                                    <td className="text-right">1,200,000</td>
                                </tr>
                            </tbody>
                        </table>
                        <div className="flex justify-between font-bold">
                            <div>JUMLAH AKTIVA LANCAR</div>
                            <div>1,200,000</div>
                        </div>
                    </div>

                    <div id='aktiva-tetap' className='text-sm lg:text-base'>
                        <h4 className="font-bold">AKTIVA TETAP</h4>
                        <table className="w-full text-left mb-4">
                            <tbody>
                                <tr>
                                    <td>1-110</td>
                                    <td>KAS</td>
                                    <td className="text-right">1,200,000</td>
                                </tr>
                                <tr>
                                    <td>1-110</td>
                                    <td>KAS</td>
                                    <td className="text-right">1,200,000</td>
                                </tr>
                                <tr>
                                    <td>1-110</td>
                                    <td>KAS</td>
                                    <td className="text-right">1,200,000</td>
                                </tr>
                                <tr>
                                    <td>1-110</td>
                                    <td>KAS</td>
                                    <td className="text-right">1,200,000</td>
                                </tr>
                            </tbody>
                        </table>
                        <div className="flex justify-between font-bold">
                            <div>JUMLAH AKTIVA TETAP</div>
                            <div>1,200,000</div>
                        </div>
                    </div>

                    <div className="flex justify-between font-bold mt-4 text-sm lg:text-base">
                        <div>JUMLAH AKTIVA</div>
                        <div>1,200,000</div>
                    </div>
                </div>

                <div className="md:w-1/2 md:pl-4 pt-10">
                    <h3 className="font-bold mb-4 text-md lg:text-xl">PASIVA</h3>

                    <div id='hutang' className="mb-4 text-sm lg:text-base">
                        <h4 className="font-bold">HUTANG</h4>
                        <table className="w-full text-left mb-4">
                            <tbody>
                                <tr>
                                    <td>1-110</td>
                                    <td>KAS</td>
                                    <td className="text-right">1,200,000</td>
                                </tr>
                                <tr>
                                    <td>1-110</td>
                                    <td>KAS</td>
                                    <td className="text-right">1,200,000</td>
                                </tr>
                                <tr>
                                    <td>1-110</td>
                                    <td>KAS</td>
                                    <td className="text-right">1,200,000</td>
                                </tr>
                            </tbody>
                        </table>
                        <div className="flex justify-between font-bold mb-4">
                            <div>JUMLAH HUTANG</div>
                            <div>1,200,000</div>
                        </div>
                    </div>

                    <div id='modal' className='text-sm lg:text-base'>
                        <h4 className="font-bold mt-2">MODAL</h4>
                        <table className="w-full text-left mb-4">
                            <tbody>
                                <tr>
                                    <td>1-110</td>
                                    <td>KAS</td>
                                    <td className="text-right">1,200,000</td>
                                </tr>
                                <tr>
                                    <td>1-110</td>
                                    <td>KAS</td>
                                    <td className="text-right">1,200,000</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                    <div className="flex justify-between font-bold mt-4 text-sm lg:text-base">
                        <div>JUMLAH PASIVA</div>
                        <div>1,200,000</div>
                    </div>
                </div>
            </div>
        </section>
    );
}

export default FinancialPosition;