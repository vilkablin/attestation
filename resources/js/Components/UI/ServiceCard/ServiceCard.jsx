import React from "react";
import styles from "./ServiceCard.module.scss";
import { BaseButton } from "../Button/Button";
import arrow from "@icons/arrow-icon.svg";
export const ServiceCard = ({ data }) => {
    return (
        <div className={styles.card}>
            <div className={styles.imgWrapper}>
                <img src={data.image} alt={data.title} />
            </div>
            <h3>{data.title}</h3>
            <div className={styles.price}>
                <h4>{data.price} ₽</h4>
                <BaseButton iconRight={arrow}>Записаться</BaseButton>
            </div>
        </div>
    );
};
