import React from 'react';
import { motion } from "framer-motion";

const Closing = () => {
    return (
        <section className='lg:px-40 px-10 font-montserrat my-24'>
            <div className='flex flex-col'>
                <motion.h1
                    initial={{ opacity: 0, y: 50 }}
                    whileInView={{ opacity: 1, y: 0 }}
                    transition={{ duration: 0.5, ease: "easeOut" }}
                    className='font-semibold text-2xl lg:text-4xl text-center'
                >
                    "Mulai Kelola Keuangan dengan Lebih Mudah!"
                </motion.h1>
                <p className='text-lg lg:text-xl text-center lg:text-justify mt-8'>
                    Bergabunglah dengan UMKM Bajo sekarang dan nikmati pencatatan keuangan yang simpel dan efisien. Klik
                    <span
                        onClick={() => window.location.assign("/merchant/login")}
                        style={{ textDecoration: 'underline', cursor: 'pointer', marginRight: '10px', marginLeft: '10px' }}
                    >
                        Daftar Sekarang
                    </span>
                    untuk mengembangkan bisnis Anda hari ini!
                </p>
                <p className='text-cust-yellow font-semibold mt-4 text-sm lg:text-base'>UMKM Bojo: <span className='text-black'>Solusi digital untuk usaha yang terus maju.</span></p>
            </div>
        </section>
    );
}

export default Closing;
