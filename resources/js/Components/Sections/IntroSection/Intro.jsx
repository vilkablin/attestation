import React from "react";
import bg from "@images/introbg.png";
import { BaseButton } from "../../UI/Button/Button";
import styles from "./Intro.module.scss";
import { Container } from "../../UI/Button/Container/Container";

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
                    <div>
                        <BaseButton size="l">Записаться сейчас</BaseButton>
                    </div>
                </div>
            </div>
        </Container>
    );
};
