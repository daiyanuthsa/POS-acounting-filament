import { Link } from '@inertiajs/react'
import React from 'react'

const ApplicationLogo = () => {
    return (
        <Link className="transition duration-300 ease-in-out transform hover:scale-105 active:scale-95 font-dmsans">
            <img src="Logo.webp" alt="logo" className='w-20'/>
        </Link>
    )
}

export default ApplicationLogo
