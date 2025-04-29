import React from "react";
import styles from "./GallerySection.module.scss";
import { Container } from "../../UI/Container/Container";

import img1 from "@images/gallery/1.png";
import img2 from "@images/gallery/2.png";
import img3 from "@images/gallery/3.png";
import img4 from "@images/gallery/4.png";
import img5 from "@images/gallery/5.png";
import img6 from "@images/gallery/6.png";
import img7 from "@images/gallery/7.png";
import img8 from "@images/gallery/8.png";

export const GallerySection = () => {
    return (
        <div className={styles.gallery}>
            <Container>
                <div className={styles.title}>
                    <h2>Популярные услуги</h2>
                    <p>Отмоем от грязи до блеска за считанные минуты!</p>
                </div>
            </Container>

            <div className={styles.marquee}>
                <div className={styles.top}>
                    <img src={img1} alt="image" />
                    <img src={img2} alt="image" />
                    <img src={img3} alt="image" />
                    <img src={img4} alt="image" />
                    <img src={img1} alt="image" />
                    <img src={img2} alt="image" />
                    <img src={img3} alt="image" />
                    <img src={img4} alt="image" />
                </div>
            </div>
            <div className={styles.marquee}>
                <div className={styles.bottom}>
                    <img src={img5} alt="image" />
                    <img src={img6} alt="image" />
                    <img src={img7} alt="image" />
                    <img src={img8} alt="image" />
                    <img src={img5} alt="image" />
                    <img src={img6} alt="image" />
                    <img src={img7} alt="image" />
                    <img src={img8} alt="image" />
                </div>
            </div>
        </div>
    );
};
