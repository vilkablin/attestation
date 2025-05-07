import React from "react";
import { Head } from "@inertiajs/react";
import { Intro } from "../Components/Sections/IntroSection/Intro";
import { Header } from "../Components/UI/Header/Header";
import { PopularServicesSection } from "../Components/Sections/PopularServicesSection/PopularServicesSection";
import { FeaturesSection } from "../Components/Sections/FeaturesSection/FeaturesSection";
import { GallerySection } from "../Components/Sections/GallerySection/GallerySection";
import { CallBtn } from "../Components/UI/CallBtn/CallBtn";
import { Footer } from "../Components/UI/Footer/Footer";
import { FAQ } from "../Components/Sections/Faq/FAQ";

export default function Home({ services }) {
    return (
        <>
            <Head title="Главная" />
            <Header />
            <CallBtn />
            <Intro />
            <PopularServicesSection services={services} />
            <FeaturesSection />
            <GallerySection />
            <FAQ />
            <Footer />
        </>
    );
}
