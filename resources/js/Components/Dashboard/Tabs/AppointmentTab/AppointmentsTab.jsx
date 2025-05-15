import React, { useState } from "react";
import { BaseButton } from "../../../UI/Button/Button";
import styles from "./appointmentTab.module.scss";
import Tooltip from "../../../UI/Tooltip/Tooltip";
import ConfirmModal from "../../../UI/ConfirmModal/ConfirmModal";
import clsx from "clsx";
import dayjs from "dayjs";
import utc from "dayjs/plugin/utc";
import timezone from "dayjs/plugin/timezone";

dayjs.extend(utc);
dayjs.extend(timezone);

export function AppointmentsTab({ appointments, onCancel }) {
    const [isModalOpen, setIsModalOpen] = useState(false);
    const [selectedAppointmentId, setSelectedAppointmentId] = useState(null);

    const openConfirmModal = (id) => {
        setSelectedAppointmentId(id);
        setIsModalOpen(true);
    };

    const handleDelete = () => {
        if (selectedAppointmentId) {
            onCancel(selectedAppointmentId);
        }
        setIsModalOpen(false);
        setSelectedAppointmentId(null);
    };

    return (
        <div className={styles.appointment}>
            <h2>История записей</h2>

            {!appointments || appointments.length === 0 ? (
                <p>У вас нет активных записей</p>
            ) : (
                <div className={styles.list}>
                    <div className={styles.header}>
                        <div className={styles.date}>
                            <p>Дата</p>
                        </div>
                        <div className={styles.title}>
                            <p>Название услуги</p>
                        </div>
                        <div className={styles.status}>
                            <p>Статус</p>
                        </div>
                        <div className={styles.address}>
                            <p>Адрес</p>
                        </div>
                        <div className={styles.btn}></div>
                    </div>

                    {appointments.map((appointment) => {
                        return (
                            <div key={appointment.id} className={styles.item}>
                                <div className={styles.date}>
                                    <p>
                                        {dayjs(appointment.date)
                                            .subtract(3, "hour")
                                            .format("DD.MM.YYYY HH:mm")}
                                    </p>
                                </div>

                                <Tooltip text={appointment.service.name}>
                                    <div className={styles.title}>
                                        <p>{appointment.service.name}</p>
                                    </div>
                                </Tooltip>

                                <div className={clsx(styles.status)}>
                                    <p
                                        className={clsx(
                                            styles[appointment.status.color]
                                        )}
                                    >
                                        {appointment.status.name}
                                    </p>
                                </div>

                                <Tooltip text={appointment.location.address}>
                                    <div className={styles.address}>
                                        <p>
                                            {appointment.location.address.replace(
                                                /^г\.\s*[^,]+,\s*/i,
                                                ""
                                            )}
                                        </p>
                                    </div>
                                </Tooltip>

                                <div className={styles.btn}>
                                    {appointment.status.id === 1 && (
                                        <BaseButton
                                            size="xxs"
                                            onClick={() =>
                                                openConfirmModal(appointment.id)
                                            }
                                            className={styles.cancel}
                                        >
                                            <svg
                                                width="31"
                                                height="32"
                                                viewBox="0 0 31 32"
                                                fill="none"
                                                xmlns="http://www.w3.org/2000/svg"
                                            >
                                                <path
                                                    d="M15.5 17.8083L19.2458 21.5541C19.4826 21.7909 19.784 21.9093 20.15 21.9093C20.516 21.9093 20.8173 21.7909 21.0541 21.5541C21.291 21.3173 21.4094 21.0159 21.4094 20.6499C21.4094 20.2839 21.291 19.9826 21.0541 19.7458L17.3083 15.9999L21.0541 12.2541C21.291 12.0173 21.4094 11.7159 21.4094 11.3499C21.4094 10.9839 21.291 10.6826 21.0541 10.4458C20.8173 10.2089 20.516 10.0905 20.15 10.0905C19.784 10.0905 19.4826 10.2089 19.2458 10.4458L15.5 14.1916L11.7541 10.4458C11.5173 10.2089 11.216 10.0905 10.85 10.0905C10.484 10.0905 10.1826 10.2089 9.94582 10.4458C9.70901 10.6826 9.59061 10.9839 9.59061 11.3499C9.59061 11.7159 9.70901 12.0173 9.94582 12.2541L13.6916 15.9999L9.94582 19.7458C9.70901 19.9826 9.59061 20.2839 9.59061 20.6499C9.59061 21.0159 9.70901 21.3173 9.94582 21.5541C10.1826 21.7909 10.484 21.9093 10.85 21.9093C11.216 21.9093 11.5173 21.7909 11.7541 21.5541L15.5 17.8083ZM15.5 28.9166C13.7132 28.9166 12.034 28.5773 10.4625 27.8988C8.89095 27.2202 7.52394 26.3001 6.36144 25.1385C5.19894 23.9768 4.27884 22.6098 3.60115 21.0374C2.92345 19.465 2.58418 17.7859 2.58331 15.9999C2.58245 14.214 2.92173 12.5348 3.60115 10.9624C4.28056 9.39003 5.20066 8.02302 6.36144 6.86138C7.52222 5.69974 8.88923 4.77964 10.4625 4.10109C12.0357 3.42253 13.7149 3.08325 15.5 3.08325C17.2851 3.08325 18.9642 3.42253 20.5375 4.10109C22.1107 4.77964 23.4777 5.69974 24.6385 6.86138C25.7993 8.02302 26.7198 9.39003 27.4001 10.9624C28.0804 12.5348 28.4192 14.214 28.4167 15.9999C28.4141 17.7859 28.0748 19.465 27.3988 21.0374C26.7228 22.6098 25.8027 23.9768 24.6385 25.1385C23.4743 26.3001 22.1073 27.2206 20.5375 27.9C18.9677 28.5795 17.2885 28.9183 15.5 28.9166ZM15.5 26.3333C18.3847 26.3333 20.8281 25.3322 22.8302 23.3301C24.8323 21.328 25.8333 18.8846 25.8333 15.9999C25.8333 13.1152 24.8323 10.6718 22.8302 8.66971C20.8281 6.66763 18.3847 5.66659 15.5 5.66659C12.6153 5.66659 10.1719 6.66763 8.16977 8.66971C6.16769 10.6718 5.16665 13.1152 5.16665 15.9999C5.16665 18.8846 6.16769 21.328 8.16977 23.3301C10.1719 25.3322 12.6153 26.3333 15.5 26.3333Z"
                                                    fill="#00A677"
                                                />
                                            </svg>
                                        </BaseButton>
                                    )}
                                </div>
                            </div>
                        );
                    })}
                </div>
            )}

            <ConfirmModal
                isOpen={isModalOpen}
                onClose={() => setIsModalOpen(false)}
                onConfirm={handleDelete}
                title="Удаление записи"
                message="Вы уверены, что хотите удалить эту запись?"
            />
        </div>
    );
}
