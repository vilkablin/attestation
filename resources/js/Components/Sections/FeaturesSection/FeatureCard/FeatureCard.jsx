import React from "react";
import styles from "./FeatureCard.module.scss";

export const FeatureCard = ({ data }) => {
    return (
        <div className={styles.feature}>
            <img src={data.icon} alt={data.title} />
            <h3>{data.title}</h3>
            <p>{data.text}</p>
        </div>
    );
};
