import React from "react";
import { Header } from "../Components/UI/Header/Header";
import { Footer } from "../Components/UI/Footer/Footer";
import AppointmentCreatePage from "../Components/Appointments/AppointmentCreate";

export default function Appointment({ services, locations }) {
    return (
        <>
            <Header />
            <AppointmentCreatePage service={services} locations={locations} />
            <Footer />
        </>
    );
}
