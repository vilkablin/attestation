import React from "react";
import { Head, Link } from "@inertiajs/react";
import { BaseButton } from "../../UI/Button/Button";

export default function ServicePage({ service }) {
    return (
        <>
            <Head title={service.title} />
            <div className="max-w-3xl mx-auto p-6">
                <img
                    src={`/storage/${service.image}`}
                    alt={service.title}
                    className="w-full rounded-xl mb-4"
                />
                <h1 className="text-3xl font-bold mb-2">{service.title}</h1>
                <p className="text-gray-700 mb-4">
                    {service.description || "Описание скоро появится."}
                </p>
                <p className="text-xl font-semibold mb-6">
                    {Number(service.price).toLocaleString("ru-RU")} ₽
                </p>
                <Link href={`/appointments/create?service_id=${service.id}`}>
                    <BaseButton>Записаться</BaseButton>
                </Link>
            </div>
        </>
    );
}
