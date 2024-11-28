import React from 'react'
import { motion } from "framer-motion";


const Profile = () => {
    return (
        <section className='lg:px-48 px-10 font-montserrat'>
            <div className='flex flex-col items-center'>
                <motion.h1
                    initial={{ opacity: 0, y: 50 }}
                    whileInView={{ opacity: 1, y: 0 }}
                    transition={{ duration: 0.5, ease: "easeOut" }}
                    className='text-cust-yellow font-bold text-2xl lg:text-4xl'
                >
                    Kenapa <span className='text-black'>UMKM Bajo?</span>
                </motion.h1>

                <div className='flex flex-col xl:flex-row gap-12 lg:gap-24 mt-14 lg:mt-[121px] items-center xl:items-start'>
                    <img src="./images/home/ProfilHero.webp" alt="image" className='w-[350px] lg:w-[500px]' />
                    <div id='text' className='text-justify'>
                        <p className='text-lg lg:text-2xl'>UMKM Bajo adalah platform yang dirancang untuk membantu pelaku UMKM di Kabupaten Manggarai Barat mencatat dan mengelola keuangan mereka dengan mudah. Tidak ada lagi pembukuan manual yang rumit dengan UMKM Bajo, semua bisa Anda kelola dalam satu platform.</p>
                    </div>
                </div>
            </div>
        </section>
    )
}

export default Profile
