import React from "react";
import clsx from "clsx";
import styles from "./button.module.scss";

export const BaseButton = ({
    variant,
    size = "m",
    iconRight,
    className,
    children,
    disabled,
    ...props
}) => {
    const classes = clsx(
        styles.button,
        styles[variant],
        styles[size],
        className
    );

    return (
        <button className={classes} {...props} disabled={disabled}>
            {children}
            {iconRight && (
                <img src={iconRight} className={styles.icon} alt="icon" />
            )}
        </button>
    );
};
