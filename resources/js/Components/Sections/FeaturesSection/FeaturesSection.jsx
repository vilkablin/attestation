import React from "react";
import styles from "./FeaturesSection.module.scss";
import { Container } from "../../UI/Container/Container";
import bg from "@images/featuresbg.png";
import { FEATURES_DATA } from "./featuresSectionData";
import { FeatureCard } from "./FeatureCard/FeatureCard";

export const FeaturesSection = () => {
    return (
        <Container>
            <div className={styles.root}>
                <div className={styles.title}>
                    <h2>Почему именно мы?</h2>
                    <p>Сделаем ваш автомобиль эффектнее и ярче!</p>
                </div>
                <img src={bg} alt="background" className={styles.bg} />
                <div className={styles.features}>
                    {FEATURES_DATA.map((item) => {
                        return <FeatureCard data={item} key={item.id} />;
                    })}
                </div>
            </div>
        </Container>
    );
};
