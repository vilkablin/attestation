import React, { useState } from "react";
import { Container } from "../Container/Container";
import styles from "./header.module.scss";
import { Link, usePage } from "@inertiajs/react";
import { BaseButton } from "../Button/Button";
import { Menu, X } from "lucide-react";

export const Header = () => {
    const { auth } = usePage().props;
    const [isMenuOpen, setIsMenuOpen] = useState(false);

    const toggleMenu = () => setIsMenuOpen((prev) => !prev);
    const closeMenu = () => setIsMenuOpen(false);

    return (
        <Container>
            <header className={styles.root}>
                <Link className={styles.logo} href="/">
                    <svg
                        width="32"
                        height="32"
                        viewBox="0 0 32 32"
                        fill="none"
                        xmlns="http://www.w3.org/2000/svg"
                    >
                        <path
                            d="M17.5 1.5C17.5 0.66875 16.8313 0 16 0C15.1688 0 14.5 0.66875 14.5 1.5V6.5C14.5 7.33125 15.1688 8 16 8C16.8313 8 17.5 7.33125 17.5 6.5V1.5ZM11.6125 14H20.3813C20.8063 14 21.1813 14.2688 21.325 14.6625L22.5188 18H9.48126L10.675 14.6625C10.8188 14.2625 11.1938 14 11.6188 14H11.6125ZM6.90626 13.3188L5.13751 18.275C3.88126 18.8062 3.00001 20.05 3.00001 21.5V30C3.00001 31.1063 3.89376 32 5.00001 32H6.00001C7.10626 32 8.00001 31.1063 8.00001 30V28H24V30C24 31.1063 24.8938 32 26 32H27C28.1063 32 29 31.1063 29 30V21.5C29 20.05 28.1188 18.8062 26.8625 18.275L25.0938 13.3188C24.3813 11.3313 22.5 10 20.3875 10H11.6188C9.50626 10 7.61876 11.3313 6.91251 13.3188H6.90626ZM8.00001 21.5C8.39784 21.5 8.77937 21.658 9.06067 21.9393C9.34198 22.2206 9.50001 22.6022 9.50001 23C9.50001 23.3978 9.34198 23.7794 9.06067 24.0607C8.77937 24.342 8.39784 24.5 8.00001 24.5C7.60219 24.5 7.22066 24.342 6.93935 24.0607C6.65805 23.7794 6.50001 23.3978 6.50001 23C6.50001 22.6022 6.65805 22.2206 6.93935 21.9393C7.22066 21.658 7.60219 21.5 8.00001 21.5ZM22.5 23C22.5 22.6022 22.658 22.2206 22.9394 21.9393C23.2207 21.658 23.6022 21.5 24 21.5C24.3978 21.5 24.7794 21.658 25.0607 21.9393C25.342 22.2206 25.5 22.6022 25.5 23C25.5 23.3978 25.342 23.7794 25.0607 24.0607C24.7794 24.342 24.3978 24.5 24 24.5C23.6022 24.5 23.2207 24.342 22.9394 24.0607C22.658 23.7794 22.5 23.3978 22.5 23ZM2.43751 2.4375C1.85001 3.025 1.85001 3.975 2.43751 4.55625L5.43751 7.55625C6.02501 8.14375 6.97501 8.14375 7.55626 7.55625C8.13751 6.96875 8.14376 6.01875 7.55626 5.4375L4.56251 2.4375C3.97501 1.85 3.02501 1.85 2.44376 2.4375H2.43751ZM27.4375 2.4375L24.4375 5.4375C23.85 6.025 23.85 6.975 24.4375 7.55625C25.025 8.1375 25.975 8.14375 26.5563 7.55625L29.5563 4.55625C30.1438 3.96875 30.1438 3.01875 29.5563 2.4375C28.9688 1.85625 28.0188 1.85 27.4375 2.4375Z"
                            fill="#09C690"
                        />
                    </svg>
                    Будет Чисто!
                </Link>

                <button
                    className={styles.burger}
                    onClick={toggleMenu}
                    aria-label="Toggle menu"
                >
                    {isMenuOpen ? (
                        <X size={32} color="var(--accent_green)" />
                    ) : (
                        <Menu size={32} color="var(--accent_green)" />
                    )}
                </button>

                <nav
                    className={`${styles.nav} ${isMenuOpen ? styles.open : ""}`}
                >
                    <Link
                        className={styles.nav__a}
                        href="/"
                        onClick={closeMenu}
                    >
                        Главная
                    </Link>
                    <Link
                        className={styles.nav__a}
                        href="/services"
                        onClick={closeMenu}
                    >
                        Услуги
                    </Link>
                    <Link
                        className={styles.nav__a}
                        href="/#benefits"
                        onClick={closeMenu}
                    >
                        О нас
                    </Link>
                    <Link
                        className={styles.nav__a}
                        href="#footer"
                        onClick={closeMenu}
                    >
                        Контакты
                    </Link>

                    {auth.user && (
                        <Link
                            className={styles.nav__a}
                            href="/dashboard"
                            onClick={closeMenu}
                        >
                            Профиль
                        </Link>
                    )}

                    {auth.user ? (
                        <Link href="/logout" method="post" onClick={closeMenu}>
                            Выйти
                        </Link>
                    ) : (
                        <Link href="/signin" onClick={closeMenu}>
                            <BaseButton>Войти</BaseButton>
                        </Link>
                    )}
                </nav>

                {/* затемнение фона при открытом меню */}
                {isMenuOpen && (
                    <div className={styles.overlay} onClick={closeMenu} />
                )}
            </header>
        </Container>
    );
};
