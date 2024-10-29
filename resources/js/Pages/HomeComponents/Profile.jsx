import React from 'react'
import { motion } from "framer-motion";


const Profile = () => {
    return (
        <section className='lg:px-24 px-10 font-montserrat'>
            <div className='flex flex-col items-center'>
                <motion.h1
                    initial={{ opacity: 0, y: 50 }}
                    whileInView={{ opacity: 1, y: 0 }}
                    transition={{ duration: 0.5, ease: "easeOut" }}
                    className='text-cust-yellow font-bold text-2xl lg:text-4xl'
                >
                    Profil
                </motion.h1>

                <motion.h2
                    initial={{ opacity: 0, y: 50 }}
                    whileInView={{ opacity: 1, y: 0 }}
                    transition={{ duration: 0.5, ease: "easeOut", delay: 0.3 }}
                    className='font-bold text-2xl lg:text-4xl text-center'
                >
                    Kabupaten Manggarai Timur
                </motion.h2>

                <div className='flex flex-col xl:flex-row gap-12 lg:gap-24 mt-14 lg:mt-[121px] items-center xl:items-start'>
                    <img src="./images/home/ProfilHero.webp" alt="image" className='w-[500px]' />
                    <div id='text' className='space-y-3 text-justify'>
                        <p>Kabupaten Manggarai Timur adalah salah satu kabupaten yang berada di provinsi Nusa Tenggara Timur, Indonesia. Kabupaten Manggarai Timur merupakan hasil pemekaran dari Kabupaten Manggarai, tepatnya pada tanggal 17 Juli 2007. Luas Wilayahnya 2.643,41 km2 memiliki 9 kecamatan, 17 kelurahan dan 159 desa. Jumlah penduduk Kabupaten Manggarai Timur pada tahun 2021 sebanyak 276.155 jiwa. Pusat pemerintahan atau ibukota kabupaten berada di kecamatan Borong.</p>
                        <p>Kabupaten ini dihuni oleh beragam etnis, dengan mayoritas penduduk berasal dari suku Manggarai. Bahasa daerah yang digunakan adalah Bahasa Manggarai, selain Bahasa Indonesia sebagai bahasa resmi. Perekonomian Kabupaten Manggarai Timur didukung oleh sektor pertanian, perikanan, dan pariwisata. Komoditas utama meliputi kopi, padi, jagung, dan hasil perikanan. Potensi pariwisata, terutama di bidang ekowisata, semakin berkembang dengan adanya taman nasional dan objek wisata alam. Kabupaten ini kaya akan budaya dan tradisi. Masyarakatnya dikenal dengan keramahtamahan dan kesenian lokal, seperti tarian tradisional dan kerajinan tangan. Festival budaya sering diadakan untuk merayakan tradisi dan memperkenalkan kebudayaan lokal kepada pengunjung.</p>
                    </div>
                </div>
            </div>
        </section>
    )
}

export default Profile
