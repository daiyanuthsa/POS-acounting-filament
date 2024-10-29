import React from 'react'
import { motion } from "framer-motion";
import DataProduk from '../../Data/DataProduk';

const ProdukUMKM = () => {
    return (
        <section className='lg:px-24 px-10 font-montserrat my-24'>
            <div>
                <motion.h1
                    initial={{ opacity: 0, y: 50 }}
                    whileInView={{ opacity: 1, y: 0 }}
                    transition={{ duration: 0.5, ease: "easeOut" }}
                    className='text-cust-yellow font-bold text-2xl lg:text-4xl'
                >
                    Kenali Produk Lokal Manggarai Timur yang Berkualitas!
                </motion.h1>

                <p className='mt-8 hidden md:block'>
                    Di Kabupaten Manggarai Timur, UMKM lokal terus berkembang, menghadirkan produk-produk unggulan yang dibuat <br /> dengan tangan terampil dan penuh dedikasi. Mulai dari kerajinan tangan hingga kuliner khas, semua <br /> tersedia untuk mendukung perekonomian daerah dan melestarikan budaya setempat.
                </p>

                <p className='mt-8 md:hidden'>
                    Di Kabupaten Manggarai Timur, UMKM lokal terus berkembang, menghadirkan produk-produk unggulan yang dibuat <br /> dengan tangan terampil dan penuh dedikasi. Mulai dari kerajinan tangan hingga kuliner khas, semua <br /> tersedia untuk mendukung perekonomian daerah dan melestarikan budaya setempat.
                </p>
                <div className='flex flex-col md:flex-row gap-10 items-center md:justify-between mt-14 lg:mt-32'>
                    {DataProduk.map((dataproduk, index) => (
                        <motion.div
                            key={index}
                            initial={{ opacity: 0, y: 50 }}
                            whileInView={{ opacity: 1, y: 0 }}
                            transition={{ duration: 0.5, delay: index * 0.2, ease: "easeOut" }}
                            className=''
                        >
                            <img src={dataproduk.img} alt="image" className='w-60 md:w-48 lg:w-96 h-[200px] md:h-40 lg:h-[366px] rounded-t-2xl' />
                            <div className='bg-cust-yellow w-60 md:w-48 lg:w-96 flex flex-col items-center gap-3 p-5 lg:p-8 rounded-b-2xl'>
                                <h1 className='font-dmsans text-xl lg:text-2xl text-center'>{dataproduk.title}</h1>
                                <p className='font-plusJakarta font-light text-sm lg:text-sm text-center lg:text-start'>{dataproduk .description}</p>
                            </div>
                        </motion.div>
                    ))}
                </div>
            </div>
        </section>
    )
}

export default ProdukUMKM