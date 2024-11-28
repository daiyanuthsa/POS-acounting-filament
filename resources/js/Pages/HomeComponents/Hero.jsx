import React, { useState, useEffect } from 'react';
import { motion } from "framer-motion";

const Hero = () => {
    const images = [
        './images/home/HeroImage1.webp',
        './images/home/HeroImage2.webp',
    ];

    const [currentImageIndex, setCurrentImageIndex] = useState(0);

    const nextImage = () => {
        setCurrentImageIndex((prevIndex) =>
            prevIndex === images.length - 1 ? 0 : prevIndex + 1
        );
    };

    const prevImage = () => {
        setCurrentImageIndex((prevIndex) =>
            prevIndex === 0 ? images.length - 1 : prevIndex - 1
        );
    };

    useEffect(() => {
        const interval = setInterval(nextImage, 2000);
        return () => clearInterval(interval);
    }, []);

    return (
        <section className='relative lg:px-24 px-10 py-36 lg:py-52 mb-7 md:mb-11 font-dmsans overflow-hidden'>
            <div className="absolute inset-0 -z-10 flex transition-opacity duration-1000">
                <img
                    src={images[currentImageIndex]}
                    alt={`Background ${currentImageIndex}`}
                    className="w-full h-auto object-cover min-w-full"
                />
            </div>

            <div className='relative flex flex-col gap-10 lg:gap-16 '>
                <motion.h1
                    initial={{ opacity: 0, y: 50 }}
                    whileInView={{ opacity: 1, y: 0 }}
                    transition={{ duration: 0.5, ease: "easeOut" }}
                    className='font-medium text-2xl lg:text-5xl tracking-wide text-center md:text-start text-white md:w-3/4 xl:w-1/2'
                >
                    UMKM Bajo: Cerdas Kelola Keuangan, Mudah Kembangkan Usaha
                </motion.h1>
                <motion.p
                    initial={{ opacity: 0, y: 50 }}
                    whileInView={{ opacity: 1, y: 0 }}
                    transition={{ duration: 0.5, ease: "easeOut", delay: 0.3 }}
                    className='text-md lg:pb-10 lg:text-lg md:tracking-wide font-light text-cust-yellow2 text-center md:text-justify md:w-3/4 xl:w-1/2'
                >
                    Kelola uang usaha Anda lebih efisien dengan fitur pencatatan keuangan digital yang dirancang khusus untuk pelaku UMKM.
                </motion.p>
            </div>
            <div className='absolute bottom-0 left-0 w-full'>
                <img
                    src='./images/home/patternHero.webp'
                    alt="pattern"
                    className='w-full h-auto'
                />
            </div>
        </section>
    );
};

export default Hero;
