import React from "react";
import { SigninPage } from "../../Components/Sections/Signin/SigninPage";
import { Header } from "../../Components/UI/Header/Header";
import { Head } from "@inertiajs/react";
import { Footer } from "../../Components/UI/Footer/Footer";

export default function Signin() {
    return (
        <>
            <Head title="Вход" />
            <Header />
            <SigninPage />;
            <Footer />
        </>
    );
}
