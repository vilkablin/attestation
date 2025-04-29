import React, { useCallback, useState } from "react";
import { BaseButton } from "../../../UI/Button/Button";
import styles from "./ProfileTab.module.scss";
import { Link, router } from "@inertiajs/react";

export function ProfileTab({
    formData,
    setFormData,
    editing,
    setEditing,
    handleSubmit,
}) {
    const [previewImage, setPreviewImage] = useState(null);
    const [dragActive, setDragActive] = useState(false);

    const handleChange = (e) => {
        setFormData({
            ...formData,
            [e.target.name]: e.target.value,
        });
    };

    const handleDrag = useCallback((e) => {
        e.preventDefault();
        e.stopPropagation();

        if (e.type === "dragenter" || e.type === "dragover") {
            setDragActive(true);
        } else if (e.type === "dragleave") {
            setDragActive(false);
        }
    }, []);

    const handleDrop = useCallback((e) => {
        e.preventDefault();
        e.stopPropagation();
        setDragActive(false);

        if (e.dataTransfer.files && e.dataTransfer.files[0]) {
            processImageFile(e.dataTransfer.files[0]);
        }
    }, []);

    const handleFileChange = (e) => {
        if (e.target.files && e.target.files[0]) {
            processImageFile(e.target.files[0]);
        }
    };

    const processImageFile = (file) => {
        if (!file.type.match("image.*")) {
            alert("Пожалуйста, выберите файл изображения");
            return;
        }

        if (file.size > 2 * 1024 * 1024) {
            alert("Размер файла не должен превышать 2MB");
            return;
        }

        const reader = new FileReader();
        reader.onload = () => {
            setPreviewImage(reader.result);
        };
        reader.readAsDataURL(file);

        const formData = new FormData();
        formData.append("image", file);
        router.post("/profile/image", formData, {
            onSuccess: () => {
                router.reload({ only: ["auth.user"] });
            },
        });
    };

    return (
        <div className={styles.profileTab}>
            <h2>Настройки профиля</h2>

            {!editing ? (
                <div className={styles.profileView}>
                    <div className={styles.field}>
                        <label>Имя:</label>
                        <p>{formData.name}</p>
                    </div>
                    <div className={styles.field}>
                        <label>Телефон:</label>
                        <p>{formData.phone}</p>
                    </div>
                    <div>
                        <BaseButton onClick={() => setEditing(true)}>
                            Редактировать
                        </BaseButton>
                    </div>
                </div>
            ) : (
                <form onSubmit={handleSubmit}>
                    <div className={styles.formGroup}>
                        <label>Имя</label>
                        <input
                            type="text"
                            name="name"
                            value={formData.name}
                            onChange={handleChange}
                            required
                        />
                    </div>

                    <div className={styles.formGroup}>
                        <label>Телефон</label>
                        <input
                            type="tel"
                            name="phone"
                            value={formData.phone}
                            onChange={handleChange}
                            required
                        />
                    </div>

                    <div className={styles.formGroup}>
                        <label>Аватар</label>
                        <div
                            className={`${styles.uploadContainer} ${
                                dragActive ? styles.dragActive : ""
                            }`}
                            onDragEnter={handleDrag}
                            onDragLeave={handleDrag}
                            onDragOver={handleDrag}
                            onDrop={handleDrop}
                        >
                            <input
                                id="avatar-upload"
                                type="file"
                                accept="image/*"
                                onChange={handleFileChange}
                                className={styles.fileInput}
                            />
                            <label
                                htmlFor="avatar-upload"
                                className={styles.uploadLabel}
                            >
                                {previewImage ? (
                                    <img
                                        src={previewImage}
                                        alt="Предпросмотр"
                                        className={styles.previewImage}
                                    />
                                ) : (
                                    <>
                                        <span className={styles.uploadIcon}>
                                            📤
                                        </span>
                                        <p>
                                            Перетащите изображение сюда или
                                            кликните для выбора
                                        </p>
                                        <p className={styles.hint}>
                                            Поддерживаются JPG, PNG до 2MB
                                        </p>
                                    </>
                                )}
                            </label>
                            {previewImage && (
                                <button
                                    type="button"
                                    className={styles.removeImage}
                                    onClick={() => setPreviewImage(null)}
                                >
                                    Удалить
                                </button>
                            )}
                        </div>
                    </div>

                    <div className={styles.buttons}>
                        <BaseButton type="submit">Сохранить</BaseButton>
                        <BaseButton>
                            <Link href={"/"}>Изменить пароль</Link>
                        </BaseButton>
                        <BaseButton
                            type="button"
                            onClick={() => setEditing(false)}
                        >
                            Отмена
                        </BaseButton>
                    </div>
                </form>
            )}
        </div>
    );
}
