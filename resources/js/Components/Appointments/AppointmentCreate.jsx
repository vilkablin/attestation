import React, { useState, useEffect } from "react";
import { useForm } from "@inertiajs/inertia-react";
import { router, usePage } from "@inertiajs/react";
import { h2 } from "motion/react-client";

export default function AppointmentCreatePage({ service, locations }) {
    const { data, setData, post, errors } = useForm({
        service_id: service[0].id,
        location_id: "",
        employee_id: "",
        date: "",
    });

    console.log(data);

    const [availableHours, setAvailableHours] = useState([]);

    function toLocalDateTimeInputValue(date) {
        const pad = (n) => n.toString().padStart(2, "0");

        return `${date.getFullYear()}-${pad(date.getMonth() + 1)}-${pad(
            date.getDate()
        )}T${pad(date.getHours())}:${pad(date.getMinutes())}`;
    }

    useEffect(() => {
        if (data.location_id && data.date) {
            // Здесь логика получения доступных часов для выбранной локации и даты
            fetchAvailableHours();
        }
    }, [data.location_id, data.date]);

    const fetchAvailableHours = () => {
        router.get(
            `/appointment/${data.location_id}/available-hours`,
            { date: data.date },
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

    const handleSubmit = (e) => {
        e.preventDefault();

        // Логируем данные перед отправкой
        console.log("Form data before submit: ", data);

        // Проверяем, выбран ли сотрудник
        if (!data.employee_id) {
            alert("Пожалуйста, выберите сотрудника.");
            return;
        }

        // Если все в порядке, отправляем данные
        post("/appointments");
    };

    return (
        <div>
            <h1>Create Appointment</h1>
            <form onSubmit={handleSubmit}>
                <div>
                    <label htmlFor="location_id">Location</label>
                    <select
                        name="location_id"
                        value={data.location_id}
                        onChange={(e) => setData("location_id", e.target.value)}
                    >
                        <option value="">Select a location</option>
                        {locations.map((location) => (
                            <option key={location.id} value={location.id}>
                                {location.address}
                            </option>
                        ))}
                    </select>
                    {errors.location_id && <div>{errors.location_id}</div>}
                </div>

                <div>
                    <label htmlFor="date">Appointment Date</label>
                    <input
                        type="datetime-local"
                        name="date"
                        value={data.date}
                        onChange={(e) => setData("date", e.target.value)}
                    />
                    {errors.date && <div>{errors.date}</div>}
                </div>

                <div>
                    <label>Available Hours</label>
                    <ul>
                        {availableHours.length ? (
                            availableHours.map((item) => (
                                <li
                                    key={`${item.employee_id}-${item.time}`}
                                    style={{
                                        cursor: "pointer",
                                        backgroundColor:
                                            data.employee_id ===
                                                item.employee_id &&
                                            data.date.endsWith(item.time)
                                                ? "#d1fae5"
                                                : "transparent",
                                    }}
                                    onClick={() => {
                                        const date = new Date(data.date);
                                        const [hours, minutes] =
                                            item.time.split(":");
                                        date.setHours(hours);
                                        date.setMinutes(minutes);
                                        date.setSeconds(0);
                                        setData({
                                            ...data,
                                            date: toLocalDateTimeInputValue(
                                                date
                                            ),
                                            employee_id: item.employee_id,
                                        });
                                    }}
                                >
                                    {item.time} – {item.employee_name}
                                </li>
                            ))
                        ) : (
                            <h4>
                                К сожалению на эту дату нет доступных записей
                            </h4>
                        )}
                    </ul>
                </div>

                <button type="submit">Create Appointment</button>
            </form>
        </div>
    );
}
