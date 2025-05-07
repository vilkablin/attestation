import React from "react";
import SignupPage from "../../Components/Sections/Signup/SignupPage";
import { Header } from "../../Components/UI/Header/Header";
import { Head } from "@inertiajs/react";
import { Footer } from "../../Components/UI/Footer/Footer";

export default function Signup() {
    return (
        <>
            <Head title="Регистрация" />
            <Header />
            <SignupPage />
            <Footer />
        </>
    );
}
