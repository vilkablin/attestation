import { Container } from "../../UI/Container/Container";
import styles from "./FAQ.module.scss";
import { FAQ_data } from "./FAQData";
import { FaqItem } from "./FaqItem/FaqItem";

export const FAQ = () => {
    return (
        <Container>
            <div className={styles.root}>
                <div className={styles.title}>
                    <h2> Вопросы и ответы</h2>
                    <p>Делимся информацией и экономим ваше время! </p>
                </div>
                <div className={styles.faq}>
                    {FAQ_data.map((item, index) => (
                        <FaqItem
                            key={index}
                            question={item.question}
                            answer={item.answer}
                        />
                    ))}
                </div>
            </div>
        </Container>
    );
};
