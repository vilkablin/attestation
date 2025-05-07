import React from "react";
import styles from "./PopularServicesSection.module.scss";
import { Container } from "../../UI/Container/Container";
import { ServiceCard } from "../../UI/ServiceCard/ServiceCard";

export const PopularServicesSection = ({ services }) => {
    return (
        <Container>
            <div className={styles.root}>
                <div className={styles.title}>
                    <h2>Популярные услуги</h2>
                    <p>Отмоем от грязи до блеска за считанные минуты!</p>
                </div>

                <div className={styles.services}>
                    {services.map((item) => {
                        return <ServiceCard data={item} key={item.id} />;
                    })}
                </div>
            </div>
        </Container>
    );
};
