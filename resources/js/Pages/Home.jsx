import React from 'react'
import Nav from './HomeComponents/Nav'
import Footer from './HomeComponents/Footer'
import Hero from './HomeComponents/Hero'
import Profile from './HomeComponents/Profile'
import FAQ from './HomeComponents/FAQ'
import ProdukUMKM from './HomeComponents/ProdukUMKM'
import Fitur from './HomeComponents/Fitur'
import Closing from './HomeComponents/Closing'

const Home = () => {
    return (
        <section>
            <Nav />
            <Hero />
            <Profile />
            <Fitur />
            <ProdukUMKM />
            <FAQ />
            <Closing />
            <Footer />
        </section>
    )
}

export default Home