import React from "react";
import { ServicesPage } from "../Components/Services/ServicesPage/ServicesPage";
import { Header } from "../Components/UI/Header/Header";
import { Footer } from "../Components/UI/Footer/Footer";

export default function Services({ services, filters }) {
    return (
        <>
            <Header />
            <ServicesPage services={services} filters={filters} />
            <Footer />
        </>
    );
}
