#!/bin/bash
# Путь к файлу лицензии
LIC_FILE="/usr/local/FlashphonerWebCallServer/conf/flashphoner.license"

# Проверка на наличие лицензии
if [ ! -f "$LIC_FILE" ]; then
  echo "Лицензия не найдена, активируем..."
  echo "2DE3A24D-479C-4533-BBA5-3609D9BEE794" > "$LIC_FILE"
  # Выполнение активации
  cd /usr/local/FlashphonerWebCallServer/bin
  ./activation.sh
else
  echo "Лицензия уже активирована."
fi
