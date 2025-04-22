import React, { useState } from "react";
import { router, usePage } from "@inertiajs/react";
import { BaseButton } from "../../UI/Button/Button";
import styles from "./signup.module.scss";
import { Container } from "../../UI/Button/Container/Container";

export default function SignupPage() {
    const { props } = usePage();
    const [formData, setFormData] = useState({
        phone: "",
        name: "",
        telegram_id: "",
        password: "",
        password_confirmation: "",
        _token: props.csrf_token,
    });

    const handleSubmit = (e) => {
        e.preventDefault();
        router.post("/register", formData);
    };

    const handleChange = (e) => {
        setFormData({
            ...formData,
            [e.target.name]: e.target.value,
        });
    };

    return (
        <Container>
            <div className={styles.root}>
                <div className={styles.wrapper}>
                    <h2>Регистрация</h2>

                    {props.errors && (
                        <div className={styles.error}>
                            {Object.values(props.errors).map((error, index) => (
                                <div key={index}>{error}</div>
                            ))}
                        </div>
                    )}

                    <form className={styles.form} onSubmit={handleSubmit}>
                        <input
                            type="hidden"
                            name="_token"
                            value={formData._token}
                        />

                        <div className={styles.inputs}>
                            <div className={styles.label}>
                                <label htmlFor="phone">
                                    Телефон <span>*</span>
                                </label>
                                <input
                                    id="phone"
                                    name="phone"
                                    type="tel"
                                    value={formData.phone}
                                    onChange={handleChange}
                                    placeholder="+7 (XXX) XXX-XX-XX"
                                    required
                                />
                            </div>

                            <div className={styles.label}>
                                <label htmlFor="name">
                                    Имя <span>*</span>
                                </label>
                                <input
                                    id="name"
                                    name="name"
                                    type="text"
                                    placeholder="Виолетта"
                                    value={formData.name}
                                    onChange={handleChange}
                                    required
                                />
                            </div>

                            <div className={styles.label}>
                                <label htmlFor="telegram_id">ID Telegram</label>
                                <input
                                    id="telegram_id"
                                    name="telegram_id"
                                    placeholder="@vilka"
                                    type="text"
                                    value={formData.telegram_id}
                                    onChange={handleChange}
                                />
                            </div>

                            <div className={styles.label}>
                                <label htmlFor="password">
                                    Пароль <span>*</span>
                                </label>
                                <input
                                    id="password"
                                    name="password"
                                    type="password"
                                    placeholder="Пароль..."
                                    value={formData.password}
                                    onChange={handleChange}
                                    required
                                />
                            </div>

                            <div className={styles.label}>
                                <label htmlFor="password_confirmation">
                                    Подтверждение пароля <span>*</span>
                                </label>
                                <input
                                    id="password_confirmation"
                                    name="password_confirmation"
                                    type="password"
                                    placeholder="Подтвердите пароль..."
                                    value={formData.password_confirmation}
                                    onChange={handleChange}
                                    required
                                />
                            </div>
                        </div>

                        <BaseButton type="submit">
                            Зарегистрироваться
                        </BaseButton>
                    </form>
                </div>
            </div>
        </Container>
    );
}
