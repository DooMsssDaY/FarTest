<?php
/**
 * Класс с настройками
 */
Class Config {
    
    // настройки подключения к БД
    const DB_NAME = "farTest";
    const DB_USER = "root";
    const DB_PASSWORD = "";
    const DB_HOST = "localhost";
    const DB_tab_prefix = "dbtr_";

    // настройки ограничений ввода данных
    const MIN_LANGTH_NAME = 2;
    const MAX_LANGTH_NAME = 50;
    const MIN_LANGTH_PASS = 6;

    // настройки для загрузки изображений
    const MAX_SIZE_IMG = 2000;
    const AVAILABLE_EXT = "jpg,png,gif,bmp,jpeg";
    const UPLOAD_PATH = "upload/images/";

    // другие данные сайта
    const ADMIN_NAME = "Турушев Николай";
    const ADMIN_MAIL = "Tur.Nik.8@mail.ru";
}
