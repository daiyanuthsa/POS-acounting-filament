import React from 'react'

const ChangesInEquity = () => {
    return (
        <section className='md:px-24 px-10 pt-20'>
            <div className='flex flex-col items-center'>
                <div className='title-report font-bold text-lg lg:text-2xl text-center'>
                    <h1>NAMA PERUSAHAAN</h1>
                    <h1>LAPORAN PERUBAHAN MODAL</h1>
                    <h1>TAHUN 2024</h1>
                </div>
                <div id='table-report' className='lg:w-1/2 w-full pt-10 lg:pt-20 text-sm space-y-2 lg:text-lg'>
                    <div className='flex justify-between font-bold'>
                        <h4>MODAL AWAL</h4>
                        <p>1.200.000</p>
                    </div>
                    <h4 className='font-bold'>PERUBAHAN</h4>
                    <table class="w-full">
                        <tbody>
                            <tr>
                                <td>1-110</td>
                                <td>PRIVE</td>
                                <td class="text-right">1.200.000</td>
                            </tr>
                            <tr>
                                <td>1-110</td>
                                <td>MODAL</td>
                                <td class="text-right">1.200.000</td>
                            </tr>
                        </tbody>
                    </table>
                    <div className='flex justify-between font-bold'>
                        <h4>TOTAL PERUBAHAN MODAL</h4>
                        <p>1.200.000</p>
                    </div>
                    <div className='flex justify-between font-bold'>
                        <h4>MODAL AKHIR</h4>
                        <p>1.200.000</p>
                    </div>
                </div>
            </div>
        </section>
    )
}

export default ChangesInEquity
