import React from "react";
import { Header } from "../Components/UI/Header/Header";
import { Footer } from "../Components/UI/Footer/Footer";
import AppointmentCreatePage from "../Components/Appointments/AppointmentCreate";

export default function Appointment({ service, locations }) {
    return (
        <>
            <Header />
            <AppointmentCreatePage service={service} locations={locations} />
            <Footer />
        </>
    );
}
