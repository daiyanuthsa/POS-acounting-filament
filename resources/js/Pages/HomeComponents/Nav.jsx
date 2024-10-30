import { Link } from '@inertiajs/react';
import React, { useState, useEffect } from 'react';
import ApplicationLogo from '../../Components/ApplicationLogo';

const Nav = () => {
    const [isScrolled, setIsScrolled] = useState(false);

    useEffect(() => {
        const handleScroll = () => {
            setIsScrolled(window.scrollY > 50);
        };

        window.addEventListener('scroll', handleScroll);

        return () => {
            window.removeEventListener('scroll', handleScroll);
        };
    }, []);

    return (
        <section
            className={`fixed top-0 w-full z-50 lg:px-20 px-10 py-4 border-b-2 rounded-b-2xl shadow-md bg-white transition-all duration-300 ease-in-out ${isScrolled ? 'py-2 shadow-cust-yellow2 shadow-sm' : 'py-4'
                }`}
        >
            <div className='flex justify-between items-center'>
                <ApplicationLogo />
                <Link>
                    <button className='font-bold font-dmsans text-sm lg:text-lg rounded-xl bg-cust-yellow py-2 px-5 lg:py-2 lg:px-9 transition duration-300 ease-in-out transform hover:scale-105 active:scale-95'>
                        Login
                    </button>
                </Link>
            </div>
        </section>
    );
};

export default Nav;