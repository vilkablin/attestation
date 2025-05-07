import React from "react";
import ServicePage from "../Components/Services/ServicePage/ServicePage";
import { Header } from "../Components/UI/Header/Header";
import { Footer } from "../Components/UI/Footer/Footer";

export default function Service({ service }) {
    return (
        <>
            <Header />
            <ServicePage service={service} />
            <Footer />
        </>
    );
}
