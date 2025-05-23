import React from "react";
import { Head } from "@inertiajs/react";
import { Header } from "../Components/UI/Header/Header";
import DashboardPage from "../Components/Dashboard/DashboardPage";
import { Footer } from "../Components/UI/Footer/Footer";

export default function Dashboard() {
    return (
        <>
            <Head title="Личный кабинет" />
            <Header />
            <DashboardPage />
            <Footer />
        </>
    );
}
