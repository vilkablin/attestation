import React from "react";
import styles from "./ConfirmModal.module.scss";
import { BaseButton } from "../Button/Button";

const ConfirmModal = ({ isOpen, onClose, onConfirm, title, message }) => {
    if (!isOpen) return null;

    return (
        <div className={styles.overlay}>
            <div className={styles.modal}>
                <h2>{title || "Подтвердите действие"}</h2>
                <p>{message || "Вы уверены, что хотите продолжить?"}</p>
                <div className={styles.buttons}>
                    <button className={styles.cancel} onClick={onClose}>
                        Отмена
                    </button>
                    <BaseButton className={styles.confirm} onClick={onConfirm}>
                        Подтвердить
                    </BaseButton>
                </div>
            </div>
        </div>
    );
};

export default ConfirmModal;
