import React from 'react'
import ApplicationLogo from '../../Components/ApplicationLogo'

const Footer = () => {
    return (
        <section className='lg:px-20 px-10 py-7 lg:py-14 bg-cust-yellow'>
            <div className='flex flex-col md:flex-row gap-6 lg:gap-0 justify-between'>
                <div className='space-y-6'>
                    <ApplicationLogo />
                    <h1 className='font-bold font-plusJakarta text-xs lg:text-lg'>Selamat datang di Kabupaten Manggarai Timur, <br/> surga tersembunyi di Nusa Tenggara Timur!</h1>
                </div>

                <div className='flex space-x-7 xl:space-x-20'>
                    <div className='space-y-6'>
                        <h1 className='font-bold font-plusJakarta text-sm lg:text-lg xl:text-2xl'>More on The Blog</h1>
                        <ul className='font-montserrat flex-col flex gap-6 text-xs lg:text-lg'>
                            <li>About Kabupaten Maggarai</li>
                            <li>Contributors & Writers</li>
                            <li>Write For Us</li>
                            <li>Contact Us</li>
                            <li>Privacy Policy</li>
                        </ul>
                    </div>
                    <div className='space-y-6'>
                        <h1 className='font-bold font-plusJakarta text-sm lg:text-lg xl:text-2xl'>More on MNTN</h1>
                        <ul className='font-montserrat flex-col flex gap-6 text-xs lg:text-lg'>
                            <li>The Team</li>
                            <li>Press</li>
                        </ul>
                    </div>
                </div>
            </div>
            <h1 className='mt-6 font-montserrat flex-col flex gap-6 text-xs lg:text-lg font-light'>Copyright 2024 Kabupaten Maggarai</h1>
        </section>
    )
}

export default Footer