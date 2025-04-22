import React from "react";
import { Container } from "../Button/Container/Container";
import styles from "./header.module.scss";
import { Link, usePage } from "@inertiajs/react";
import { BaseButton } from "../Button/Button";

export const Header = () => {
    const { auth } = usePage().props;

    return (
        <Container>
            <header className={styles.root}>
                <Link className={styles.logo} href="/">
                    Будет Чисто!
                </Link>

                <nav>
                    <Link className={styles.nav__a} href="/">
                        Главная
                    </Link>
                    <Link className={styles.nav__a} href="/">
                        Услуги
                    </Link>
                    <Link className={styles.nav__a} href="/">
                        О нас
                    </Link>
                    <Link className={styles.nav__a} href="/">
                        Контакты
                    </Link>

                    {auth.user ? (
                        <Link href="/logout" method="post">
                            Выйти
                        </Link>
                    ) : (
                        <Link href="/signin">
                            <BaseButton>Войти</BaseButton>
                        </Link>
                    )}
                </nav>
            </header>
        </Container>
    );
};
