import React, { useState } from "react";
import { Head, router, usePage } from "@inertiajs/react";

import styles from "./dashboard.module.scss";
import { Container } from "../UI/Container/Container";
import { ProfileTab } from "./Tabs/ProfileTab/ProfileTab";
import { AppointmentsTab } from "./Tabs/AppointmentTab/AppointmentsTab";
import { PromocodeTab } from "./Tabs/PromocodeTab/PromocodeTab";

export default function DashboardPage() {
    const { auth } = usePage().props;
    const [activeTab, setActiveTab] = useState("appointments");
    const [editing, setEditing] = useState(false);
    const [formData, setFormData] = useState({
        name: auth.user.name,
        phone: auth.user?.phone,
        telegram_id: auth.user.telegram_id || "",
        current_password: "",
        new_password: "",
        new_password_confirmation: "",
    });

    const handleUpdateProfile = (e) => {
        e.preventDefault();
        router.put("/profile", formData, {
            onSuccess: () => setEditing(false),
        });
    };

    const handleCancelAppointment = (appointmentId) => {
        router.delete(`/appointments/${appointmentId}`);
    };

    return (
        <Container>
            <div className={styles.dashboard}>
                <div className={styles.sidebar}>
                    <div className={styles.userInfo}>
                        <img
                            src={
                                auth.user?.image
                                    ? `/storage/${auth.user.image}`
                                    : "/images/default.webp"
                            }
                            alt="Аватар"
                            className={styles.avatar}
                        />
                        <h3>{auth.user.name}</h3>
                        <p>{auth.user.phone}</p>
                    </div>

                    <nav className={styles.nav}>
                        <button
                            className={`${styles.navItem} ${
                                activeTab === "profile" ? styles.active : ""
                            }`}
                            onClick={() => setActiveTab("profile")}
                        >
                            Настройки профиля
                        </button>
                        <button
                            className={`${styles.navItem} ${
                                activeTab === "appointments"
                                    ? styles.active
                                    : ""
                            }`}
                            onClick={() => setActiveTab("appointments")}
                        >
                            Мои записи
                        </button>
                        <button
                            className={`${styles.navItem} ${
                                activeTab === "promocodes" ? styles.active : ""
                            }`}
                            onClick={() => setActiveTab("promocodes")}
                        >
                            Мои промокоды
                        </button>
                        {auth.user.role_id === 2 && (
                            <a target="_blank" href="/admin">
                                <button className={`${styles.navItem}`}>
                                    Администратор
                                </button>
                            </a>
                        )}
                    </nav>
                </div>

                <div className={styles.content}>
                    {activeTab === "profile" && (
                        <ProfileTab
                            formData={formData}
                            setFormData={setFormData}
                            editing={editing}
                            setEditing={setEditing}
                            handleSubmit={handleUpdateProfile}
                        />
                    )}

                    {activeTab === "appointments" && (
                        <AppointmentsTab
                            appointments={auth.user.appointments ?? null}
                            onCancel={handleCancelAppointment}
                        />
                    )}

                    {activeTab === "promocodes" && (
                        <PromocodeTab promocodes={auth.user.promocodes} />
                    )}
                </div>
            </div>
        </Container>
    );
}
