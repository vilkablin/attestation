import React, { useEffect, useState } from "react";
import styles from "./TelegramModal.module.scss";
import { BaseButton } from "../Button/Button";

export const TelegramModal = () => {
    const [isOpen, setIsOpen] = useState(false);

    useEffect(() => {
        const timer = setTimeout(() => {
            setIsOpen(true);
        }, 7000); // 7 секунд

        return () => clearTimeout(timer);
    }, []);

    if (!isOpen) return null;

    return (
        <div className={styles.overlay} onClick={() => setIsOpen(false)}>
            <div className={styles.modal} onClick={(e) => e.stopPropagation()}>
                <h2>Дарим скидку за подписку на наш Telegram-бот!</h2>
                <p>
                    Подпишитесь на нашего telegram-бота, получите промокод на
                    скидку 10%. Узнавайте о новых акциях и быстро записывайтесь
                    на услуги!
                </p>
                <a
                    href="https://t.me/BudetChistoKazanBot"
                    target="_blank"
                    rel="noopener noreferrer"
                >
                    <BaseButton size="ml">Подписаться</BaseButton>
                </a>
                <button
                    className={styles.close}
                    onClick={() => setIsOpen(false)}
                >
                    ×
                </button>
            </div>
        </div>
    );
};
