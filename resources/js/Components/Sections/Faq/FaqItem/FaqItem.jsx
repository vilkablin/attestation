import clsx from "clsx";
import { motion } from "motion/react";
import { useState } from "react";
import styles from "./FaqItem.module.scss";
import { animateHelper } from "../../../../Helpers/animation.helper";
import IconArrow from "@icons/faq-icon.svg";

export const FaqItem = ({ question, answer }) => {
    const [isActive, setIsActive] = useState(false);

    const toggleActive = () => setIsActive((prev) => !prev);

    return (
        <div className={styles.faqItem} onClick={toggleActive}>
            <div
                className={clsx(styles.question, { [styles.active]: isActive })}
            >
                <h3
                    as="h3"
                    variant="headlines.h2_m_text"
                    className={styles.questionText}
                >
                    {question}
                </h3>
                <div className={styles.icon}>
                    <svg
                        width="40"
                        height="40"
                        viewBox="0 0 40 40"
                        fill="none"
                        xmlns="http://www.w3.org/2000/svg"
                    >
                        <path
                            d="M11.6666 16.6667L19.9999 25L28.3333 16.6667"
                            stroke="currentColor"
                            strokeWidth="2"
                            strokeLinecap="round"
                            strokeLinejoin="round"
                        />
                    </svg>
                </div>
            </div>
            <motion.div
                variants={animateHelper("faqItem")}
                initial="close"
                animate={isActive ? "open" : "close"}
                exit="close"
                transition={{
                    duration: 0.8,
                    ease: [0.04, 0.62, 0.23, 0.98],
                }}
            >
                <p variant="paragraphs.body_m" className={styles.answer}>
                    {answer}
                </p>
            </motion.div>
        </div>
    );
};
