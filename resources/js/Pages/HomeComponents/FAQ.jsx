import React, { useState } from 'react';
import Accordion from '@mui/material/Accordion';
import AccordionSummary from '@mui/material/AccordionSummary';
import AccordionDetails from '@mui/material/AccordionDetails';
import ExpandMoreIcon from '@mui/icons-material/ExpandMore';
import DataFAQ from '../../Data/DataFAQ';
import { motion } from "framer-motion";

const FAQ = () => {
    const [expanded, setExpanded] = useState(false);

    const handleChange = (index) => (event, isExpanded) => {
        setExpanded(isExpanded ? index : false);
    };

    return (
        <section className="lg:px-24 px-10 font-montserrat pb-28">
            <div className='items-center flex flex-col gap-9'>
                <motion.h1
                    initial={{ opacity: 0, y: 50 }}
                    whileInView={{ opacity: 1, y: 0 }}
                    transition={{ duration: 0.5, ease: "easeOut" }}
                    className='text-cust-yellow font-bold text-2xl lg:text-4xl'
                >
                    FAQ
                </motion.h1>
                <div className='border-x-2 border-b-2 rounded-2xl shadow-lg py-3 px-4 lg:px-10'>
                    {DataFAQ.map((datafaq, index) => (
                        <Accordion
                            key={index}
                            expanded={expanded === index}
                            onChange={handleChange(index)}
                            sx={{ boxShadow: 'none', border: 'none', '&:before': { display: 'none' } }}
                            className='space-y-4'
                        >
                            <AccordionSummary
                                expandIcon={<ExpandMoreIcon />}
                                aria-controls={`panel${index}-content`}
                                id={`panel${index}-header`}
                                sx={{ border: 'none' }} 
                                className='font-bold text-base xl:text-xl tracking-widest'
                            >
                                <h2>{datafaq.question}</h2>
                            </AccordionSummary>
                            <AccordionDetails sx={{ border: 'none' }}> 
                                <p className='text-sm xl:text-lg tracking-wide'>{datafaq.answer}</p>
                            </AccordionDetails>
                        </Accordion>
                    ))}
                </div>
            </div>
        </section>
    );
}

export default FAQ;