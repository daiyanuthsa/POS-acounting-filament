import { Link } from '@inertiajs/react'
import React from 'react'
import ApplicationLogo from '../../Components/ApplicationLogo'

const Nav = () => {
    return (
        <section className='lg:px-20 px-10 py-4'>
            <div className='flex justify-between items-center'>
                <ApplicationLogo />
                <Link>
                    <button className='font-bold font-dmsans text-sm lg:text-lg rounded-xl bg-cust-yellow py-2 px-5 lg:py-2 lg:px-9 transition duration-300 ease-in-out transform hover:scale-105 active:scale-95'>Login</button>
                </Link>
            </div>
        </section>
    )
}

export default Nav
