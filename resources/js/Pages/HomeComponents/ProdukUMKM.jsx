import React from 'react'
import { motion } from "framer-motion";
import DataProduk from '../../Data/DataProduk';

const ProdukUMKM = () => {
    return (
        <section className='lg:px-40 px-10 font-montserrat my-24'>
            <div className='flex flex-col items-center'>
                <motion.h1
                    initial={{ opacity: 0, y: 50 }}
                    whileInView={{ opacity: 1, y: 0 }}
                    transition={{ duration: 0.5, ease: "easeOut" }}
                    className='text-center md:text-start font-bold text-2xl lg:text-4xl'
                >
                    Untuk Siapa <span className='text-cust-yellow'> UMKM Bajo</span>
                </motion.h1>

                <p className='mt-8 text-center'>
                    UMKM Bajo dirancang untuk semua pelaku usaha mikro, kecil, dan menengah, termasuk:
                </p>

                <div className='flex justify-center'>
                    <div className='grid grid-cols-1 md:grid-cols-2 gap-10 px-5 xl:px-0 mt-14 lg:mt-20'>
                        {DataProduk.map((dataproduk, index) => (
                            <motion.div
                                key={index}
                                initial={{ opacity: 0, y: 50 }}
                                whileInView={{ opacity: 1, y: 0 }}
                                transition={{ duration: 0.5, delay: index * 0.2, ease: "easeOut" }}
                                className='relative w-72 md:w-full'
                            >
                                <img
                                    src={dataproduk.img}
                                    alt="image"
                                    className='w-64 md:w-full h-60 lg:h-[250px] rounded-2xl object-cover'
                                />

                                <div className='absolute bottom-0 left-0 w-64 md:w-full bg-cust-yellow bg-opacity-80 rounded-b-2xl py-3'>
                                    <h1 className='text-black font-dmsans font-medium text-center text-sm'>
                                        {dataproduk.title}
                                    </h1>
                                </div>
                            </motion.div>
                        ))}
                    </div>
                </div>
            </div>
        </section>
    )
}

export default ProdukUMKM;