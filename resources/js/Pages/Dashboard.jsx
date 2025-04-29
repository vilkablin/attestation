import React from "react";
import { Head } from "@inertiajs/react";
import { Header } from "../Components/UI/Header/Header";
import DashboardPage from "../Components/Dashboard/DashboardPage";

export default function Dashboard() {
    return (
        <>
            <Head title="Личный кабинет" />
            <Header />
            <DashboardPage />
        </>
    );
}
