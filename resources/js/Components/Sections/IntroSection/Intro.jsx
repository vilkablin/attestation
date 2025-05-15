import React from "react";
import bg from "@images/introbg.png";
import { BaseButton } from "../../UI/Button/Button";
import styles from "./Intro.module.scss";
import { Container } from "../../UI/Container/Container";
import { Link } from "@inertiajs/react";

export const Intro = () => {
    return (
        <Container>
            <div className={styles.root}>
                <img src={bg} alt="intro" className={styles.image} />
                <div className={styles.info}>
                    <h1>
                        Чистота, которая <br /> <span>впечатляет</span>
                    </h1>
                    <p>
                        Автомойка Будет Чисто! - место, <br />
                        где ваш автомобиль обретает <br /> идеальную чистоту и
                        сияние!
                    </p>
                    <Link href={"/services"}>
                        <BaseButton size="l">Записаться сейчас</BaseButton>
                    </Link>
                </div>
            </div>
        </Container>
    );
};
