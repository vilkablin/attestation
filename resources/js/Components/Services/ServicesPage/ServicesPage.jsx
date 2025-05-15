import React, { useState } from "react";
import styles from "./ServicesPage.module.scss";
import { Container } from "../../UI/Container/Container";
import { usePage, router, Link } from "@inertiajs/react";
import { ServiceCard } from "../../UI/ServiceCard/ServiceCard";
import { BaseButton } from "../../UI/Button/Button";

export const ServicesPage = ({ services, filters }) => {
    const [search, setSearch] = useState(filters.search || "");
    const [priceFrom, setPriceFrom] = useState(filters.price_from || "");
    const [priceTo, setPriceTo] = useState(filters.price_to || "");

    const handleFilter = (e) => {
        e.preventDefault();
        router.get(
            "/services",
            {
                search,
                price_from: priceFrom,
                price_to: priceTo,
            },
            {
                preserveScroll: true,
                preserveState: true,
            }
        );
    };

    return (
        <Container>
            <div className={styles.breads}>
                <Link href={"/"}>Главная</Link> <p>/ Каталог услуг</p>
            </div>
            <div className={styles.catalog}>
                <h2>Каталог услуг</h2>
                <form onSubmit={handleFilter} className={styles.filters}>
                    <div>
                        <label>Поиск</label>
                        <input
                            type="text"
                            value={search}
                            onChange={(e) => setSearch(e.target.value)}
                            placeholder="Название услуги"
                        />
                    </div>
                    <div>
                        <label>Цена от</label>
                        <input
                            type="number"
                            value={priceFrom}
                            onChange={(e) => setPriceFrom(e.target.value)}
                        />
                    </div>
                    <div>
                        <label>Цена до</label>
                        <input
                            type="number"
                            value={priceTo}
                            onChange={(e) => setPriceTo(e.target.value)}
                        />
                    </div>
                    <BaseButton type="submit">Найти</BaseButton>
                </form>

                <div className={styles.cardsGrid}>
                    {services.map((service) => (
                        <ServiceCard key={service.id} data={service} />
                    ))}
                </div>
            </div>
        </Container>
    );
};
