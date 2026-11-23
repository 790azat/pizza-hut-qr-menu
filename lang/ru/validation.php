<?php

return [
    'boolean' => 'Поле «:attribute» должно быть да/нет.',
    'confirmed' => 'Поле «:attribute» не совпадает с подтверждением.',
    'email' => 'Введите корректный email.',
    'exists' => 'Выбранное значение «:attribute» не найдено.',
    'in' => 'Недопустимое значение поля «:attribute».',
    'integer' => 'Поле «:attribute» должно быть целым числом.',
    'max' => [
        'numeric' => 'Поле «:attribute» не может быть больше :max.',
        'string' => 'Поле «:attribute» не может быть длиннее :max символов.',
    ],
    'min' => [
        'numeric' => 'Поле «:attribute» не может быть меньше :min.',
        'string' => 'Поле «:attribute» должно быть не короче :min символов.',
    ],
    'regex' => 'Неверный формат поля «:attribute».',
    'required' => 'Заполните поле «:attribute».',
    'string' => 'Поле «:attribute» должно быть текстом.',

    'attributes' => [
        'email' => 'email',
        'password' => 'пароль',
        'current_password' => 'текущий пароль',
    ],
];
