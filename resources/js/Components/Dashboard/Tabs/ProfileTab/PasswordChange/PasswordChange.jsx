import React from "react";
import styles from "./PasswordChange.module.scss";

export const PasswordChange = () => {
    return (
        <div className={styles.pass}>
            <div className={styles.formGroup}>
                <label>Текущий пароль</label>
                <input
                    type="password"
                    name="current_password"
                    value={formData.current_password}
                    onChange={handleChange}
                />
            </div>

            <div className={styles.formGroup}>
                <label>Новый пароль</label>
                <input
                    type="password"
                    name="new_password"
                    value={formData.new_password}
                    onChange={handleChange}
                />
            </div>

            <div className={styles.formGroup}>
                <label>Подтверждение пароля</label>
                <input
                    type="password"
                    name="new_password_confirmation"
                    value={formData.new_password_confirmation}
                    onChange={handleChange}
                />
            </div>
        </div>
    );
};
