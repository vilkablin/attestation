import React, { useState, useEffect } from "react";
import { useForm } from "@inertiajs/inertia-react";
import { router } from "@inertiajs/react";
import styles from "./AppointmentCreate.module.scss";
import { Container } from "../UI/Container/Container";
import { BaseButton } from "../UI/Button/Button";
import dayjs from "dayjs";
import toast, { Toaster } from "react-hot-toast";

export default function AppointmentCreatePage({ service, locations }) {
    const { data, setData, post, errors } = useForm({
        service_id: service.id,
        location_id: "",
        employee_id: "",
        date: "",
        promocode: "",
    });

    const [availableHours, setAvailableHours] = useState([]);
    const [price, setPrice] = useState(service.price || 0);
    const [discountedPrice, setDiscountedPrice] = useState(service.price || 0);
    const [promoStatus, setPromoStatus] = useState("");

    useEffect(() => {
        if (data.location_id) {
            const location = locations.find(
                (loc) => loc.id === Number(data.location_id)
            );

            if (location && location.location_services?.length > 0) {
                // Найдём нужную услугу по ID
                const serviceEntry = location.location_services.find(
                    (entry) => entry.service_id === service.id
                );

                if (serviceEntry) {
                    const newPrice = parseFloat(serviceEntry.price);
                    setPrice(newPrice);

                    // Перерасчёт скидки
                    if (data.promocode) {
                        const discountMatch = promoStatus.match(/(\d+)%/);
                        const discount = discountMatch
                            ? parseInt(discountMatch[1])
                            : 0;
                        const discounted =
                            newPrice - (newPrice * discount) / 100;
                        setDiscountedPrice(discounted);
                    } else {
                        setDiscountedPrice(newPrice);
                    }
                } else {
                    setPrice(0);
                    setDiscountedPrice(0);
                }
            } else {
                setPrice(0);
                setDiscountedPrice(0);
            }
        }
    }, [data.location_id, locations, data.promocode]);

    useEffect(() => {
        if (data.location_id && data.date) {
            fetchAvailableHours();
        }
    }, [data.location_id, data.date]);

    const fetchAvailableHours = () => {
        router.get(
            `/appointment/${data.location_id}/available-hours`,
            { date: data.date, service_id: data.service_id },
            {
                preserveScroll: true,
                preserveState: true,
                only: ["availableHours"],
                onSuccess: (page) => {
                    setAvailableHours(page.props.availableHours);
                },
            }
        );
    };

    const handlePromoCheck = () => {
        if (!data.promocode) {
            toast.error("Введите промокод");
            return;
        }

        router.get(
            "/promocode/check",
            { code: data.promocode, service_id: data.service_id },
            {
                preserveScroll: true,
                preserveState: true,
                only: ["discount"],
                onSuccess: (page) => {
                    if (page.props.discount !== null) {
                        const discountAmount =
                            (price * page.props.discount) / 100;
                        const finalPrice = price - discountAmount;

                        setDiscountedPrice(finalPrice);
                        setPromoStatus(
                            `Промокод применен: -${page.props.discount}%`
                        );
                        toast.success(
                            `Промокод применён: скидка ${page.props.discount}%`
                        );
                    } else {
                        setDiscountedPrice(price);
                        setPromoStatus("Неверный или недействующий промокод");
                        toast.error("Неверный или недействующий промокод");
                    }
                },
                onError: () => {
                    toast.error("Ошибка при проверке промокода");
                },
            }
        );
    };

    const handleSubmit = (e) => {
        e.preventDefault();
        post("/appointments");
    };

    return (
        <>
            <Toaster position="top-right" />
            <Container>
                <div className={styles.form}>
                    <h1 className={styles.title}>
                        Записаться на услугу: {service.name}
                    </h1>
                    <form onSubmit={handleSubmit} className={styles.formInner}>
                        <div className={styles.field}>
                            <label htmlFor="location_id">Адрес</label>
                            <select
                                name="location_id"
                                value={data.location_id}
                                onChange={(e) =>
                                    setData("location_id", e.target.value)
                                }
                            >
                                <option value="">Выберите адрес</option>
                                {locations.map((location) => (
                                    <option
                                        key={location.id}
                                        value={location.id}
                                    >
                                        {location.address}
                                    </option>
                                ))}
                            </select>
                            {errors.location_id && (
                                <div className={styles.error}>
                                    {errors.location_id}
                                </div>
                            )}
                        </div>

                        <div className={styles.field}>
                            <label htmlFor="date">Дата</label>
                            <input
                                type="datetime-local"
                                name="date"
                                value={data.date}
                                onChange={(e) =>
                                    setData("date", e.target.value)
                                }
                            />
                            {errors.date && (
                                <div className={styles.error}>
                                    {errors.date}
                                </div>
                            )}
                        </div>

                        {data.date && (
                            <div className={styles.field}>
                                <label>Доступное время для записи</label>
                                <ul className={styles.timeList}>
                                    {availableHours.length ? (
                                        availableHours.map((item) => (
                                            <li
                                                key={`${item.employee_id}-${item.time}`}
                                                className={`${
                                                    styles.timeItem
                                                } ${
                                                    data.employee_id ===
                                                        item.employee_id &&
                                                    data.date.endsWith(
                                                        item.time
                                                    )
                                                        ? styles.active
                                                        : ""
                                                }`}
                                                onClick={() => {
                                                    const [hours, minutes] =
                                                        item.time.split(":");
                                                    const selected = dayjs(
                                                        data.date
                                                    )
                                                        .hour(Number(hours))
                                                        .minute(Number(minutes))
                                                        .second(0);
                                                    setData({
                                                        ...data,
                                                        date: selected.format(
                                                            "YYYY-MM-DDTHH:mm"
                                                        ),
                                                        employee_id:
                                                            item.employee_id,
                                                    });
                                                }}
                                            >
                                                {item.time} –{" "}
                                                {item.employee_name}
                                            </li>
                                        ))
                                    ) : (
                                        <h4>Нет доступных записей</h4>
                                    )}
                                </ul>
                            </div>
                        )}

                        <div className={styles.field}>
                            <label htmlFor="promocode">Промокод</label>
                            <input
                                type="text"
                                name="promocode"
                                value={data.promocode}
                                onChange={(e) =>
                                    setData("promocode", e.target.value)
                                }
                            />
                            <BaseButton
                                type="button"
                                onClick={handlePromoCheck}
                            >
                                Проверить
                            </BaseButton>
                            {promoStatus && <div>{promoStatus}</div>}
                        </div>

                        <div className={styles.totalPrice}>
                            <strong>Итоговая цена:</strong> {discountedPrice} ₽
                        </div>

                        <BaseButton
                            type="submit"
                            disabled={
                                !data.date ||
                                !data.location_id ||
                                !data.employee_id
                            }
                        >
                            Записаться
                        </BaseButton>
                    </form>
                </div>
            </Container>
        </>
    );
}
