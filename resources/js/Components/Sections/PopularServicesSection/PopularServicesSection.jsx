import React from "react";
import styles from "./PopularServicesSection.module.scss";
import image from "@images/service.png";
import { Container } from "../../UI/Container/Container";
import { ServiceCard } from "../../UI/ServiceCard/ServiceCard";

const data = [
    {
        image: image,
        title: "Комплексная мойка",
        price: 2500,
        id: 1,
    },
    {
        image: image,
        title: "Комплексная мойка",
        price: 2500,
        id: 2,
    },
    {
        image: image,
        title: "Комплексная мойка",
        price: 2500,
        id: 3,
    },
];

export const PopularServicesSection = () => {
    return (
        <Container>
            <div className={styles.root}>
                <div className={styles.title}>
                    <h2>Популярные услуги</h2>
                    <p>Отмоем от грязи до блеска за считанные минуты!</p>
                </div>

                <div className={styles.services}>
                    {data.map((item) => {
                        return <ServiceCard data={item} key={item.id} />;
                    })}
                </div>
            </div>
        </Container>
    );
};
