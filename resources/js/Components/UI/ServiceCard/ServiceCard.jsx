import React from "react";
import styles from "./ServiceCard.module.scss";
import { BaseButton } from "../Button/Button";
import arrow from "@icons/arrow-icon.svg";
import { Link, usePage } from "@inertiajs/react";

export const ServiceCard = ({ data }) => {
    const { auth } = usePage().props;
    return (
        <div className={styles.card}>
            <Link href={`/services/${data.id}`} className={styles.imgWrapper}>
                <img src={`storage/${data.image}`} alt={data.title} />
            </Link>
            <h3>{data.title}</h3>
            <div className={styles.price}>
                <h4>{Number(data.price).toLocaleString("ru-RU")} ₽</h4>

                <Link
                    href={`/appointments/make/${data.id}`}
                    className={styles.link}
                >
                    <BaseButton disabled={!auth.user} iconRight={arrow}>
                        Записаться
                    </BaseButton>
                </Link>
            </div>
        </div>
    );
};
