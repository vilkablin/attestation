import React from "react";
import { BaseButton } from "../../../UI/Button/Button";

export function AppointmentsTab({ appointments, onCancel }) {
    return (
        <div className="appointments-tab">
            <h2>Мои записи</h2>

            {!appointments || appointments.length === 0 ? (
                <p>У вас нет активных записей</p>
            ) : (
                <div className="appointments-list">
                    {appointments.map((appointment) => (
                        <div key={appointment.id} className="appointment-card">
                            <div className="info">
                                <h3>{appointment.service.name}</h3>
                                <p>
                                    Дата:{" "}
                                    {new Date(
                                        appointment.date
                                    ).toLocaleString()}
                                </p>
                                <p>Место: {appointment.location.address}</p>
                                <p>Статус: {appointment.status.name}</p>
                                <p>Цена: {appointment.price} ₽</p>
                            </div>

                            {appointment.status.id === 1 && (
                                <BaseButton
                                    onClick={() => onCancel(appointment.id)}
                                    className="cancel-btn"
                                >
                                    Отменить
                                </BaseButton>
                            )}
                        </div>
                    ))}
                </div>
            )}
        </div>
    );
}
