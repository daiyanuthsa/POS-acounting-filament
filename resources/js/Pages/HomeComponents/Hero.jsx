import React, { useState, useEffect } from 'react';

const Hero = () => {
    const images = [
        './images/home/HeroImage1.webp',
        './images/home/HeroImage2.webp',
        //tambah je
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

            <div className='relative flex flex-col gap-16'>
                <h1 className='font-medium text-2xl lg:text-5xl tracking-wide text-white w-4/5 md:w-3/4 xl:w-1/2'>
                    Potensi Serta Kekayaan alam Kabupaten Manggarai Timur
                </h1>
                <p className='text-md lg:pb-10 lg:text-lg md:tracking-wide font-light text-cust-yellow2 text-justify w-4/5 md:w-3/4 xl:w-1/2'>
                    Selamat datang di Kabupaten Manggarai Timur, surga tersembunyi di Nusa Tenggara Timur! Dikenal dengan kekayaan alam yang menakjubkan, daerah ini menyuguhkan pemandangan yang memukau dan keanekaragaman hayati yang luar biasa.
                </p>
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
