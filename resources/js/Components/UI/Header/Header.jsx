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
