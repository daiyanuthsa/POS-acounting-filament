import React from 'react';
import { motion } from "framer-motion";
import DataFitur from '../../Data/DataFitur';

const Fitur = () => {
    return (
        <section className='lg:px-24 px-10 font-montserrat my-24'>
            <div>
                <motion.h1
                    initial={{ opacity: 0, y: 50 }}
                    whileInView={{ opacity: 1, y: 0 }}
                    transition={{ duration: 0.5, ease: "easeOut" }}
                    className='text-cust-yellow font-bold text-2xl text-center md:text-start lg:text-4xl'
                >
                    Fitur <span className='text-black'>Unggulan</span>
                </motion.h1>

                <p className='mt-8 text-center lg:text-justify text-lg'>
                    Di Kabupaten Manggarai Barat, UMKM lokal terus berkembang, menghadirkan produk-produk unggulan yang dibuat dengan tangan terampil dan penuh dedikasi. Mulai dari kerajinan tangan hingga kuliner khas, semua tersedia untuk mendukung perekonomian daerah dan melestarikan budaya setempat. Berikut adalah fitur unggulan yang kami tawarkan
                </p>

                <div className='flex justify-center'>
                    <div className='grid grid-cols-1 md:grid-cols-2 xl:grid-cols-3 gap-10 px-5 xl:px-0 mt-14 lg:mt-20 items-center'>
                        {DataFitur.map((datafitur, index) => (
                            <motion.div
                                key={index}
                                initial={{ opacity: 0, y: 50 }}
                                whileInView={{ opacity: 1, y: 0 }}
                                transition={{ duration: 0.5, delay: index * 0.2, ease: "easeOut" }}
                                className=''
                            >
                                <img src={datafitur.img} alt="image" className='w-64 md:w-full h-[200px] md:h-40 lg:h-[306px] rounded-t-2xl' />
                                <div className='bg-cust-yellow w-64 md:w-full items-center space-y-2 lg:space-y-3 p-5 lg:p-8 rounded-b-2xl h-60'>
                                    <h1 className='font-dmsans text-lg lg:text-xl xl:text-2xl text-center'>{datafitur.title}</h1>
                                    <p className='font-plusJakarta font-light text-xs lg:text-sm text-center lg:text-start'>{datafitur.description}</p>
                                </div>
                            </motion.div>
                        ))}
                    </div>
                </div>
            </div>
        </section>
    );
};

export default Fitur;