import React from "react";
import styles from "./Tooltip.module.scss";

const Tooltip = ({ children, text }) => {
    return (
        <div className={styles.wrapper}>
            {children}
            <span className={styles.tooltip}>{text}</span>
        </div>
    );
};

export default Tooltip;
