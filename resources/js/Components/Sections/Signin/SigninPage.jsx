import React, { useState } from "react";
import { Link, router, usePage } from "@inertiajs/react";
import styles from "./signin.module.scss";
import { Container } from "../../UI/Container/Container";
import { BaseButton } from "../../UI/Button/Button";

export const SigninPage = () => {
    const { props } = usePage();
    const [formData, setFormData] = useState({
        phone: "",
        password: "",
        remember: false,
        _token: props.csrf_token,
    });

    const handleSubmit = (e) => {
        e.preventDefault();
        router.post("/signin/store", formData);
    };

    const handleChange = (e) => {
        const { name, value, type, checked } = e.target;
        setFormData({
            ...formData,
            [name]: type === "checkbox" ? checked : value,
        });
    };

    return (
        <Container>
            <div className={styles.root}>
                <div className={styles.wrapper}>
                    <h2>Вход в аккаунт</h2>
                    <form className={styles.form} onSubmit={handleSubmit}>
                        <input
                            type="hidden"
                            name="_token"
                            value={formData._token}
                        />

                        <div className={styles.inputs}>
                            <div className={styles.label}>
                                <label htmlFor="phone">Телефон</label>
                                <input
                                    id="phone"
                                    name="phone"
                                    type="tel"
                                    value={formData.phone}
                                    onChange={handleChange}
                                    required
                                    autoFocus
                                />
                                {props.errors?.phone && (
                                    <p className={styles.error}>
                                        {props.errors.phone}
                                    </p>
                                )}
                            </div>

                            <div className={styles.label}>
                                <label htmlFor="password">Пароль</label>
                                <input
                                    id="password"
                                    name="password"
                                    type="password"
                                    value={formData.password}
                                    onChange={handleChange}
                                    required
                                />
                                {props.errors?.password && (
                                    <p className={styles.error}>
                                        {props.errors.password}
                                    </p>
                                )}
                            </div>

                            <div className={styles.check}>
                                <input
                                    id="remember"
                                    name="remember"
                                    type="checkbox"
                                    checked={formData.remember}
                                    onChange={handleChange}
                                />
                                <label
                                    htmlFor="remember"
                                    className="ml-2 block text-sm text-gray-900"
                                >
                                    Запомнить меня
                                </label>
                            </div>
                        </div>

                        <BaseButton
                            type="submit"
                            className="w-full flex justify-center py-2 px-4 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500"
                        >
                            Войти
                        </BaseButton>
                    </form>

                    <div className={styles.text}>
                        <Link href="/signup">
                            Нет аккаунта? Зарегистрироваться
                        </Link>
                    </div>
                </div>
            </div>
        </Container>
    );
};
