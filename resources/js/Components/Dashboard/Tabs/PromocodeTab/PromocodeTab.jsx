import React, { useState } from "react";

import { format } from "date-fns";
import { ru } from "date-fns/locale";
import { BaseButton } from "../../../UI/Button/Button";
import styles from "./PromocodeTab.module.scss";
import toast from "react-hot-toast";
import clsx from "clsx";

export function PromocodeTab({ promocodes = [] }) {
    const copyToClipboard = (code) => {
        navigator.clipboard.writeText(code);
        toast.success("Промокод скопирован!");
    };

    const getStatusStyle = (statusId, validTo) => {
        const now = new Date();
        const expired = new Date(validTo) < now;

        if (expired) return "expired";

        switch (statusId) {
            case 1:
                return "active";
            case 2:
                return "inactive";
            default:
                return "";
        }
    };

    const [filter, setFilter] = useState("all");

    const filteredPromocodes = promocodes.filter((promo) => {
        if (filter === "active")
            return (
                promo.status_id === 1 && new Date(promo.valid_to) > new Date()
            );
        if (filter === "expired") return new Date(promo.valid_to) < new Date();
        return true;
    });

    return (
        <div className={styles.promocodes}>
            <h2>Мои промокоды</h2>
            <div className={styles.btns}>
                <BaseButton onClick={() => setFilter("all")}>Все</BaseButton>
                <BaseButton onClick={() => setFilter("active")}>
                    Активные
                </BaseButton>
                <BaseButton onClick={() => setFilter("expired")}>
                    Истекшие
                </BaseButton>
            </div>

            {!promocodes || promocodes.length === 0 ? (
                <p>У вас нет активных промокодов</p>
            ) : (
                <div className={styles.list}>
                    {filteredPromocodes.map((promocode) => {
                        const statusClass = getStatusStyle(
                            promocode.status_id,
                            promocode.valid_to
                        );
                        const isExpired =
                            new Date(promocode.valid_to) < new Date();

                        return (
                            <div
                                key={promocode.id}
                                className={clsx(
                                    styles.promocode_card,
                                    styles[statusClass]
                                )}
                            >
                                <div className={styles.promocode_header}>
                                    <h3>{promocode.code}</h3>
                                    <span className={styles.discount}>
                                        -{promocode.discount}%
                                    </span>
                                </div>

                                <div className={styles.promocode_details}>
                                    <p>
                                        <strong>Срок действия:</strong> до{" "}
                                        {format(
                                            new Date(promocode.valid_to),
                                            "dd MMMM yyyy",
                                            { locale: ru }
                                        )}
                                    </p>
                                    <p>
                                        <strong>Статус:</strong>{" "}
                                        {isExpired
                                            ? "Истек"
                                            : promocode.status_id === 1
                                            ? "Активен"
                                            : "Неактивен"}
                                    </p>
                                    {promocode.description && (
                                        <p className={styles.description}>
                                            {promocode.description}
                                        </p>
                                    )}
                                </div>

                                <div className={styles.promocode_actions}>
                                    <BaseButton
                                        onClick={() =>
                                            copyToClipboard(promocode.code)
                                        }
                                        disabled={
                                            isExpired ||
                                            promocode.status_id !== 1
                                        }
                                    >
                                        Скопировать
                                    </BaseButton>
                                </div>
                            </div>
                        );
                    })}
                </div>
            )}
        </div>
    );
}
