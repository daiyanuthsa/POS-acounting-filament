import React from 'react'

const Hero = () => {
    const images = [
        './images/home/HeroImage1.webp',
        './images/home/HeroImage2.webp',
    ]

    return (
        <section className='relative lg:px-24 px-10 font-dmsans overflow-hidden'>
            sek
            {/* <div className="absolute inset-0 -z-10 flex animate-scroll space-x-0">
                {images.map((image, index) => (
                    <img
                        key={index}
                        src={image}
                        alt={`Background ${index}`}
                        className="w-full h-auto object-cover min-w-full"
                    />
                ))}
            </div>

            <div className='relative flex flex-col gap-16'>
                <h1 className='font-medium text-5xl text-white'>Potensi Serta Kekayaan alam Kabupaten Maggarai Timur</h1>
                <p className='text-lg text-cust-yellow'>
                    Selamat datang di Kabupaten Manggarai Timur, surga tersembunyi di Nusa Tenggara Timur! Dikenal dengan kekayaan alam yang menakjubkan, daerah ini menyuguhkan pemandangan yang memukau dan keanekaragaman hayati yang luar biasa.
                </p>
            </div> */}
        </section>
    )
}

export default Hero
