import React from 'react'
import Nav from './HomeComponents/Nav'
import Footer from './HomeComponents/Footer'
import Hero from './HomeComponents/Hero'
import Profile from './HomeComponents/Profile'
import Wisata from './HomeComponents/Wisata'
import FAQ from './HomeComponents/FAQ'

const Home = () => {
    return (
        <section>
            <Nav />
            <Hero />
            <Profile />
            <Wisata />
            <FAQ />
            <Footer />
        </section>
    )
}

export default Home