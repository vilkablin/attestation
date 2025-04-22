import React from "react";
import { Head } from "@inertiajs/react";
import { Intro } from "../Components/Sections/IntroSection/Intro";
import { Header } from "../Components/UI/Header/Header";

export default function Home() {
    return (
        <>
            <Head title="Главная" />
            <Header />
            <Intro />
        </>
    );
}
